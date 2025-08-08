<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Display user settings dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's recent activity
        $recentActivities = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get notification statistics
        $notificationStats = [
            'total_notifications' => $user->notifications()->count(),
            'unread_notifications' => $user->unreadNotifications()->count(),
            'urgent_notifications' => $user->notifications()->where('priority', 'urgent')->count(),
        ];

        // Get login activity (you could track this in a separate table)
        $loginActivities = ActivityLog::where('user_id', $user->id)
            ->where('event_type', 'user_login')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('settings.index', compact('user', 'recentActivities', 'notificationStats', 'loginActivities'));
    }

    /**
     * Show profile edit form
     */
    public function profile()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
        ]);

        $oldData = $user->only(['name', 'email']);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Log the profile update
        ActivityLog::logActivity(
            'profile_updated',
            'updated',
            $user,
            $oldData,
            $user->only(['name', 'email']),
            ['ip_address' => request()->ip(), 'user_agent' => request()->userAgent()]
        );

        return redirect()->route('settings.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show password change form
     */
    public function password()
    {
        return view('settings.password');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Log the password change
        ActivityLog::logActivity(
            'password_changed',
            'updated',
            $user,
            null,
            null,
            ['ip_address' => request()->ip(), 'user_agent' => request()->userAgent()],
            'Password changed successfully'
        );

        return redirect()->route('settings.password')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Show notification preferences
     */
    public function notifications()
    {
        $user = Auth::user();
        
        // Get current notification preferences (stored in JSON column or separate table)
        $preferences = $user->notification_preferences ?? $this->getDefaultNotificationPreferences();
        
        return view('settings.notifications', compact('user', 'preferences'));
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'email_notifications' => 'boolean',
            'form_submissions' => 'boolean',
            'thesis_updates' => 'boolean',
            'defense_reminders' => 'boolean',
            'system_alerts' => 'boolean',
            'role_changes' => 'boolean',
        ]);

        $preferences = [
            'email_notifications' => $request->boolean('email_notifications'),
            'form_submissions' => $request->boolean('form_submissions'),
            'thesis_updates' => $request->boolean('thesis_updates'),
            'defense_reminders' => $request->boolean('defense_reminders'),
            'system_alerts' => $request->boolean('system_alerts'),
            'role_changes' => $request->boolean('role_changes'),
            'updated_at' => now(),
        ];

        // Store preferences (you might want to add a notification_preferences column to users table)
        $user->update(['notification_preferences' => $preferences]);

        // Log the preference change
        ActivityLog::logActivity(
            'notification_preferences_updated',
            'updated',
            $user,
            $user->notification_preferences,
            $preferences,
            ['ip_address' => request()->ip(), 'user_agent' => request()->userAgent()]
        );

        return redirect()->route('settings.notifications')
            ->with('success', 'Notification preferences updated successfully!');
    }

    /**
     * Show account activity and login history
     */
    public function activity()
    {
        $user = Auth::user();
        
        // Get detailed activity logs
        $activities = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get activity statistics
        $stats = [
            'total_activities' => ActivityLog::where('user_id', $user->id)->count(),
            'form_submissions' => ActivityLog::where('user_id', $user->id)->where('event_type', 'form_submitted')->count(),
            'thesis_updates' => ActivityLog::where('user_id', $user->id)->where('event_type', 'thesis_reviewed')->count(),
            'login_count' => ActivityLog::where('user_id', $user->id)->where('event_type', 'user_login')->count(),
        ];

        return view('settings.activity', compact('user', 'activities', 'stats'));
    }

    /**
     * Show privacy and security settings
     */
    public function privacy()
    {
        $user = Auth::user();
        return view('settings.privacy', compact('user'));
    }

    /**
     * Download user data (GDPR compliance)
     */
    public function downloadData()
    {
        $user = Auth::user();
        
        $userData = [
            'profile' => $user->only(['name', 'email', 'role', 'created_at', 'updated_at']),
            'academic_forms' => $user->academicForms->toArray(),
            'thesis_documents' => $user->thesisDocuments->toArray(),
            'notifications' => $user->notifications->toArray(),
            'activities' => $user->activityLogs->toArray(),
        ];

        $fileName = "user_data_{$user->id}_" . now()->format('Y-m-d_H-i-s') . ".json";
        
        return response()->json($userData, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Request account deletion
     */
    public function requestDeletion(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'password' => 'required|current_password',
            'reason' => 'required|string|max:500',
        ]);

        // Log the deletion request
        ActivityLog::logActivity(
            'account_deletion_requested',
            'requested',
            $user,
            null,
            ['reason' => $request->reason],
            ['ip_address' => request()->ip(), 'user_agent' => request()->userAgent()],
            'Account deletion requested'
        );

        // Here you would typically:
        // 1. Send notification to admin
        // 2. Schedule account for deletion
        // 3. Send confirmation email to user

        return redirect()->route('settings.privacy')
            ->with('success', 'Account deletion request submitted. An administrator will review your request.');
    }

    /**
     * Clear notification history
     */
    public function clearNotifications()
    {
        $user = Auth::user();
        
        $deletedCount = $user->notifications()->delete();

        // Log the action
        ActivityLog::logActivity(
            'notifications_cleared',
            'deleted',
            $user,
            null,
            ['deleted_count' => $deletedCount],
            ['ip_address' => request()->ip(), 'user_agent' => request()->userAgent()],
            "Cleared {$deletedCount} notifications"
        );

        return redirect()->back()
            ->with('success', "Cleared {$deletedCount} notifications successfully!");
    }

    /**
     * Export activity log
     */
    public function exportActivity()
    {
        $user = Auth::user();
        
        $activities = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($activity) {
                return [
                    'Date' => $activity->created_at->format('Y-m-d H:i:s'),
                    'Event Type' => $activity->event_type,
                    'Action' => $activity->action,
                    'Description' => $activity->description,
                    'IP Address' => $activity->ip_address,
                ];
            });

        $fileName = "activity_log_{$user->id}_" . now()->format('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Date', 'Event Type', 'Action', 'Description', 'IP Address']);
            
            // Add data
            foreach ($activities as $activity) {
                fputcsv($file, $activity);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get default notification preferences
     */
    private function getDefaultNotificationPreferences(): array
    {
        return [
            'email_notifications' => true,
            'form_submissions' => true,
            'thesis_updates' => true,
            'defense_reminders' => true,
            'system_alerts' => true,
            'role_changes' => true,
        ];
    }
}
