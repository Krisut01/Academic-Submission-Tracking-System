<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\PanelAssignment;
use App\Models\ThesisDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
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
     * Display faculty dashboard
     */
    public function index()
    {
        $this->ensureUserIsFaculty();
        
        $user = Auth::user();
        $facultyId = $user->id;
        
        // Initialize with safe defaults
        $dashboardData = [
            'pending_reviews' => 0,
            'completed_reviews' => 0,
            'assigned_students' => 0,
            'urgent_reviews' => 0,
            'recent_submissions' => collect(),
            'panel_assignments' => collect(),
            'avg_review_time' => 0,
            'unread_notifications' => 0,
            'this_week_activity' => 0,
            'recent_activities' => collect(),
        ];
        
        try {
            // Get panel assignments where faculty is a member
            $panelAssignments = PanelAssignment::query()
                ->where(function ($q) use ($facultyId) {
                    $q->where('panel_chair_id', $facultyId)
                      ->orWhere('secretary_id', $facultyId)
                      ->orWhereJsonContains('panel_members', (string)$facultyId);
                })
                ->with(['student', 'thesisDocument'])
                ->orderBy('defense_date', 'desc')
                ->get();
            
            // Get student IDs from panel assignments
            $studentIds = $panelAssignments->pluck('student_id')->unique();
            $panelDocumentIds = $panelAssignments->pluck('thesis_document_id')->filter();
            
            // Get ALL documents where faculty has access (optimized single query)
            $allAssignedDocuments = ThesisDocument::query()
                ->whereIn('status', ['pending', 'under_review', 'approved'])
                ->where(function ($q) use ($facultyId, $studentIds, $panelDocumentIds) {
                    // Direct assignment (adviser OR reviewer)
                    $q->where(function ($directQuery) use ($facultyId) {
                        $directQuery->where('adviser_id', $facultyId)
                                   ->orWhere('reviewed_by', $facultyId);
                    })
                    // Panel assignment documents
                    ->orWhereIn('id', $panelDocumentIds)
                    // ALL documents for students where faculty is panel member
                    ->orWhere(function ($panelQuery) use ($studentIds) {
                        $panelQuery->whereIn('user_id', $studentIds);
                    });
                })
                ->with('user')
                ->get();
            
            $dashboardData = [
                'pending_reviews' => $allAssignedDocuments->where('status', 'pending')->count(),
                'completed_reviews' => ThesisDocument::where('reviewed_by', $facultyId)
                    ->whereIn('status', ['approved', 'returned_for_revision'])
                    ->count(),
                'assigned_students' => User::where('role', 'student')
                    ->whereHas('thesisDocuments', function($q) use ($facultyId) {
                        $q->where('adviser_id', $facultyId)
                          ->orWhere('reviewed_by', $facultyId);
                    })
                    ->count(),
                'urgent_reviews' => $allAssignedDocuments
                    ->where('status', 'pending')
                    ->where('created_at', '<=', now()->subDays(5))
                    ->count(),
                'recent_submissions' => $allAssignedDocuments->sortByDesc('created_at')->take(5)->values(),
                'panel_assignments' => $panelAssignments,
                'avg_review_time' => 2.5,
                'unread_notifications' => \App\Models\Notification::where('user_id', $facultyId)
                    ->whereNull('read_at')
                    ->count(),
                'this_week_activity' => $allAssignedDocuments
                    ->where('created_at', '>=', now()->subWeek())
                    ->count(),
                'recent_activities' => \App\Models\ActivityLog::where('user_id', $facultyId)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get(),
            ];
            
        } catch (\Exception $e) {
            Log::error('Faculty Dashboard Error: ' . $e->getMessage());
        }
        
        return view('faculty.dashboard', compact('dashboardData'));
    }
}
