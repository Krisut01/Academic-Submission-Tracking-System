<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\PanelAssignment;
use App\Models\PanelAssignmentReview;
use App\Models\ThesisDocument;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PanelAssignmentReviewController extends Controller
{
    /**
     * Check if the authenticated user is a faculty
     */
    private function ensureUserIsFaculty()
    {
        if (Auth::user()->role !== 'faculty') {
            abort(403, 'Unauthorized access. Only faculty can access this resource.');
        }
    }

    /**
     * Display panel assignments assigned to the current faculty member
     */
    public function index(Request $request)
    {
        $this->ensureUserIsFaculty();
        
        $facultyId = Auth::id();
        
        // Get panel assignments where this faculty is assigned
        $query = PanelAssignment::with(['student', 'thesisDocument', 'reviews', 'panelChair'])
            ->where(function ($q) use ($facultyId) {
                $q->where('panel_chair_id', $facultyId)
                  ->orWhere('secretary_id', $facultyId)
                  ->orWhereJsonContains('panel_members', $facultyId);
            });

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('defense_type')) {
            $query->where('defense_type', $request->defense_type);
        }

        if ($request->filled('student_search')) {
            $search = $request->student_search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $panelAssignments = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total_assignments' => PanelAssignment::where(function ($q) use ($facultyId) {
                $q->where('panel_chair_id', $facultyId)
                  ->orWhere('secretary_id', $facultyId)
                  ->orWhereJsonContains('panel_members', $facultyId);
            })->count(),
            'pending_reviews' => PanelAssignmentReview::where('reviewer_id', $facultyId)
                ->where('status', 'pending')->count(),
            'completed_reviews' => PanelAssignmentReview::where('reviewer_id', $facultyId)
                ->whereIn('status', ['approved', 'rejected', 'needs_revision'])->count(),
        ];

        return view('faculty.panel-assignments.index', compact('panelAssignments', 'stats'));
    }

    /**
     * Show a specific panel assignment for review
     */
    public function show(PanelAssignment $panelAssignment)
    {
        $this->ensureUserIsFaculty();
        
        $facultyId = Auth::id();
        
        // Check if faculty has access to this panel assignment
        if (!$this->isFacultyInPanelAssignment($panelAssignment, $facultyId)) {
            abort(403, 'You do not have permission to review this panel assignment.');
        }

        // Ensure all review records exist and sync existing approvals
        $approvalSyncService = new \App\Services\ApprovalSyncService();
        $approvalSyncService->ensureReviewRecordsExist($panelAssignment);
        $approvalSyncService->syncExistingApprovals($panelAssignment);

        // Get or create review record for this faculty
        $review = PanelAssignmentReview::firstOrCreate([
            'panel_assignment_id' => $panelAssignment->id,
            'reviewer_id' => $facultyId,
        ], [
            'thesis_document_id' => $panelAssignment->thesis_document_id,
            'reviewer_role' => $this->getFacultyRoleInPanel($panelAssignment, $facultyId),
            'status' => 'pending',
        ]);

        // Load related data
        $panelAssignment->load(['student', 'thesisDocument', 'reviews.reviewer', 'panelChair', 'secretary']);

        return view('faculty.panel-assignments.show', compact('panelAssignment', 'review'));
    }

    /**
     * Submit a panel assignment review
     */
    public function submitReview(Request $request, PanelAssignment $panelAssignment)
    {
        $this->ensureUserIsFaculty();
        
        $facultyId = Auth::id();
        
        // Check if faculty has access to this panel assignment
        if (!$this->isFacultyInPanelAssignment($panelAssignment, $facultyId)) {
            abort(403, 'You do not have permission to review this panel assignment.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected,needs_revision',
            'review_comments' => 'nullable|string|max:2000',
            'recommendations' => 'nullable|string|max:1000',
            'review_criteria' => 'nullable|array',
        ]);

        // Get or create review record
        $review = PanelAssignmentReview::firstOrCreate([
            'panel_assignment_id' => $panelAssignment->id,
            'reviewer_id' => $facultyId,
        ], [
            'thesis_document_id' => $panelAssignment->thesis_document_id,
            'reviewer_role' => $this->getFacultyRoleInPanel($panelAssignment, $facultyId),
            'status' => 'pending',
        ]);

        // Update review
        $review->update([
            'status' => $request->status,
            'review_comments' => $request->review_comments,
            'recommendations' => $request->recommendations,
            'review_criteria' => $request->review_criteria,
            'reviewed_at' => now(),
        ]);

        // Check if all reviews are completed and notify student
        $this->checkAndNotifyCompletion($panelAssignment);

        $statusMessage = match($request->status) {
            'approved' => 'Panel assignment has been approved successfully!',
            'rejected' => 'Panel assignment has been rejected.',
            'needs_revision' => 'Panel assignment needs revision.',
        };

        return redirect()->route('faculty.panel-assignments.show', $panelAssignment)
            ->with('success', $statusMessage);
    }

    /**
     * Check if faculty is assigned to this panel assignment
     */
    private function isFacultyInPanelAssignment(PanelAssignment $panelAssignment, int $facultyId): bool
    {
        return $panelAssignment->panel_chair_id === $facultyId ||
               $panelAssignment->secretary_id === $facultyId ||
               in_array($facultyId, $panelAssignment->panel_member_ids ?? []);
    }

    /**
     * Get faculty role in panel assignment
     */
    private function getFacultyRoleInPanel(PanelAssignment $panelAssignment, int $facultyId): string
    {
        if ($panelAssignment->panel_chair_id === $facultyId) {
            return 'panel_chair';
        } elseif ($panelAssignment->secretary_id === $facultyId) {
            return 'panel_member'; // Secretary is also a panel member
        } else {
            return 'panel_member';
        }
    }

    /**
     * Check if all reviews are completed and notify student
     */
    private function checkAndNotifyCompletion(PanelAssignment $panelAssignment): void
    {
        if ($panelAssignment->allReviewsCompleted()) {
            $overallStatus = $panelAssignment->overall_approval_status;
            
            // Notify student about completion
            $notification = [
                'title' => 'Panel Assignment Review Complete',
                'message' => "All panel members have completed their reviews for your {$panelAssignment->defense_type_label}. Overall status: " . ucfirst($overallStatus),
                'data' => [
                    'panel_assignment_id' => $panelAssignment->id,
                    'overall_status' => $overallStatus,
                    'defense_type' => $panelAssignment->defense_type,
                    'url' => route('student.thesis.defense'),
                ]
            ];

            Notification::createForUser(
                $panelAssignment->student_id,
                'panel_review_complete',
                $notification['title'],
                $notification['message'],
                $notification['data'],
                get_class($panelAssignment),
                $panelAssignment->id,
                'high'
            );

            // Update panel assignment status
            $panelAssignment->update([
                'status' => $overallStatus === 'approved' ? 'completed' : 'scheduled',
            ]);
        }
    }
}
