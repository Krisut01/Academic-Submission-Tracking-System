<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\AcademicFormController;
use App\Http\Controllers\Student\ThesisDocumentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema; // Added this import for Schema facade

Route::get('/', function () {
    return view('welcome');
});

// Test AJAX page
Route::get('/test-ajax', function () {
    return view('test-ajax');
});

// Test individual approvals page
Route::get('/test-individual-approvals', function () {
    return view('test-individual-approvals');
});

// Test faculty approval fix page
Route::get('/test-faculty-approval-fix', function () {
    return view('test-faculty-approval-fix');
});

// Test notification fix page
Route::get('/test-notification-fix', function () {
    return view('test-notification-fix');
});

// Test complete notification fix page
Route::get('/test-notification-complete-fix', function () {
    return view('test-notification-complete-fix');
});

// Test route for approval status
Route::get('/test-approval', function () {
    $document = App\Models\ThesisDocument::first();
    if (!$document) {
        return 'No documents found';
    }
    
    $approvalStatus = $document->getApprovalStatus();
    return view('components.approval-status', [
        'approvalStatus' => $approvalStatus,
        'title' => 'Test Approval Status'
    ]);
});

// Test route for faculty approval
Route::post('/test-faculty-approval', function (Illuminate\Http\Request $request) {
    try {
        $document = App\Models\ThesisDocument::first();
        if (!$document) {
            return response()->json(['success' => false, 'message' => 'No documents found'], 404);
        }
        
        // Simulate approval
        $document->update([
            'status' => 'approved',
            'reviewed_by' => 1, // Test faculty ID
            'reviewed_at' => now(),
            'review_comments' => 'Test approval'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Test approval successful',
            'status' => 'approved',
            'document_id' => $document->id,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Test approval failed: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    $role = $user->role;
    
    // Redirect faculty users to their specific dashboard
    if ($role === 'faculty') {
        return redirect()->route('faculty.dashboard');
    }
    
    // Initialize dashboard data
    $dashboardData = [];
    
    try {
        if ($role === 'student') {
            // Check if tables exist before querying
            $hasAcademicForms = Schema::hasTable('academic_forms');
            $hasThesisDocuments = Schema::hasTable('thesis_documents');
            $hasNotifications = Schema::hasTable('notifications');
            $hasActivityLogs = Schema::hasTable('activity_logs');
            
            // Get real student data from database with safety checks
            $dashboardData = [
                'total_forms' => $hasAcademicForms ? $user->academicForms()->count() : 0,
                'total_thesis_documents' => $hasThesisDocuments ? $user->thesisDocuments()->count() : 0,
                'pending_forms' => $hasAcademicForms ? $user->academicForms()->where('status', 'pending')->count() : 0,
                'approved_forms' => $hasAcademicForms ? $user->academicForms()->where('status', 'approved')->count() : 0,
                'pending_thesis' => $hasThesisDocuments ? $user->thesisDocuments()->where('status', 'pending')->count() : 0,
                'approved_thesis' => $hasThesisDocuments ? $user->thesisDocuments()->where('status', 'approved')->count() : 0,
                'unread_notifications' => $hasNotifications ? $user->unreadNotifications()->count() : 0,
                'recent_activities' => $hasActivityLogs ? $user->activityLogs()->with('user')->orderBy('created_at', 'desc')->take(5)->get() : collect(),
                'latest_form' => $hasAcademicForms ? $user->academicForms()->latest()->first() : null,
                'latest_thesis' => $hasThesisDocuments ? $user->thesisDocuments()->latest()->first() : null,
                'forms_this_week' => $hasAcademicForms ? $user->academicForms()->where('created_at', '>=', now()->subWeek())->count() : 0,
                'thesis_this_week' => $hasThesisDocuments ? $user->thesisDocuments()->where('created_at', '>=', now()->subWeek())->count() : 0,
                'current_thesis_progress' => $hasThesisDocuments ? $user->thesisDocuments()
                    ->where('status', 'under_review')
                    ->orWhere('status', 'approved')
                    ->orderBy('created_at', 'desc')
                    ->first() : null
            ];
        } elseif ($role === 'admin') {
            // Get real admin data from database with safety checks
            $hasAcademicForms = Schema::hasTable('academic_forms');
            $hasThesisDocuments = Schema::hasTable('thesis_documents');
            $hasUsers = Schema::hasTable('users');
            $hasPanelAssignments = Schema::hasTable('panel_assignments');
            $hasActivityLogs = Schema::hasTable('activity_logs');
            $hasNotifications = Schema::hasTable('notifications');
            
            $totalSubmissions = 0;
            if ($hasAcademicForms) $totalSubmissions += \App\Models\AcademicForm::count();
            if ($hasThesisDocuments) $totalSubmissions += \App\Models\ThesisDocument::count();
            
            $submissionsThisWeek = 0;
            if ($hasAcademicForms) $submissionsThisWeek += \App\Models\AcademicForm::where('created_at', '>=', now()->subWeek())->count();
            if ($hasThesisDocuments) $submissionsThisWeek += \App\Models\ThesisDocument::where('created_at', '>=', now()->subWeek())->count();
            
            $dashboardData = [
                'total_submissions' => $totalSubmissions,
                'active_users' => $hasUsers ? \App\Models\User::count() : 0,
                'active_students' => $hasUsers ? \App\Models\User::where('role', 'student')->count() : 0,
                'active_faculty' => $hasUsers ? \App\Models\User::where('role', 'faculty')->count() : 0,
                'scheduled_defenses' => $hasPanelAssignments ? \App\Models\PanelAssignment::where('status', 'scheduled')->count() : 0,
                'overdue_reviews' => $hasThesisDocuments ? \App\Models\ThesisDocument::where('status', 'pending')
                    ->where('created_at', '<=', now()->subDays(5))->count() : 0,
                'recent_activities' => $hasActivityLogs ? \App\Models\ActivityLog::with('user')->orderBy('created_at', 'desc')->take(10)->get() : collect(),
                'submissions_this_week' => $submissionsThisWeek,
                'unread_notifications' => $hasNotifications ? \App\Models\Notification::where('user_id', $user->id)
                    ->whereNull('read_at')->count() : 0,
            ];
        }
    } catch (\Exception $e) {
        // If any error occurs, provide safe default values
        $dashboardData = [
            'total_forms' => 0,
            'total_thesis_documents' => 0,
            'pending_forms' => 0,
            'approved_forms' => 0,
            'pending_thesis' => 0,
            'approved_thesis' => 0,
            'unread_notifications' => 0,
            'recent_activities' => collect(),
            'latest_form' => null,
            'latest_thesis' => null,
            'forms_this_week' => 0,
            'thesis_this_week' => 0,
            'current_thesis_progress' => null,
            'total_submissions' => 0,
            'active_users' => 1, // At least the current user
            'active_students' => 0,
            'active_faculty' => 0,
            'scheduled_defenses' => 0,
            'overdue_reviews' => 0,
            'submissions_this_week' => 0,
        ];
    }
    
    return view('dashboard', compact('dashboardData'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Role-based dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/stats', [App\Http\Controllers\Admin\DashboardController::class, 'getStats'])->name('admin.dashboard.stats');
    Route::get('/admin/dashboard/activity', [App\Http\Controllers\Admin\DashboardController::class, 'getRecentActivity'])->name('admin.dashboard.activity');

    Route::get('/faculty/dashboard', [App\Http\Controllers\Faculty\DashboardController::class, 'index'])->name('faculty.dashboard');

    Route::get('/student/dashboard', function () {
        if (Auth::user()->role !== 'student') {
            abort(403, 'Unauthorized access.');
        }
        
        $user = Auth::user();
        $dashboardData = [];
        
        try {
            // Check if tables exist before querying
            $hasAcademicForms = Schema::hasTable('academic_forms');
            $hasThesisDocuments = Schema::hasTable('thesis_documents');
            $hasNotifications = Schema::hasTable('notifications');
            $hasActivityLogs = Schema::hasTable('activity_logs');
            $hasPanelAssignments = Schema::hasTable('panel_assignments');
            
            // Get defense schedule data for the student - more robust checking
            $upcomingDefense = null;
            $defenseNotification = null;
            if ($hasPanelAssignments) {
                // Check for any scheduled defense (not just upcoming ones)
                $upcomingDefense = \App\Models\PanelAssignment::where('student_id', $user->id)
                    ->where('status', 'scheduled')
                    ->where('defense_date', '>', now())
                    ->orderBy('defense_date', 'asc')
                    ->first();
                
                // Also check for recently scheduled defenses (within last 30 days) that might be relevant
                $recentDefense = \App\Models\PanelAssignment::where('student_id', $user->id)
                    ->where('status', 'scheduled')
                    ->where('defense_date', '>=', now()->subDays(30))
                    ->where('defense_date', '<=', now()->addDays(7))
                    ->orderBy('defense_date', 'asc')
                    ->first();
                
                // Use the most relevant defense (upcoming or recent)
                $relevantDefense = $upcomingDefense ?? $recentDefense;
                
                if ($relevantDefense) {
                    $defenseNotification = [
                        'title' => 'Defense Scheduled',
                        'message' => "Your {$relevantDefense->defense_type_label} is scheduled for {$relevantDefense->defense_date->format('F j, Y \a\t g:i A')}",
                        'defense_date' => $relevantDefense->defense_date,
                        'venue' => $relevantDefense->defense_venue,
                        'defense_type' => $relevantDefense->defense_type_label,
                        'panel_chair' => $relevantDefense->panelChair?->name,
                        'secretary' => $relevantDefense->secretary?->name,
                        'instructions' => $relevantDefense->defense_instructions,
                        'assignment_id' => $relevantDefense->id,
                        'is_upcoming' => $relevantDefense->defense_date > now(),
                        'days_until' => $relevantDefense->defense_date > now() ? $relevantDefense->defense_date->diffInDays(now()) : 0
                    ];
                }
            }
            
            // Get comprehensive research progress data from database
            $thesisDocuments = $hasThesisDocuments ? $user->thesisDocuments()->with(['reviewer', 'adviser', 'defenseConfirmedBy'])->get() : collect();
            $academicForms = $hasAcademicForms ? $user->academicForms()->get() : collect();
            
            // Get panel assignments for this student
            $panelAssignments = \App\Models\PanelAssignment::where('student_id', $user->id)->get();
            
            // Calculate research progress milestones based on actual database data
            $milestones = [
                'proposal_submitted' => $thesisDocuments->where('document_type', 'proposal')->isNotEmpty(),
                'proposal_approved' => $thesisDocuments->where('document_type', 'proposal')->where('status', 'approved')->isNotEmpty(),
                'approval_sheet_submitted' => $thesisDocuments->where('document_type', 'approval_sheet')->isNotEmpty(),
                'approval_sheet_approved' => $thesisDocuments->where('document_type', 'approval_sheet')->where('status', 'approved')->isNotEmpty(),
                'panel_assigned' => $thesisDocuments->where('document_type', 'panel_assignment')->isNotEmpty(),
                'defense_scheduled' => $panelAssignments->where('status', 'scheduled')->isNotEmpty() || $panelAssignments->where('status', 'completed')->isNotEmpty(),
                'defense_completed' => $panelAssignments->where('status', 'completed')->isNotEmpty(),
                'final_manuscript_submitted' => $thesisDocuments->where('document_type', 'final_manuscript')->isNotEmpty(),
                'final_manuscript_approved' => $thesisDocuments->where('document_type', 'final_manuscript')->where('status', 'approved')->isNotEmpty(),
            ];
            
            // Calculate progress percentage
            $completedMilestones = collect($milestones)->filter()->count();
            $totalMilestones = count($milestones);
            $progressPercentage = $totalMilestones > 0 ? round(($completedMilestones / $totalMilestones) * 100) : 0;
            
            // Get current phase based on database data
            $currentPhase = 'Not Started';
            if ($milestones['final_manuscript_approved']) {
                $currentPhase = 'Completed';
            } elseif ($milestones['final_manuscript_submitted']) {
                $currentPhase = 'Final Review';
            } elseif ($milestones['defense_completed']) {
                $currentPhase = 'Final Manuscript';
            } elseif ($milestones['defense_scheduled']) {
                $currentPhase = 'Defense Scheduled';
            } elseif ($milestones['panel_assigned']) {
                $currentPhase = 'Panel Assignment';
            } elseif ($milestones['approval_sheet_approved']) {
                $currentPhase = 'Panel Assignment Request';
            } elseif ($milestones['approval_sheet_submitted']) {
                $currentPhase = 'Approval Sheet Review';
            } elseif ($milestones['proposal_approved']) {
                $currentPhase = 'Approval Sheet';
            } elseif ($milestones['proposal_submitted']) {
                $currentPhase = 'Proposal Review';
            }
            
            // Get pending actions based on actual database state
            $pendingActions = [];
            if ($milestones['proposal_submitted'] && !$milestones['proposal_approved']) {
                $pendingActions[] = [
                    'type' => 'proposal_review',
                    'title' => 'Proposal Under Review',
                    'description' => 'Your proposal is being reviewed by faculty',
                    'status' => 'pending',
                    'priority' => 'high'
                ];
            }
            if ($milestones['approval_sheet_submitted'] && !$milestones['approval_sheet_approved']) {
                $pendingActions[] = [
                    'type' => 'approval_sheet_review',
                    'title' => 'Approval Sheet Under Review',
                    'description' => 'Your approval sheet is being reviewed',
                    'status' => 'pending',
                    'priority' => 'high'
                ];
            }
            if ($milestones['defense_scheduled']) {
                $pendingActions[] = [
                    'type' => 'defense_preparation',
                    'title' => 'Defense Preparation',
                    'description' => 'Prepare for your upcoming defense',
                    'status' => 'upcoming',
                    'priority' => 'urgent'
                ];
            }
            
            // Get latest activity from database
            $latestActivity = collect();
            if ($hasActivityLogs) {
                $latestActivity = $user->activityLogs()
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();
            }
            
            // Get real student data from database with safety checks
            $dashboardData = [
                'total_forms' => $hasAcademicForms ? $user->academicForms()->count() : 0,
                'total_thesis_documents' => $hasThesisDocuments ? $user->thesisDocuments()->count() : 0,
                'pending_forms' => $hasAcademicForms ? $user->academicForms()->where('status', 'pending')->count() : 0,
                'approved_forms' => $hasAcademicForms ? $user->academicForms()->where('status', 'approved')->count() : 0,
                'pending_thesis' => $hasThesisDocuments ? $user->thesisDocuments()->where('status', 'pending')->count() : 0,
                'approved_thesis' => $hasThesisDocuments ? $user->thesisDocuments()->where('status', 'approved')->count() : 0,
                'unread_notifications' => $hasNotifications ? $user->unreadNotifications()->count() : 0,
                'recent_activities' => $latestActivity,
                'latest_form' => $hasAcademicForms ? $user->academicForms()->latest()->first() : null,
                'latest_thesis' => $hasThesisDocuments ? $user->thesisDocuments()->latest()->first() : null,
                'forms_this_week' => $hasAcademicForms ? $user->academicForms()->where('created_at', '>=', now()->subWeek())->count() : 0,
                'thesis_this_week' => $hasThesisDocuments ? $user->thesisDocuments()->where('created_at', '>=', now()->subWeek())->count() : 0,
                'current_thesis_progress' => $hasThesisDocuments ? $user->thesisDocuments()
                    ->whereIn('status', ['under_review', 'approved', 'pending'])
                    ->orderBy('created_at', 'desc')
                    ->first() : null,
                'upcoming_defense' => $upcomingDefense,
                'defense_notification' => $defenseNotification,
                // Enhanced research progress data from database
                'research_progress' => [
                    'milestones' => $milestones,
                    'progress_percentage' => $progressPercentage,
                    'current_phase' => $currentPhase,
                    'completed_milestones' => $completedMilestones,
                    'total_milestones' => $totalMilestones,
                    'pending_actions' => $pendingActions,
                    'all_documents' => $thesisDocuments,
                    'all_forms' => $academicForms,
                ]
            ];
        } catch (\Exception $e) {
            // If any error occurs, provide safe default values
            $dashboardData = [
                'total_forms' => 0,
                'total_thesis_documents' => 0,
                'pending_forms' => 0,
                'approved_forms' => 0,
                'pending_thesis' => 0,
                'approved_thesis' => 0,
                'unread_notifications' => 0,
                'recent_activities' => collect(),
                'latest_form' => null,
                'latest_thesis' => null,
                'forms_this_week' => 0,
                'thesis_this_week' => 0,
                'current_thesis_progress' => null,
                'upcoming_defense' => null,
                'defense_notification' => null,
                'research_progress' => [
                    'milestones' => [],
                    'progress_percentage' => 0,
                    'current_phase' => 'Not Started',
                    'completed_milestones' => 0,
                    'total_milestones' => 0,
                    'pending_actions' => [],
                    'all_documents' => collect(),
                    'all_forms' => collect(),
                ]
            ];
        }
        
        return view('dashboard', compact('dashboardData'));
    })->name('student.dashboard');
});

// Student Academic Forms Routes
Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    Route::get('/forms', [AcademicFormController::class, 'index'])->name('forms.index');
    Route::get('/forms/create', [AcademicFormController::class, 'create'])->name('forms.create');
    Route::post('/forms', [AcademicFormController::class, 'store'])->name('forms.store');
    Route::get('/forms/history', [AcademicFormController::class, 'history'])->name('forms.history');
    Route::get('/forms/{form}', [AcademicFormController::class, 'show'])->name('forms.show');
    Route::get('/forms/{form}/download/{fileIndex}', [AcademicFormController::class, 'downloadFile'])->name('forms.download');
    
    // Thesis Document Routes
    Route::get('/thesis', [ThesisDocumentController::class, 'index'])->name('thesis.index');
    Route::get('/thesis/create', [ThesisDocumentController::class, 'create'])->name('thesis.create');
    Route::post('/thesis', [ThesisDocumentController::class, 'store'])->name('thesis.store');
    Route::get('/thesis/history', [ThesisDocumentController::class, 'history'])->name('thesis.history');
    Route::get('/thesis/defense', [ThesisDocumentController::class, 'defense'])->name('thesis.defense');
    Route::get('/thesis/panel-assignments', [ThesisDocumentController::class, 'panelAssignments'])->name('thesis.panel-assignments');
    Route::get('/thesis/panel-assignments/{panelAssignment}', [ThesisDocumentController::class, 'showPanelAssignment'])->name('thesis.panel-assignment.show');
    Route::get('/thesis/{document}', [ThesisDocumentController::class, 'show'])->name('thesis.show');
    Route::get('/thesis/{document}/edit', [ThesisDocumentController::class, 'edit'])->name('thesis.edit');
    Route::put('/thesis/{document}', [ThesisDocumentController::class, 'update'])->name('thesis.update');
    Route::delete('/thesis/{document}/files/{fileIndex}', [ThesisDocumentController::class, 'removeFile'])->name('thesis.remove-file');
    Route::get('/thesis/{document}/download/{fileIndex}', [ThesisDocumentController::class, 'downloadFile'])->name('thesis.download');
    Route::post('/thesis/panel-assignment/{panelAssignment}/mark-completed', [ThesisDocumentController::class, 'markDefenseCompleted'])->name('thesis.mark-defense-completed');
    Route::get('/thesis/{document}/approval-status', [ThesisDocumentController::class, 'getApprovalStatus'])->name('thesis.approval-status');
    
    // Get individual approval status for a document
    Route::get('/thesis/{document}/individual-approval-status', [ThesisDocumentController::class, 'getIndividualApprovalStatus'])->name('thesis.individual-approval-status');
});

// Faculty Thesis Review Routes
Route::middleware(['auth', 'verified'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/thesis/reviews', [App\Http\Controllers\Faculty\ThesisReviewController::class, 'reviews'])->name('thesis.reviews');
    Route::get('/thesis/progress', [App\Http\Controllers\Faculty\ThesisReviewController::class, 'progress'])->name('thesis.progress');
    Route::get('/thesis/reviews/{document}', [App\Http\Controllers\Faculty\ThesisReviewController::class, 'show'])->name('thesis.show');
    Route::put('/thesis/reviews/{document}', [App\Http\Controllers\Faculty\ThesisReviewController::class, 'updateReview'])->name('thesis.update');
    Route::get('/thesis/{document}/download/{fileIndex}', [App\Http\Controllers\Faculty\ThesisReviewController::class, 'downloadFile'])->name('thesis.download');
    
    // Panel Assignment Review Routes
    Route::get('/panel-assignments', [App\Http\Controllers\Faculty\PanelAssignmentReviewController::class, 'index'])->name('panel-assignments.index');
    Route::get('/panel-assignments/{panelAssignment}', [App\Http\Controllers\Faculty\PanelAssignmentReviewController::class, 'show'])->name('panel-assignments.show');
    Route::post('/panel-assignments/{panelAssignment}/review', [App\Http\Controllers\Faculty\PanelAssignmentReviewController::class, 'submitReview'])->name('panel-assignments.review');
});

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // User Management
    Route::get('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/password', [App\Http\Controllers\Admin\UserManagementController::class, 'resetPassword'])->name('users.reset-password');
    Route::put('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/bulk-action', [App\Http\Controllers\Admin\UserManagementController::class, 'bulkAction'])->name('users.bulk-action');
    Route::get('/users/export', [App\Http\Controllers\Admin\UserManagementController::class, 'export'])->name('users.export');

    // Records & RMT Table
    Route::get('/records', [App\Http\Controllers\Admin\RecordsController::class, 'index'])->name('records');
    Route::get('/records/forms/{form}', [App\Http\Controllers\Admin\RecordsController::class, 'showForm'])->name('records.show-form');
    Route::get('/records/documents/{document}', [App\Http\Controllers\Admin\RecordsController::class, 'showDocument'])->name('records.show-document');
    Route::get('/records/download/{type}/{id}/{fileIndex}', [App\Http\Controllers\Admin\RecordsController::class, 'downloadFile'])->name('records.download');
    Route::get('/records/export/forms', [App\Http\Controllers\Admin\RecordsController::class, 'exportForms'])->name('records.export-forms');
    Route::get('/records/export/documents', [App\Http\Controllers\Admin\RecordsController::class, 'exportDocuments'])->name('records.export-documents');
    Route::get('/records/feedback/{type}/{id}', [App\Http\Controllers\Admin\RecordsController::class, 'feedbackLogs'])->name('records.feedback');
    
    // Academic Form Approval Routes
    Route::post('/records/forms/{form}/approve', [App\Http\Controllers\Admin\RecordsController::class, 'approveForm'])->name('records.approve-form');
    Route::post('/records/forms/{form}/reject', [App\Http\Controllers\Admin\RecordsController::class, 'rejectForm'])->name('records.reject-form');
    Route::post('/records/forms/{form}/under-review', [App\Http\Controllers\Admin\RecordsController::class, 'markUnderReview'])->name('records.mark-under-review');
    
    // Defense Confirmation Routes
    Route::post('/records/documents/{document}/confirm-defense', [App\Http\Controllers\Admin\RecordsController::class, 'confirmDefense'])->name('records.confirm-defense');
    Route::post('/records/documents/{document}/mark-defended', [App\Http\Controllers\Admin\RecordsController::class, 'markProposalDefended'])->name('records.mark-defended');

    // Reports
    Route::get('/reports', [App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/submission-rates', [App\Http\Controllers\Admin\ReportsController::class, 'submissionRates'])->name('reports.submission-rates');
    Route::get('/reports/overdue-documents', [App\Http\Controllers\Admin\ReportsController::class, 'overdueDocuments'])->name('reports.overdue-documents');
    Route::get('/reports/approval-trends', [App\Http\Controllers\Admin\ReportsController::class, 'approvalTrends'])->name('reports.approval-trends');
    Route::get('/reports/user-activity', [App\Http\Controllers\Admin\ReportsController::class, 'userActivity'])->name('reports.user-activity');
    Route::get('/reports/faculty-performance', [App\Http\Controllers\Admin\ReportsController::class, 'facultyPerformance'])->name('reports.faculty-performance');
    Route::get('/reports/export/pdf', [App\Http\Controllers\Admin\ReportsController::class, 'exportPDF'])->name('reports.export-pdf');
    Route::get('/reports/export/csv', [App\Http\Controllers\Admin\ReportsController::class, 'exportCSV'])->name('reports.export-csv');

    // Panel Assignment & Defense Schedule
    Route::get('/panel', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'index'])->name('panel');
    Route::get('/panel/create', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'create'])->name('panel.create');
    Route::post('/panel', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'store'])->name('panel.store');
    Route::get('/panel/{assignment}', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'show'])->name('panel.show');
    Route::get('/panel/{assignment}/edit', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'edit'])->name('panel.edit');
    Route::put('/panel/{assignment}', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'update'])->name('panel.update');
    Route::delete('/panel/{assignment}', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'destroy'])->name('panel.destroy');
    Route::post('/panel/{assignment}/notify', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'resendNotifications'])->name('panel.notify');
    
    // Panel Assignment Request Management
    Route::post('/panel-requests/{document}/approve', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'approveRequest'])->name('panel-requests.approve');
    Route::post('/panel-requests/{document}/reject', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'rejectRequest'])->name('panel-requests.reject');
    
    Route::get('/panel/api/faculty', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'getAvailableFaculty'])->name('panel.api.faculty');
    Route::get('/panel/api/schedule', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'getSchedule'])->name('panel.api.schedule');
    Route::get('/panel/api/student-thesis/{studentId}', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'getStudentThesis'])->name('panel.api.student-thesis');
    Route::get('/panel/api/thesis-details/{thesisId}', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'getThesisDetails'])->name('panel.api.thesis-details');

    // Settings
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
});

