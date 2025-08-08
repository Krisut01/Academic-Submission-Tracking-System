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

Route::get('/dashboard', function () {
    $user = Auth::user();
    $role = $user->role;
    
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
                'recent_activities' => $hasActivityLogs ? $user->activityLogs()->orderBy('created_at', 'desc')->take(5)->get() : collect(),
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
        } elseif ($role === 'faculty') {
            // Get real faculty data from database with safety checks
            $hasThesisDocuments = Schema::hasTable('thesis_documents');
            $hasUsers = Schema::hasTable('users');
            
            $dashboardData = [
                'pending_reviews' => $hasThesisDocuments ? \App\Models\ThesisDocument::where('status', 'pending')->count() : 0,
                'completed_reviews' => $hasThesisDocuments ? \App\Models\ThesisDocument::where('reviewed_by', $user->id)->count() : 0,
                'assigned_students' => $hasUsers && $hasThesisDocuments ? \App\Models\User::where('role', 'student')
                    ->whereHas('thesisDocuments', function($q) use ($user) {
                        $q->where('reviewed_by', $user->id);
                    })->count() : 0,
                'urgent_reviews' => $hasThesisDocuments ? \App\Models\ThesisDocument::where('status', 'pending')
                    ->where('created_at', '<=', now()->subDays(5))->count() : 0,
                'recent_submissions' => $hasThesisDocuments ? \App\Models\ThesisDocument::where('status', 'pending')
                    ->with('user')->orderBy('created_at', 'desc')->take(5)->get() : collect(),
                'avg_review_time' => 2.5, // This would need more complex calculation
                'unread_notifications' => Schema::hasTable('notifications') ? $user->unreadNotifications()->count() : 0,
            ];
        } elseif ($role === 'admin') {
            // Get real admin data from database with safety checks
            $hasAcademicForms = Schema::hasTable('academic_forms');
            $hasThesisDocuments = Schema::hasTable('thesis_documents');
            $hasUsers = Schema::hasTable('users');
            $hasPanelAssignments = Schema::hasTable('panel_assignments');
            $hasActivityLogs = Schema::hasTable('activity_logs');
            
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
                'recent_activities' => $hasActivityLogs ? \App\Models\ActivityLog::orderBy('created_at', 'desc')->take(10)->get() : collect(),
                'submissions_this_week' => $submissionsThisWeek,
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
            'pending_reviews' => 0,
            'completed_reviews' => 0,
            'assigned_students' => 0,
            'urgent_reviews' => 0,
            'recent_submissions' => collect(),
            'avg_review_time' => 0,
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
    Route::get('/admin/dashboard', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        return view('dashboard', ['userRole' => 'admin']);
    })->name('admin.dashboard');

    Route::get('/faculty/dashboard', function () {
        if (Auth::user()->role !== 'faculty') {
            abort(403, 'Unauthorized access.');
        }
        return view('dashboard', ['userRole' => 'faculty']);
    })->name('faculty.dashboard');

    Route::get('/student/dashboard', function () {
        if (Auth::user()->role !== 'student') {
            abort(403, 'Unauthorized access.');
        }
        return view('dashboard', ['userRole' => 'student']);
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
    Route::get('/thesis/{document}', [ThesisDocumentController::class, 'show'])->name('thesis.show');
    Route::get('/thesis/{document}/download/{fileIndex}', [ThesisDocumentController::class, 'downloadFile'])->name('thesis.download');
});

// Faculty Thesis Review Routes
Route::middleware(['auth', 'verified'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/thesis/reviews', [App\Http\Controllers\Faculty\ThesisReviewController::class, 'reviews'])->name('thesis.reviews');
    Route::get('/thesis/progress', [App\Http\Controllers\Faculty\ThesisReviewController::class, 'progress'])->name('thesis.progress');
    Route::get('/thesis/reviews/{document}', [App\Http\Controllers\Faculty\ThesisReviewController::class, 'show'])->name('thesis.show');
    Route::put('/thesis/reviews/{document}', [App\Http\Controllers\Faculty\ThesisReviewController::class, 'updateReview'])->name('thesis.update');
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
    Route::get('/panel/api/faculty', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'getAvailableFaculty'])->name('panel.api.faculty');
    Route::get('/panel/api/schedule', [App\Http\Controllers\Admin\PanelAssignmentController::class, 'getSchedule'])->name('panel.api.schedule');

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
