<?php

namespace App\Traits;

use App\Models\ActivityLog;
use App\Models\AcademicForm;
use App\Models\ThesisDocument;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Cache;

trait InterconnectedSystem
{
    /**
     * Get real-time dashboard statistics for any role
     */
    public function getDashboardStats(string $userRole): array
    {
        $cacheKey = "dashboard_stats_{$userRole}_" . now()->format('Y-m-d-H');
        
        return Cache::remember($cacheKey, 300, function () use ($userRole) { // 5 min cache
            $baseStats = [
                'total_users' => User::count(),
                'active_students' => User::where('role', 'student')->count(),
                'active_faculty' => User::where('role', 'faculty')->count(),
                'total_forms' => AcademicForm::count(),
                'total_thesis' => ThesisDocument::count(),
                'pending_submissions' => AcademicForm::where('status', 'pending')->count() + 
                                       ThesisDocument::where('status', 'pending')->count(),
                'approved_submissions' => AcademicForm::where('status', 'approved')->count() + 
                                        ThesisDocument::where('status', 'approved')->count(),
            ];

            // Role-specific statistics
            switch ($userRole) {
                case 'student':
                    return array_merge($baseStats, [
                        'my_forms' => AcademicForm::where('user_id', auth()->id())->count(),
                        'my_thesis' => ThesisDocument::where('user_id', auth()->id())->count(),
                        'my_pending' => AcademicForm::where('user_id', auth()->id())
                            ->where('status', 'pending')->count() +
                            ThesisDocument::where('user_id', auth()->id())
                            ->where('status', 'pending')->count(),
                    ]);

                case 'faculty':
                    return array_merge($baseStats, [
                        'my_reviews' => ThesisDocument::where('reviewed_by', auth()->id())->count(),
                        'pending_reviews' => ThesisDocument::whereIn('status', ['pending', 'under_review'])->count(),
                        'completed_reviews' => ThesisDocument::where('reviewed_by', auth()->id())
                            ->where('status', 'approved')->count(),
                    ]);

                case 'admin':
                    return array_merge($baseStats, [
                        'overdue_reviews' => ThesisDocument::where('status', 'pending')
                            ->where('submission_date', '<=', now()->subDays(5))->count(),
                        'recent_activities' => ActivityLog::where('created_at', '>=', now()->subDay())->count(),
                        'system_health' => $this->calculateSystemHealth(),
                    ]);

                default:
                    return $baseStats;
            }
        });
    }

    /**
     * Get recent activities with real-time updates
     */
    public function getRecentActivities(int $limit = 10): array
    {
        return ActivityLog::with(['user'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'description' => $log->description,
                    'user_name' => $log->user?->name ?? 'System',
                    'event_type' => $log->event_type,
                    'time' => $log->formatted_time,
                    'created_at' => $log->created_at,
                ];
            })->toArray();
    }

    /**
     * Get cross-role notifications
     */
    public function getCrossRoleNotifications(): array
    {
        if (!auth()->check()) {
            return [];
        }

        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'priority' => $notification->priority,
                    'priority_color' => $notification->priority_color,
                    'is_read' => $notification->is_read,
                    'formatted_time' => $notification->formatted_time,
                    'url' => $notification->data['url'] ?? null,
                ];
            })->toArray();

        return [
            'notifications' => $notifications,
            'unread_count' => auth()->user()->unread_notification_count,
        ];
    }

    /**
     * Update system cache when data changes
     */
    public function invalidateSystemCaches(): void
    {
        $patterns = [
            'dashboard_stats_*',
            'user_stats_*',
            'form_stats_*',
            'thesis_stats_*',
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }
    }

    /**
     * Ensure data consistency across all views
     */
    public function syncDataAcrossRoles(string $eventType, array $data): void
    {
        // Invalidate relevant caches
        $this->invalidateSystemCaches();

        // Update real-time counters
        $this->updateRealTimeCounters($eventType, $data);

        // Log the synchronization
        ActivityLog::logActivity(
            'system_sync',
            'updated',
            new \stdClass(), // Placeholder model
            null,
            $data,
            ['sync_type' => 'cross_role_update', 'event_type' => $eventType]
        );
    }

    /**
     * Calculate system health percentage
     */
    private function calculateSystemHealth(): int
    {
        $factors = [
            'active_users_ratio' => min(100, (User::count() / max(1, User::withTrashed()->count())) * 100),
            'submission_success_rate' => $this->getSubmissionSuccessRate(),
            'review_completion_rate' => $this->getReviewCompletionRate(),
            'system_responsiveness' => 95, // This could be calculated based on response times
        ];

        return (int) round(array_sum($factors) / count($factors));
    }

    /**
     * Get submission success rate
     */
    private function getSubmissionSuccessRate(): float
    {
        $totalSubmissions = AcademicForm::count() + ThesisDocument::count();
        if ($totalSubmissions === 0) return 100;

        $approvedSubmissions = AcademicForm::where('status', 'approved')->count() + 
                              ThesisDocument::where('status', 'approved')->count();

        return ($approvedSubmissions / $totalSubmissions) * 100;
    }

    /**
     * Get review completion rate
     */
    private function getReviewCompletionRate(): float
    {
        $totalReviews = ThesisDocument::whereNotNull('reviewed_by')->count();
        if ($totalReviews === 0) return 100;

        $completedReviews = ThesisDocument::whereIn('status', ['approved', 'returned_for_revision'])->count();

        return ($completedReviews / max(1, $totalReviews)) * 100;
    }

    /**
     * Update real-time counters
     */
    private function updateRealTimeCounters(string $eventType, array $data): void
    {
        $cacheKeys = [
            'total_submissions' => AcademicForm::count() + ThesisDocument::count(),
            'pending_reviews' => ThesisDocument::whereIn('status', ['pending', 'under_review'])->count(),
            'recent_activity_count' => ActivityLog::where('created_at', '>=', now()->subHour())->count(),
        ];

        foreach ($cacheKeys as $key => $value) {
            Cache::put($key, $value, 300); // 5 minutes
        }
    }

    /**
     * Check if user has access to specific data
     */
    public function checkDataAccess(string $modelType, int $modelId, ?int $userId = null): bool
    {
        $userId = $userId ?? auth()->id();
        $user = User::find($userId);

        if (!$user) return false;

        // Admin has access to everything
        if ($user->role === 'admin') return true;

        // Check model-specific access
        switch ($modelType) {
            case 'AcademicForm':
                $form = AcademicForm::find($modelId);
                return $form && ($form->user_id === $userId || $user->role === 'faculty');

            case 'ThesisDocument':
                $document = ThesisDocument::find($modelId);
                return $document && ($document->user_id === $userId || $user->role === 'faculty');

            case 'User':
                return $user->role === 'admin' || $modelId === $userId;

            default:
                return false;
        }
    }
} 