// Settings Routes (Available to all authenticated users)
Route::middleware(['auth', 'verified'])->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [App\Http\Controllers\SettingsController::class, 'index'])->name('index');
    Route::get('/profile', [App\Http\Controllers\SettingsController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('profile.update');
    Route::get('/password', [App\Http\Controllers\SettingsController::class, 'password'])->name('password');
    Route::put('/password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('password.update');
    Route::get('/notifications', [App\Http\Controllers\SettingsController::class, 'notifications'])->name('notifications');
    Route::put('/notifications', [App\Http\Controllers\SettingsController::class, 'updateNotifications'])->name('notifications.update');
    Route::get('/activity', [App\Http\Controllers\SettingsController::class, 'activity'])->name('activity');
    Route::get('/privacy', [App\Http\Controllers\SettingsController::class, 'privacy'])->name('privacy');
    Route::get('/download-data', [App\Http\Controllers\SettingsController::class, 'downloadData'])->name('download-data');
    Route::post('/request-deletion', [App\Http\Controllers\SettingsController::class, 'requestDeletion'])->name('request-deletion');
    Route::post('/clear-notifications', [App\Http\Controllers\SettingsController::class, 'clearNotifications'])->name('clear-notifications');
    Route::get('/export-activity', [App\Http\Controllers\SettingsController::class, 'exportActivity'])->name('export-activity');
});

// Notification Routes (Available to all authenticated users)
Route::middleware(['auth', 'verified'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    Route::post('/{notification}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('unread-count');
    Route::get('/recent', [App\Http\Controllers\NotificationController::class, 'getRecent'])->name('recent');
    Route::delete('/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
});

// Redirect old profile routes to new settings system
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return redirect()->route('settings.index');
    })->name('profile.edit');
    Route::patch('/profile', function () {
        return redirect()->route('settings.profile');
    })->name('profile.update');
    Route::delete('/profile', function () {
        return redirect()->route('settings.privacy');
    })->name('profile.destroy');
});

require __DIR__.'/auth.php';
