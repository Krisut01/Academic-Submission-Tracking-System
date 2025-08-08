<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\ThesisDocument;
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
     * Display the list of assigned thesis documents for review
     */
    public function reviews(Request $request)
    {
        $this->ensureUserIsFaculty();
        
        $query = ThesisDocument::with(['user'])
            ->whereIn('status', ['pending', 'under_review']);

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

        $documents = $query->orderBy('submission_date', 'asc')->paginate(15);

        // Get statistics
        $stats = [
            'total_pending' => ThesisDocument::whereIn('status', ['pending', 'under_review'])->count(),
            'urgent_reviews' => ThesisDocument::where('status', 'pending')
                ->where('submission_date', '<=', now()->subDays(5))->count(),
            'completed_this_month' => ThesisDocument::where('status', 'approved')
                ->whereMonth('reviewed_at', now()->month)->count(),
            'returned_for_revision' => ThesisDocument::where('status', 'returned_for_revision')
                ->whereMonth('updated_at', now()->month)->count(),
        ];

        return view('faculty.thesis.reviews', compact('documents', 'stats'));
    }

    /**
     * Show a specific thesis document for review
     */
    public function show(ThesisDocument $document)
    {
        $this->ensureUserIsFaculty();
        
        $document->load(['user', 'reviewer']);
        
        return view('faculty.thesis.show', compact('document'));
    }

    /**
     * Update the review status of a thesis document
     */
    public function updateReview(Request $request, ThesisDocument $document)
    {
        $this->ensureUserIsFaculty();
        
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

        return redirect()->route('faculty.thesis.reviews')
            ->with('success', $statusMessage);
    }

    /**
     * Display thesis progress tracking interface
     */
    public function progress(Request $request)
    {
        $this->ensureUserIsFaculty();
        
        $query = User::where('role', 'student')
            ->with(['thesisDocuments' => function($q) {
                $q->orderBy('submission_date', 'desc');
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
}
