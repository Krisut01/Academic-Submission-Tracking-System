<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicForm;
use App\Models\ThesisDocument;
use App\Models\User;
use App\Models\PanelAssignment;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
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
     * Display the admin dashboard
     */
    public function index()
    {
        $this->ensureUserIsAdmin();
        
        $dashboardData = $this->getDashboardData();
        
        return view('dashboard', compact('dashboardData'));
    }

    /**
     * Get comprehensive dashboard data for admin
     */
    private function getDashboardData()
    {
        try {
            // Get current week and month for calculations
            $currentWeek = now()->startOfWeek();
            $currentMonth = now()->startOfMonth();
            
            // Total submissions (Academic Forms + Thesis Documents)
            $totalSubmissions = AcademicForm::count() + ThesisDocument::count();
            
            // Submissions this week
            $submissionsThisWeek = AcademicForm::where('created_at', '>=', $currentWeek)->count() + 
                                 ThesisDocument::where('created_at', '>=', $currentWeek)->count();
            
            // Active users (users who have logged in within the last 30 days)
            $activeUsers = User::where('updated_at', '>=', now()->subDays(30))->count();
            
            // Active students and faculty
            $activeStudents = User::where('role', 'student')
                ->where('updated_at', '>=', now()->subDays(30))
                ->count();
                
            $activeFaculty = User::where('role', 'faculty')
                ->where('updated_at', '>=', now()->subDays(30))
                ->count();
            
            // Defense schedule statistics
            $scheduledDefenses = PanelAssignment::where('status', 'scheduled')->count();
            $defensesThisWeek = PanelAssignment::where('status', 'scheduled')
                ->where('defense_date', '>=', $currentWeek)
                ->where('defense_date', '<=', now()->endOfWeek())
                ->count();
            
            // Overdue reviews (documents pending for more than 5 days)
            $overdueReviews = ThesisDocument::where('status', 'pending')
                ->where('created_at', '<=', now()->subDays(5))
                ->count();
            
            // System health calculation
            $totalPendingItems = AcademicForm::where('status', 'pending')->count() + 
                               ThesisDocument::where('status', 'pending')->count();
            $systemHealth = $totalSubmissions > 0 ? 
                round((($totalSubmissions - $overdueReviews) / $totalSubmissions) * 100) : 100;
            
            // Recent activities
            $recentActivities = ActivityLog::with(['user'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
            
            // Recent submissions
            $recentSubmissions = collect();
            $recentForms = AcademicForm::with(['user'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            $recentDocuments = ThesisDocument::with(['user'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            $recentSubmissions = $recentForms->concat($recentDocuments)
                ->sortByDesc('created_at')
                ->take(10);
            
            // Pending reviews count
            $pendingReviews = ThesisDocument::whereIn('status', ['pending', 'under_review'])->count();
            
            // Monthly statistics
            $monthlySubmissions = AcademicForm::where('created_at', '>=', $currentMonth)->count() + 
                                ThesisDocument::where('created_at', '>=', $currentMonth)->count();
            
            // Additional detailed statistics
            $pendingForms = AcademicForm::where('status', 'pending')->count();
            $approvedForms = AcademicForm::where('status', 'approved')->count();
            $pendingDocuments = ThesisDocument::where('status', 'pending')->count();
            $approvedDocuments = ThesisDocument::where('status', 'approved')->count();
            
            // Evaluation forms statistics
            $pendingEvaluationForms = AcademicForm::where('form_type', 'evaluation')
                ->where('status', 'pending')
                ->count();
            $underReviewEvaluationForms = AcademicForm::where('form_type', 'evaluation')
                ->where('status', 'under_review')
                ->count();
            $approvedEvaluationForms = AcademicForm::where('form_type', 'evaluation')
                ->where('status', 'approved')
                ->count();
            
            // Recent evaluation forms for admin review
            $recentEvaluationForms = AcademicForm::with(['user'])
                ->where('form_type', 'evaluation')
                ->whereIn('status', ['pending', 'under_review'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            // Defense statistics
            $completedDefenses = PanelAssignment::where('status', 'completed')->count();
            $upcomingDefenses = PanelAssignment::where('status', 'scheduled')
                ->where('defense_date', '>=', now())
                ->where('defense_date', '<=', now()->addDays(7))
                ->count();
            
            // User activity statistics
            $newUsersThisWeek = User::where('created_at', '>=', $currentWeek)->count();
            $activeUsersToday = User::where('updated_at', '>=', now()->startOfDay())->count();
            
            return [
                'total_submissions' => $totalSubmissions,
                'submissions_this_week' => $submissionsThisWeek,
                'active_users' => $activeUsers,
                'active_students' => $activeStudents,
                'active_faculty' => $activeFaculty,
                'scheduled_defenses' => $scheduledDefenses,
                'defenses_this_week' => $defensesThisWeek,
                'overdue_reviews' => $overdueReviews,
                'pending_reviews' => $pendingReviews,
                'system_health' => $systemHealth,
                'recent_activities' => $recentActivities,
                'recent_submissions' => $recentSubmissions,
                'monthly_submissions' => $monthlySubmissions,
                'total_pending_items' => $totalPendingItems,
                'pending_forms' => $pendingForms,
                'approved_forms' => $approvedForms,
                'pending_documents' => $pendingDocuments,
                'approved_documents' => $approvedDocuments,
                'completed_defenses' => $completedDefenses,
                'upcoming_defenses' => $upcomingDefenses,
                'new_users_this_week' => $newUsersThisWeek,
                'active_users_today' => $activeUsersToday,
                'pending_evaluation_forms' => $pendingEvaluationForms,
                'under_review_evaluation_forms' => $underReviewEvaluationForms,
                'approved_evaluation_forms' => $approvedEvaluationForms,
                'recent_evaluation_forms' => $recentEvaluationForms,
            ];
            
        } catch (\Exception $e) {
            // Return safe default values if any error occurs
            return [
                'total_submissions' => 0,
                'submissions_this_week' => 0,
                'active_users' => 1, // At least the current admin user
                'active_students' => 0,
                'active_faculty' => 0,
                'scheduled_defenses' => 0,
                'defenses_this_week' => 0,
                'overdue_reviews' => 0,
                'pending_reviews' => 0,
                'system_health' => 100,
                'recent_activities' => collect(),
                'recent_submissions' => collect(),
                'monthly_submissions' => 0,
                'total_pending_items' => 0,
                'pending_forms' => 0,
                'approved_forms' => 0,
                'pending_documents' => 0,
                'approved_documents' => 0,
                'completed_defenses' => 0,
                'upcoming_defenses' => 0,
                'new_users_this_week' => 0,
                'active_users_today' => 1,
                'pending_evaluation_forms' => 0,
                'under_review_evaluation_forms' => 0,
                'approved_evaluation_forms' => 0,
                'recent_evaluation_forms' => collect(),
            ];
        }
    }

    /**
     * Get dashboard statistics as JSON (for AJAX requests)
     */
    public function getStats()
    {
        $this->ensureUserIsAdmin();
        
        $stats = $this->getDashboardData();
        
        return response()->json($stats);
    }

    /**
     * Get real-time activity updates
     */
    public function getRecentActivity()
    {
        $this->ensureUserIsAdmin();
        
        $recentActivities = ActivityLog::with(['user'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
            
        return response()->json($recentActivities);
    }
}
