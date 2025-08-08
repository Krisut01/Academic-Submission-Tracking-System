<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $query = Auth::user()->notifications()
            ->orderBy('created_at', 'desc');

        // Filter by read status
        if ($request->filled('unread_only')) {
            $query->where('is_read', false);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $notifications = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'notifications' => $notifications->items(),
                'unread_count' => Auth::user()->unread_notification_count,
            ]);
        }

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure user owns this notification
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $notification->markAsRead();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'unread_count' => Auth::user()->unread_notification_count,
            ]);
        }

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->markAllNotificationsAsRead();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'unread_count' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    /**
     * Get unread notification count for the header
     */
    public function getUnreadCount()
    {
        return response()->json([
            'unread_count' => Auth::user()->unread_notification_count,
        ]);
    }

    /**
     * Get recent notifications for dropdown
     */
    public function getRecent()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unread_notification_count,
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        // Ensure user owns this notification
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $notification->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'unread_count' => Auth::user()->unread_notification_count,
            ]);
        }

        return redirect()->back()->with('success', 'Notification deleted');
    }
}
