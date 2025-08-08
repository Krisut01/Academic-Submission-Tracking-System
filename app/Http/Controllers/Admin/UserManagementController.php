<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
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
     * Display the user management dashboard
     */
    public function index(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $query = User::query();

        // Apply filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNotNull('deleted_at');
            }
        }

        $users = $query->withTrashed()->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total_users' => User::withTrashed()->count(),
            'active_users' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'faculty' => User::where('role', 'faculty')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'inactive_users' => User::onlyTrashed()->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $this->ensureUserIsAdmin();
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['student', 'faculty', 'admin'])],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), // Auto-verify admin-created accounts
        ]);

        return redirect()->route('admin.users')
            ->with('success', "User {$user->name} ({$user->role}) created successfully!");
    }

    /**
     * Show the form for editing a user
     */
    public function edit(User $user)
    {
        $this->ensureUserIsAdmin();
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $this->ensureUserIsAdmin();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'role' => ['required', Rule::in(['student', 'faculty', 'admin'])],
        ]);

        // Store old role for event
        $oldRole = $user->role;
        $changedBy = Auth::user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Fire event for cross-system updates if role changed
        if ($oldRole !== $request->role) {
            event(new \App\Events\UserRoleChanged(
                $user,
                $oldRole,
                $request->role,
                $changedBy,
                [
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'change_method' => 'admin_interface',
                ]
            ));
        }

        return redirect()->route('admin.users')
            ->with('success', "User {$user->name} updated successfully!");
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        $this->ensureUserIsAdmin();
        
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users')
            ->with('success', "Password reset for {$user->name} successfully!");
    }

    /**
     * Deactivate/Reactivate user account
     */
    public function toggleStatus(User $user)
    {
        $this->ensureUserIsAdmin();
        
        // Prevent self-deactivation
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot deactivate your own account!');
        }

        if ($user->trashed()) {
            $user->restore();
            $message = "User {$user->name} has been reactivated successfully!";
        } else {
            $user->delete();
            $message = "User {$user->name} has been deactivated successfully!";
        }

        return redirect()->route('admin.users')
            ->with('success', $message);
    }

    /**
     * Permanently delete user
     */
    public function destroy(User $user)
    {
        $this->ensureUserIsAdmin();
        
        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot delete your own account!');
        }

        $name = $user->name;
        $user->forceDelete();

        return redirect()->route('admin.users')
            ->with('success', "User {$name} has been permanently deleted!");
    }

    /**
     * Bulk actions for users
     */
    public function bulkAction(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = $request->user_ids;
        
        // Remove current admin from bulk actions
        $userIds = array_filter($userIds, function($id) {
            return $id != Auth::id();
        });

        if (empty($userIds)) {
            return redirect()->route('admin.users')
                ->with('error', 'Cannot perform bulk actions on your own account!');
        }

        $count = 0;
        switch ($request->action) {
            case 'activate':
                $count = User::withTrashed()->whereIn('id', $userIds)->restore();
                $message = "{$count} users activated successfully!";
                break;
                
            case 'deactivate':
                $count = User::whereIn('id', $userIds)->delete();
                $message = "{$count} users deactivated successfully!";
                break;
                
            case 'delete':
                $count = User::withTrashed()->whereIn('id', $userIds)->forceDelete();
                $message = "{$count} users permanently deleted!";
                break;
        }

        return redirect()->route('admin.users')->with('success', $message);
    }

    /**
     * Export users data
     */
    public function export(Request $request)
    {
        $this->ensureUserIsAdmin();
        
        $query = User::withTrashed();
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->get();

        $filename = 'users_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($users) {
            $handle = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Status', 'Created At', 'Updated At']);
            
            // Add user data
            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->deleted_at ? 'Inactive' : 'Active',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->updated_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($handle);
        }, 200, $headers);
    }
}
