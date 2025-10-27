<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PanelAssignment;
use App\Models\ThesisDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class PanelAssignmentController extends Controller
{
    /**
     * Check if the authenticated user is an admin
     */
    private function ensureUserIsAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Only administrators can access this resource.');
        }
    }

    /**
     * Display panel assignments dashboard
     */
    public function index(Request $request)
    {
        $this->ensureUserIsAdmin();

        $query = PanelAssignment::with(['student', 'thesisDocument', 'panelChair', 'secretary'])
            ->orderBy('defense_date', 'asc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('student_search')) {
            $search = $request->student_search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->where('defense_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('defense_date', '<=', $request->date_to);
        }

        $assignments = $query->paginate(15);

        // Get statistics
        $stats = [
            'total_assignments' => PanelAssignment::count(),
            'scheduled_defenses' => PanelAssignment::where('status', 'scheduled')->count(),
            'completed_defenses' => PanelAssignment::where('status', 'completed')->count(),
            'defended_defenses' => PanelAssignment::where('status', 'completed')->whereNotNull('completed_at')->count(),
            'upcoming_defenses' => PanelAssignment::upcoming()->count(),
            'overdue_defenses' => PanelAssignment::overdue()->count(),
        ];

        // Get students ready for defense (thesis approved but no panel assigned)
        $studentsReadyForDefense = User::where('role', 'student')
            ->whereHas('thesisDocuments', function ($q) {
                $q->where('status', 'approved')
                  ->where('document_type', 'final_manuscript');
            })
            ->whereDoesntHave('panelAssignments')
            ->get();

        // Get student panel assignment requests (pre-assignments) - PENDING status for admin review
        $studentPanelRequests = ThesisDocument::with(['user'])
            ->where('document_type', 'panel_assignment')
            ->where('status', 'pending')
            ->orderBy('submission_date', 'desc')
            ->take(10)
            ->get();

        return view('admin.panel.index', compact('assignments', 'stats', 'studentsReadyForDefense', 'studentPanelRequests'));
    }


    /**
     * Approve a panel assignment request
     */
    public function approveRequest(Request $request, ThesisDocument $document)
    {
        $this->ensureUserIsAdmin();

        // Validate that this is a panel assignment request
        if ($document->document_type !== 'panel_assignment') {
            return back()->withErrors(['error' => 'This is not a panel assignment request.']);
        }

        // Update document status to approved
        $document->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_comments' => $request->comments ?? 'Panel assignment request approved by admin.'
        ]);

        // Notify student about approval
        \App\Models\Notification::createForUser(
            $document->user_id,
            'panel_request_approved',
            'Panel Assignment Request Approved',
            "Your panel assignment request has been approved. Admin will now assign panel members and schedule your defense.",
            [
                'document_id' => $document->id,
                'admin_name' => Auth::user()->name,
                'url' => route('student.thesis.panel-assignments')
            ],
            get_class($document),
            $document->id,
            'high'
        );

        return redirect()->back()->with('success', 'Panel assignment request approved successfully.');
    }

    /**
     * Reject a panel assignment request
     */
    public function rejectRequest(Request $request, ThesisDocument $document)
    {
        $this->ensureUserIsAdmin();

        // Validate that this is a panel assignment request
        if ($document->document_type !== 'panel_assignment') {
            return back()->withErrors(['error' => 'This is not a panel assignment request.']);
        }

        $request->validate([
            'comments' => 'required|string|max:1000'
        ]);

        // Update document status to returned for revision
        $document->update([
            'status' => 'returned_for_revision',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_comments' => $request->comments
        ]);

        // Notify student about rejection
        \App\Models\Notification::createForUser(
            $document->user_id,
            'panel_request_rejected',
            'Panel Assignment Request Needs Revision',
            "Your panel assignment request has been returned for revision. Please review the comments and resubmit.",
            [
                'document_id' => $document->id,
                'admin_name' => Auth::user()->name,
                'comments' => $request->comments,
                'url' => route('student.thesis.panel-assignments')
            ],
            get_class($document),
            $document->id,
            'urgent'
        );

        return redirect()->back()->with('success', 'Panel assignment request rejected. Student has been notified to revise and resubmit.');
    }

    /**
     * Show the form for creating a new panel assignment
     */
    public function create(Request $request)
    {
        $this->ensureUserIsAdmin();

        $student = null;
        $thesisDocument = null;
        $studentRequest = null;
        $preferredPanelMembers = [];

        // If creating from a student panel assignment request
        if ($request->filled('from_request')) {
            $studentRequest = ThesisDocument::where('id', $request->from_request)
                ->where('document_type', 'panel_assignment')
                ->where('status', 'approved')
                ->with('user')
                ->firstOrFail();
            
            $student = $studentRequest->user;
            
            // Find the student's approved final manuscript for the panel assignment
            $thesisDocument = ThesisDocument::where('user_id', $student->id)
                ->where('status', 'approved')
                ->where('document_type', 'final_manuscript')
                ->first();
                
            // If no approved final manuscript, get any approved thesis document
            if (!$thesisDocument) {
                $thesisDocument = ThesisDocument::where('user_id', $student->id)
                    ->where('status', 'approved')
                    ->first();
            }
            
            // Extract preferred panel members from the student's request
            if ($studentRequest->panel_members && is_array($studentRequest->panel_members)) {
                $preferredPanelMembers = $studentRequest->panel_members;
            }
        }
        // If student_id and thesis_id are provided, pre-fill the form (existing functionality)
        elseif ($request->filled('student_id') && $request->filled('thesis_id')) {
            $student = User::findOrFail($request->student_id);
            $thesisDocument = ThesisDocument::findOrFail($request->thesis_id);
        }

        // Get all faculty members for panel selection
        $facultyMembers = User::where('role', 'faculty')->orderBy('name')->get();

        // Debug: Check what thesis documents exist
        $allStudents = User::where('role', 'student')->get();
        $allThesisDocuments = ThesisDocument::all(['document_type', 'status', 'user_id', 'title']);
        
        // Get students with approved final manuscripts
        $eligibleStudents = User::where('role', 'student')
            ->whereHas('thesisDocuments', function ($q) {
                $q->where('status', 'approved')
                  ->where('document_type', 'final_manuscript');
            })
            ->orderBy('name')
            ->get();
            
        // Temporary: Also get students with ANY approved thesis documents for testing
        $studentsWithApprovedThesis = User::where('role', 'student')
            ->whereHas('thesisDocuments', function ($q) {
                $q->where('status', 'approved');
            })
            ->orderBy('name')
            ->get();
            
        // If no eligible students, use students with any approved thesis
        if ($eligibleStudents->isEmpty() && !$studentsWithApprovedThesis->isEmpty()) {
            $eligibleStudents = $studentsWithApprovedThesis;
        }
        
        // If still empty, use all students for debugging
        if ($eligibleStudents->isEmpty()) {
            $eligibleStudents = $allStudents;
        }

        return view('admin.panel.create', compact('student', 'thesisDocument', 'facultyMembers', 'eligibleStudents', 'studentRequest', 'preferredPanelMembers'));
    }

    /**
     * Store a newly created panel assignment
     */
    public function store(Request $request)
    {
        $this->ensureUserIsAdmin();

        // Check for duplicate submission
        if (!$request->has('form_submitted')) {
            return redirect()->back()
                ->withErrors(['form' => 'Invalid form submission. Please try again.'])
                ->withInput();
        }

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'thesis_document_id' => 'required|exists:thesis_documents,id',
            'thesis_title' => 'required|string|max:255',
            'thesis_description' => 'nullable|string',
            'panel_members' => 'required|array|min:3|max:5',
            'panel_members.*' => 'exists:users,id',
            'panel_chair_id' => 'required|exists:users,id',
            'secretary_id' => 'nullable|exists:users,id',
            'defense_date' => 'required|date|after:now',
            'defense_venue' => 'required|string|max:255',
            'defense_instructions' => 'nullable|string',
            'defense_type' => 'required|in:proposal_defense,final_defense,redefense,oral_defense,thesis_defense',
        ]);

        // Check if panel assignment already exists for this student and thesis
        $existingAssignment = PanelAssignment::where('student_id', $request->student_id)
            ->where('thesis_document_id', $request->thesis_document_id)
            ->where('defense_type', $request->defense_type)
            ->first();

        if ($existingAssignment) {
            return redirect()->back()
                ->withErrors(['duplicate' => 'A panel assignment already exists for this student and thesis document.'])
                ->withInput();
        }

        // Validate that panel chair is in panel members
        if (!in_array($request->panel_chair_id, $request->panel_members)) {
            return redirect()->back()
                ->withErrors(['panel_chair_id' => 'Panel chair must be one of the panel members.'])
                ->withInput();
        }

        // Create panel assignment
        $assignment = PanelAssignment::create([
            'student_id' => $request->student_id,
            'thesis_document_id' => $request->thesis_document_id,
            'thesis_title' => $request->thesis_title,
            'thesis_description' => $request->thesis_description,
            'panel_members' => $request->panel_members,
            'panel_chair_id' => $request->panel_chair_id,
            'secretary_id' => $request->secretary_id,
            'defense_date' => $request->defense_date,
            'defense_venue' => $request->defense_venue,
            'defense_instructions' => $request->defense_instructions,
            'defense_type' => $request->defense_type,
            'status' => 'scheduled',
            'created_by' => Auth::id(),
        ]);

        // If this panel assignment was created from a student request, update the request status
        if ($request->filled('from_request_id')) {
            $studentRequest = ThesisDocument::find($request->from_request_id);
            if ($studentRequest && $studentRequest->document_type === 'panel_assignment') {
                // Add a note to the student request that panel has been created
                $studentRequest->update([
                    'review_comments' => ($studentRequest->review_comments ?? '') . 
                        "\n\n[PANEL CREATED] Official panel assignment created on " . now()->format('F j, Y g:i A') . 
                        " by " . Auth::user()->name . ". Panel ID: " . $assignment->id
                ]);
            }
        }

        // Send notifications
        $assignment->sendNotifications();

        return redirect()->route('admin.panel')
            ->with('success', 'Panel assignment created successfully! Notifications sent to student and panel members.');
    }

    /**
     * Show the details of a specific panel assignment
     */
    public function show(PanelAssignment $assignment)
    {
        $this->ensureUserIsAdmin();

        $assignment->load(['student', 'thesisDocument', 'panelChair', 'secretary', 'creator', 'updater']);

        return view('admin.panel.show', compact('assignment'));
    }

    /**
     * Show the form for editing a panel assignment
     */
    public function edit(PanelAssignment $assignment)
    {
        $this->ensureUserIsAdmin();

        $facultyMembers = User::where('role', 'faculty')->orderBy('name')->get();
        $assignment->load(['student', 'thesisDocument']);

        return view('admin.panel.edit', compact('assignment', 'facultyMembers'));
    }

    /**
     * Update the specified panel assignment
     */
    public function update(Request $request, PanelAssignment $assignment)
    {
        $this->ensureUserIsAdmin();

        $request->validate([
            'panel_members' => 'required|array|min:3|max:5',
            'panel_members.*' => 'exists:users,id',
            'panel_chair_id' => 'required|exists:users,id',
            'secretary_id' => 'nullable|exists:users,id',
            'defense_date' => 'required|date',
            'defense_venue' => 'required|string|max:255',
            'defense_instructions' => 'nullable|string',
            'defense_type' => 'required|in:proposal_defense,final_defense,redefense,oral_defense,thesis_defense',
            'status' => ['required', Rule::in(['scheduled', 'postponed', 'cancelled'])], // Removed 'completed' - faculty handles completion
        ]);

        // Validate that panel chair is in panel members
        if (!in_array($request->panel_chair_id, $request->panel_members)) {
            return redirect()->back()
                ->withErrors(['panel_chair_id' => 'Panel chair must be one of the panel members.'])
                ->withInput();
        }

        // Admin only handles scheduling, not grading/completion
        $assignment->update([
            'panel_members' => $request->panel_members,
            'panel_chair_id' => $request->panel_chair_id,
            'secretary_id' => $request->secretary_id,
            'defense_date' => $request->defense_date,
            'defense_venue' => $request->defense_venue,
            'defense_instructions' => $request->defense_instructions,
            'defense_type' => $request->defense_type,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);

        // Send update notifications if significant changes were made
        $this->sendUpdateNotifications($assignment, $request);

        return redirect()->route('admin.panel')
            ->with('success', 'Panel assignment updated successfully!');
    }

    /**
     * Remove the specified panel assignment
     */
    public function destroy(PanelAssignment $assignment)
    {
        $this->ensureUserIsAdmin();

        $studentName = $assignment->student->name;
        $assignment->delete();

        return redirect()->route('admin.panel')
            ->with('success', "Panel assignment for {$studentName} has been deleted successfully!");
    }

    /**
     * Resend notifications for a panel assignment
     */
    public function resendNotifications(PanelAssignment $assignment)
    {
        $this->ensureUserIsAdmin();

        $assignment->sendNotifications();

        return redirect()->back()
            ->with('success', 'Notifications sent successfully to student and panel members!');
    }

    /**
     * Get available faculty members for AJAX requests
     */
    public function getAvailableFaculty(Request $request)
    {
        $this->ensureUserIsAdmin();

        $date = $request->get('date');
        $excludeIds = $request->get('exclude', []);

        $query = User::where('role', 'faculty');

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        // In the future, we could check faculty availability on specific dates
        $faculty = $query->orderBy('name')->get(['id', 'name', 'email']);

        return response()->json($faculty);
    }

    /**
     * Get defense schedule for calendar view
     */
    public function getSchedule(Request $request)
    {
        $this->ensureUserIsAdmin();

        $start = $request->get('start');
        $end = $request->get('end');

        $assignments = PanelAssignment::with(['student', 'panelChair'])
            ->whereBetween('defense_date', [$start, $end])
            ->get()
            ->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->student->name . ' - Thesis Defense',
                    'start' => $assignment->defense_date->toISOString(),
                    'backgroundColor' => $this->getEventColor($assignment->status),
                    'borderColor' => $this->getEventColor($assignment->status),
                    'textColor' => '#ffffff',
                    'url' => route('admin.panel.show', $assignment),
                ];
            });

        return response()->json($assignments);
    }

    /**
     * Get thesis documents for a specific student
     */
    public function getStudentThesis($studentId)
    {
        $this->ensureUserIsAdmin();

        // First try to get approved final manuscripts
        $thesisDocuments = ThesisDocument::where('user_id', $studentId)
            ->where('status', 'approved')
            ->where('document_type', 'final_manuscript')
            ->select(['id', 'title', 'document_type', 'status'])
            ->get();
            
        // If no approved final manuscripts, get any approved thesis documents
        if ($thesisDocuments->isEmpty()) {
            $thesisDocuments = ThesisDocument::where('user_id', $studentId)
                ->where('status', 'approved')
                ->select(['id', 'title', 'document_type', 'status'])
                ->get();
        }
        
        // If still empty, get all thesis documents for debugging
        if ($thesisDocuments->isEmpty()) {
            $thesisDocuments = ThesisDocument::where('user_id', $studentId)
                ->select(['id', 'title', 'document_type', 'status'])
                ->get();
        }

        return response()->json($thesisDocuments);
    }

    /**
     * Get thesis document details
     */
    public function getThesisDetails($thesisId)
    {
        $this->ensureUserIsAdmin();

        $thesis = ThesisDocument::findOrFail($thesisId);
        
        return response()->json([
            'title' => $thesis->title,
            'description' => $thesis->description ?? '',
            'document_type' => $thesis->document_type,
        ]);
    }

    /**
     * Send update notifications for panel assignment changes
     */
    private function sendUpdateNotifications(PanelAssignment $assignment, Request $request): void
    {
        // Check if significant changes were made that require notifications
        $significantChanges = [
            'defense_date' => $request->defense_date,
            'defense_venue' => $request->defense_venue,
            'status' => $request->status,
            'panel_members' => $request->panel_members,
        ];

        $hasSignificantChanges = false;
        foreach ($significantChanges as $field => $newValue) {
            if ($assignment->getOriginal($field) != $newValue) {
                $hasSignificantChanges = true;
                break;
            }
        }

        if ($hasSignificantChanges) {
            // Send notification to student
            $studentNotification = [
                'title' => 'Defense Assignment Updated',
                'message' => "Your thesis defense assignment has been updated. Please check the new details.",
                'data' => [
                    'panel_assignment_id' => $assignment->id,
                    'defense_date' => $assignment->defense_date,
                    'venue' => $assignment->defense_venue,
                    'status' => $assignment->status,
                    'url' => route('student.thesis.index'),
                ]
            ];

            \App\Models\Notification::createForUser(
                $assignment->student_id,
                'defense_updated',
                $studentNotification['title'],
                $studentNotification['message'],
                $studentNotification['data'],
                get_class($assignment),
                $assignment->id,
                'high'
            );

            // Send notification to all panel members (including chair and secretary)
            $allPanelMembers = array_merge(
                $assignment->panel_member_ids ?? [],
                [$assignment->panel_chair_id, $assignment->secretary_id]
            );
            $allPanelMembers = array_filter(array_unique($allPanelMembers));

            if (!empty($allPanelMembers)) {
                $defenseDateInfo = $assignment->defense_date ? " Defense scheduled for {$assignment->defense_date->format('F j, Y \a\t g:i A')} at {$assignment->defense_venue}." : "";
                
                $panelNotification = [
                    'title' => 'Panel Assignment Updated',
                    'message' => "The panel assignment for {$assignment->student->name}'s {$assignment->defense_type_label} has been updated.{$defenseDateInfo}",
                    'data' => [
                        'panel_assignment_id' => $assignment->id,
                        'student_name' => $assignment->student->name,
                        'thesis_title' => $assignment->thesis_title,
                        'defense_date' => $assignment->defense_date,
                        'venue' => $assignment->defense_venue,
                        'defense_type' => $assignment->defense_type,
                        'defense_type_label' => $assignment->defense_type_label,
                        'status' => $assignment->status,
                        'url' => route('faculty.panel-assignments.show', $assignment),
                    ]
                ];

                \App\Models\Notification::createForUsers(
                    $allPanelMembers,
                    'panel_assignment_updated',
                    $panelNotification['title'],
                    $panelNotification['message'],
                    $panelNotification['data'],
                    get_class($assignment),
                    $assignment->id,
                    'high'
                );
            }
        }
    }

    /**
     * Get event color based on status
     */
    private function getEventColor(string $status): string
    {
        return match($status) {
            'scheduled' => '#3b82f6',  // blue
            'completed' => '#10b981',  // green
            'postponed' => '#f59e0b',  // yellow
            'cancelled' => '#ef4444',  // red
            default => '#6b7280'       // gray
        };
    }
}
