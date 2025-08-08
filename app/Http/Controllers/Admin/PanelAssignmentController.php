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

        $query = PanelAssignment::with(['student', 'thesisDocument', 'panelChair'])
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

        return view('admin.panel.index', compact('assignments', 'stats', 'studentsReadyForDefense'));
    }

    /**
     * Show the form for creating a new panel assignment
     */
    public function create(Request $request)
    {
        $this->ensureUserIsAdmin();

        $student = null;
        $thesisDocument = null;

        // If student_id and thesis_id are provided, pre-fill the form
        if ($request->filled('student_id') && $request->filled('thesis_id')) {
            $student = User::findOrFail($request->student_id);
            $thesisDocument = ThesisDocument::findOrFail($request->thesis_id);
        }

        // Get all faculty members for panel selection
        $facultyMembers = User::where('role', 'faculty')->orderBy('name')->get();

        // Get students with approved final manuscripts
        $eligibleStudents = User::where('role', 'student')
            ->whereHas('thesisDocuments', function ($q) {
                $q->where('status', 'approved')
                  ->where('document_type', 'final_manuscript');
            })
            ->orderBy('name')
            ->get();

        return view('admin.panel.create', compact('student', 'thesisDocument', 'facultyMembers', 'eligibleStudents'));
    }

    /**
     * Store a newly created panel assignment
     */
    public function store(Request $request)
    {
        $this->ensureUserIsAdmin();

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'thesis_document_id' => 'required|exists:thesis_documents,id',
            'thesis_title' => 'required|string|max:255',
            'thesis_description' => 'nullable|string',
            'panel_members' => 'required|array|min:3|max:5',
            'panel_members.*' => 'exists:users,id',
            'panel_chair_id' => 'required|exists:users,id',
            'defense_date' => 'required|date|after:now',
            'defense_venue' => 'required|string|max:255',
            'defense_instructions' => 'nullable|string',
        ]);

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
            'defense_date' => $request->defense_date,
            'defense_venue' => $request->defense_venue,
            'defense_instructions' => $request->defense_instructions,
            'status' => 'scheduled',
            'created_by' => Auth::id(),
        ]);

        // Send notifications
        $assignment->sendNotifications();

        return redirect()->route('admin.panel.index')
            ->with('success', 'Panel assignment created successfully! Notifications sent to student and panel members.');
    }

    /**
     * Show the details of a specific panel assignment
     */
    public function show(PanelAssignment $assignment)
    {
        $this->ensureUserIsAdmin();

        $assignment->load(['student', 'thesisDocument', 'panelChair', 'creator', 'updater']);

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
            'thesis_title' => 'required|string|max:255',
            'thesis_description' => 'nullable|string',
            'panel_members' => 'required|array|min:3|max:5',
            'panel_members.*' => 'exists:users,id',
            'panel_chair_id' => 'required|exists:users,id',
            'defense_date' => 'required|date',
            'defense_venue' => 'required|string|max:255',
            'defense_instructions' => 'nullable|string',
            'status' => ['required', Rule::in(['scheduled', 'completed', 'postponed', 'cancelled'])],
            'result' => ['nullable', Rule::in(['passed', 'failed', 'conditional', 'pending'])],
            'panel_feedback' => 'nullable|string',
            'final_grade' => 'nullable|numeric|min:0|max:100',
        ]);

        // Validate that panel chair is in panel members
        if (!in_array($request->panel_chair_id, $request->panel_members)) {
            return redirect()->back()
                ->withErrors(['panel_chair_id' => 'Panel chair must be one of the panel members.'])
                ->withInput();
        }

        $assignment->update([
            'thesis_title' => $request->thesis_title,
            'thesis_description' => $request->thesis_description,
            'panel_members' => $request->panel_members,
            'panel_chair_id' => $request->panel_chair_id,
            'defense_date' => $request->defense_date,
            'defense_venue' => $request->defense_venue,
            'defense_instructions' => $request->defense_instructions,
            'status' => $request->status,
            'result' => $request->result,
            'panel_feedback' => $request->panel_feedback,
            'final_grade' => $request->final_grade,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.panel.index')
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

        return redirect()->route('admin.panel.index')
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
