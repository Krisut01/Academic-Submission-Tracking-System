<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\ThesisDocument;
use App\Models\PanelAssignment;
use App\Models\AcademicForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ThesisReviewController extends Controller
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
     * Check if faculty is assigned to any panel for this document
     */
    private function isFacultyInPanelAssignment(ThesisDocument $document, int $facultyId): bool
    {
        return $document->panelAssignments()
            ->where(function ($query) use ($facultyId) {
                $query->where('panel_chair_id', $facultyId)
                      ->orWhere('secretary_id', $facultyId)
                                          ->orWhereJsonContains('panel_members', (string)$facultyId);
            })
            ->exists();
    }

    /**
     * Display the list of assigned thesis documents for review
     */
    public function reviews(Request $request)
    {
        $this->ensureUserIsFaculty();
        
        $facultyId = Auth::id();
        
        // Get panel assignments where faculty is a member
        $panelAssignments = PanelAssignment::where(function ($q) use ($facultyId) {
            $q->where('panel_chair_id', $facultyId)
              ->orWhere('secretary_id', $facultyId)
              ->orWhereJsonContains('panel_members', (string)$facultyId);
        })->get();
        
        $studentIds = $panelAssignments->pluck('student_id')->unique();
        $panelDocumentIds = $panelAssignments->pluck('thesis_document_id')->filter();
        
        // Show documents where faculty is assigned as adviser, reviewer, or panel member
        $query = ThesisDocument::with(['user'])
            ->whereIn('status', ['pending', 'under_review', 'approved'])
            ->where(function ($q) use ($facultyId, $studentIds, $panelDocumentIds) {
                // Direct assignment (adviser OR reviewer)
                $q->where(function ($directQuery) use ($facultyId) {
                    $directQuery->where('adviser_id', $facultyId)
                               ->orWhere('reviewed_by', $facultyId);
                })
                // Panel assignment documents
                ->orWhereIn('id', $panelDocumentIds)
                // Final manuscripts for students where faculty is panel member
                ->orWhere(function ($finalQuery) use ($studentIds) {
                    $finalQuery->where('document_type', 'final_manuscript')
                               ->whereIn('user_id', $studentIds);
                });
            });

        // Apply filters
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('student_search')) {
            $search = $request->student_search;
            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $thesisDocuments = $query->orderBy('submission_date', 'asc')->paginate(15);

        // Get statistics
        $stats = [
            'total_pending' => ThesisDocument::whereIn('status', ['pending', 'under_review'])->count(),
            'urgent_reviews' => ThesisDocument::where('status', 'pending')
                ->where('submission_date', '<=', now()->subDays(5))->count(),
            'under_review' => ThesisDocument::where('status', 'under_review')->count(),
            'this_week' => ThesisDocument::where('created_at', '>=', now()->subWeek())->count(),
        ];

        return view('faculty.thesis.reviews', compact('thesisDocuments', 'stats'));
    }

    /**
     * Show a specific thesis document for review
     */
    public function show(ThesisDocument $document)
    {
        $this->ensureUserIsFaculty();
        
        $facultyId = Auth::id();
        
        // Check if faculty has access to this document
        if (!$document->facultyHasAccess($facultyId)) {
            abort(403, 'You do not have permission to view this document. You must be assigned as adviser, reviewer, or panel member.');
        }
        
        $document->load(['user', 'reviewer']);
        
        return view('faculty.thesis.show', compact('document'));
    }

    /**
     * Update the review status of a thesis document
     */
    public function updateReview(Request $request, ThesisDocument $document)
    {
        $this->ensureUserIsFaculty();
        
        $facultyId = Auth::id();
        
        // Check if faculty has access to review this document
        if (!$document->facultyHasAccess($facultyId)) {
            abort(403, 'You do not have permission to review this document. You must be assigned as adviser, reviewer, or panel member.');
        }
        
        $request->validate([
            'status' => ['required', Rule::in(['approved', 'returned_for_revision', 'under_review'])],
            'review_comments' => 'nullable|string|max:2000',
        ]);

        // Store old status for event
        $oldStatus = $document->status;
        $reviewer = Auth::user();

        $document->update([
            'status' => $request->status,
            'review_comments' => $request->review_comments,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Sync approval with PanelAssignmentReview records
        $this->syncPanelAssignmentApprovals($document, Auth::id(), $request->status, $request->review_comments);

        // Fire event for cross-system updates
        event(new \App\Events\ThesisStatusUpdated(
            $document,
            $oldStatus,
            $request->status,
            $reviewer,
            [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'review_method' => 'faculty_interface',
                'has_comments' => !empty($request->review_comments),
            ]
        ));

        $statusMessage = match($request->status) {
            'approved' => 'Thesis document has been approved successfully!',
            'returned_for_revision' => 'Thesis document has been returned for revision.',
            'under_review' => 'Thesis document status updated to under review.',
        };

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $statusMessage,
                'status' => $request->status,
                'document_id' => $document->id,
            ]);
        }

        return redirect()->route('faculty.thesis.reviews')
            ->with('success', $statusMessage);
    }

    /**
     * Sync thesis document approvals with panel assignment reviews
     */
    private function syncPanelAssignmentApprovals($document, $facultyId, $status, $comments)
    {
        // For final manuscripts, find panel assignments for the student
        if ($document->document_type === 'final_manuscript') {
            $panelAssignments = \App\Models\PanelAssignment::where('student_id', $document->user_id)
                ->where('defense_type', 'final_defense')
                ->get();
        } else {
            // For other documents, find panel assignments for this document
            $panelAssignments = \App\Models\PanelAssignment::where('thesis_document_id', $document->id)->get();
        }
        
        foreach ($panelAssignments as $panelAssignment) {
            // Determine the reviewer role
            $reviewerRole = $this->determineReviewerRole($panelAssignment, $facultyId);
            
            if ($reviewerRole) {
                // Map thesis document status to panel assignment review status
                $reviewStatus = $this->mapStatusToReviewStatus($status);
                
                // Create or update panel assignment review
                \App\Models\PanelAssignmentReview::updateOrCreate([
                    'panel_assignment_id' => $panelAssignment->id,
                    'reviewer_id' => $facultyId,
                ], [
                    'thesis_document_id' => $document->id,
                    'reviewer_role' => $reviewerRole,
                    'status' => $reviewStatus,
                    'review_comments' => $comments,
                    'reviewed_at' => now(),
                ]);
            }
        }
    }

    /**
     * Determine the reviewer role in the panel assignment
     */
    private function determineReviewerRole($panelAssignment, $facultyId)
    {
        if ($panelAssignment->panel_chair_id == $facultyId) {
            return 'panel_chair';
        } elseif ($panelAssignment->secretary_id == $facultyId) {
            return 'panel_member'; // Secretary is treated as panel member in reviews
        } elseif (in_array($facultyId, $panelAssignment->panel_member_ids ?? [])) {
            return 'panel_member';
        } elseif ($panelAssignment->thesisDocument?->adviser_id == $facultyId) {
            return 'adviser';
        }
        
        return null;
    }

    /**
     * Map thesis document status to panel assignment review status
     */
    private function mapStatusToReviewStatus($status)
    {
        return match($status) {
            'approved' => 'approved',
            'returned_for_revision' => 'needs_revision',
            'under_review' => 'pending',
            default => 'pending'
        };
    }

    /**
     * Display thesis progress tracking interface
     */
    public function progress(Request $request)
    {
        $this->ensureUserIsFaculty();
        
        $facultyId = Auth::id();
        
        // Get panel assignments where faculty is a member
        $panelAssignments = PanelAssignment::where(function ($q) use ($facultyId) {
            $q->where('panel_chair_id', $facultyId)
              ->orWhere('secretary_id', $facultyId)
              ->orWhereJsonContains('panel_members', (string)$facultyId);
        })->get();
        
        $studentIds = $panelAssignments->pluck('student_id')->unique();
        $panelDocumentIds = $panelAssignments->pluck('thesis_document_id')->filter();
        
        // Show students who have documents assigned to this faculty
        $query = User::where('role', 'student')
            ->where(function ($q) use ($facultyId, $studentIds, $panelDocumentIds) {
                $q->whereHas('thesisDocuments', function ($docQuery) use ($facultyId, $panelDocumentIds, $studentIds) {
                    // Direct assignment (adviser OR reviewer)
                    $docQuery->where(function ($directQuery) use ($facultyId) {
                        $directQuery->where('adviser_id', $facultyId)
                                   ->orWhere('reviewed_by', $facultyId);
                    })
                    // Panel assignment documents
                    ->orWhereIn('id', $panelDocumentIds)
                    // Final manuscripts for students where faculty is panel member
                    ->orWhere(function ($finalQuery) use ($studentIds) {
                        $finalQuery->where('document_type', 'final_manuscript')
                                   ->whereIn('user_id', $studentIds);
                    });
                })
                // Include students who have final manuscripts and faculty is in their panel
                ->orWhereIn('id', $studentIds);
            })
            ->with(['thesisDocuments' => function($q) use ($facultyId, $panelDocumentIds, $studentIds) {
                // Only load documents this faculty has access to
                $q->where(function ($docQuery) use ($facultyId, $panelDocumentIds, $studentIds) {
                    // Direct assignment (adviser OR reviewer)
                    $docQuery->where(function ($directQuery) use ($facultyId) {
                        $directQuery->where('adviser_id', $facultyId)
                                   ->orWhere('reviewed_by', $facultyId);
                    })
                    // Panel assignment documents
                    ->orWhereIn('id', $panelDocumentIds)
                    // Final manuscripts for students where faculty is panel member
                    ->orWhere(function ($finalQuery) use ($studentIds) {
                        $finalQuery->where('document_type', 'final_manuscript')
                                   ->whereIn('user_id', $studentIds);
                    });
                })->orderBy('submission_date', 'desc');
            }]);

        // Apply filters
        if ($request->filled('student_search')) {
            $search = $request->student_search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('progress_filter')) {
            $filter = $request->progress_filter;
            $query->whereHas('thesisDocuments', function($q) use ($filter) {
                switch($filter) {
                    case 'proposal_submitted':
                        $q->where('document_type', 'proposal');
                        break;
                    case 'panel_assigned':
                        $q->where('document_type', 'panel_assignment');
                        break;
                    case 'final_defense':
                        $q->where('document_type', 'final_manuscript');
                        break;
                }
            });
        }

        $students = $query->paginate(12);

        // Calculate progress for each student
        foreach ($students as $student) {
            $student->progress = $this->calculateStudentProgress($student);
        }

        return view('faculty.thesis.progress', compact('students'));
    }

    /**
     * Calculate progress percentage for a student
     */
    private function calculateStudentProgress($student)
    {
        $stages = ['proposal', 'panel_assignment', 'approval_sheet', 'final_manuscript'];
        $completed = 0;
        $current_stage = null;
        $status_details = [];

        foreach ($stages as $stage) {
            $document = $student->thesisDocuments->where('document_type', $stage)->first();
            
            $status_details[$stage] = [
                'submitted' => $document ? true : false,
                'status' => $document ? $document->status : 'not_submitted',
                'date' => $document ? $document->submission_date : null,
                'document' => $document
            ];

            if ($document && $document->status === 'approved') {
                $completed++;
            } elseif ($document && in_array($document->status, ['pending', 'under_review', 'returned_for_revision'])) {
                $current_stage = $stage;
                break;
            } elseif (!$document) {
                $current_stage = $stage;
                break;
            }
        }

        return [
            'percentage' => ($completed / count($stages)) * 100,
            'completed_stages' => $completed,
            'total_stages' => count($stages),
            'current_stage' => $current_stage,
            'status_details' => $status_details,
            'stage_labels' => [
                'proposal' => 'Proposal Submitted',
                'panel_assignment' => 'Panel Assigned',
                'approval_sheet' => 'Approval Received',
                'final_manuscript' => 'Final Defense'
            ]
        ];
    }

    /**
     * Display statistics dashboard for faculty
     */
    public function dashboard()
    {
        $this->ensureUserIsFaculty();
        
        $stats = [
            'pending_reviews' => ThesisDocument::whereIn('status', ['pending', 'under_review'])->count(),
            'urgent_reviews' => ThesisDocument::where('status', 'pending')
                ->where('submission_date', '<=', now()->subDays(5))->count(),
            'completed_reviews' => ThesisDocument::where('status', 'approved')
                ->whereMonth('reviewed_at', now()->month)->count(),
            'active_students' => User::where('role', 'student')
                ->whereHas('thesisDocuments')->count(),
            'avg_review_time' => $this->getAverageReviewTime(),
        ];

        $recent_submissions = ThesisDocument::with(['user'])
            ->whereIn('status', ['pending', 'under_review'])
            ->orderBy('submission_date', 'desc')
            ->take(5)
            ->get();

        return view('faculty.dashboard', compact('stats', 'recent_submissions'));
    }

    /**
     * Calculate average review time in days
     */
    private function getAverageReviewTime()
    {
        $completed_reviews = ThesisDocument::whereNotNull('reviewed_at')
            ->where('reviewed_at', '>=', now()->subMonths(3))
            ->get();

        if ($completed_reviews->isEmpty()) {
            return 0;
        }

        $total_days = $completed_reviews->sum(function ($document) {
            return $document->submission_date->diffInDays($document->reviewed_at);
        });

        return round($total_days / $completed_reviews->count(), 1);
    }

    /**
     * Download a specific file from a thesis document
     */
    public function downloadFile(ThesisDocument $document, $fileIndex)
    {
        $this->ensureUserIsFaculty();
        
        $facultyId = Auth::id();
        
        // Check if faculty has access to this document
        if (!$document->facultyHasAccess($facultyId)) {
            abort(403, 'You do not have permission to download this file. You must be assigned as adviser, reviewer, or panel member.');
        }

        $files = $document->uploaded_files ?? [];

        if (!isset($files[$fileIndex])) {
            abort(404, 'File not found.');
        }

        $file = $files[$fileIndex];

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($file['path'])) {
            abort(404, 'File not found on server.');
        }

        return response()->download(storage_path('app/public/' . $file['path']), $file['original_name']);
    }
}
