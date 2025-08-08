<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            👥 {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-800 dark:text-green-300 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-800 dark:text-red-300 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Page Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                User Management Dashboard
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Manage user accounts, roles, and permissions across the system.
                            </p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.users.export') }}" 
                               class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                📊 Export CSV
                            </a>
                            <a href="{{ route('admin.users.create') }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                ➕ Add New User
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Total Users</h3>
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">All accounts</p>
                </div>

                <!-- Active Users -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-6 border border-green-200/50 dark:border-green-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-green-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active_users'] }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Active Users</h3>
                    <p class="text-xs text-green-600 dark:text-green-400 font-medium">Currently active</p>
                </div>

                <!-- Students -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200/50 dark:border-purple-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-purple-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.168 18.477 18.754 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['students'] }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Students</h3>
                    <p class="text-xs text-purple-600 dark:text-purple-400 font-medium">Student accounts</p>
                </div>

                <!-- Faculty -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-2xl p-6 border border-orange-200/50 dark:border-orange-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-orange-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['faculty'] }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Faculty</h3>
                    <p class="text-xs text-orange-600 dark:text-orange-400 font-medium">Faculty members</p>
                </div>

                <!-- Inactive -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-2xl p-6 border border-red-200/50 dark:border-red-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-red-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['inactive_users'] }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Inactive</h3>
                    <p class="text-xs text-red-600 dark:text-red-400 font-medium">Deactivated accounts</p>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.users') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Users</label>
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Name or email..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Role Filter -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter by Role</label>
                            <select name="role" id="role" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Roles</option>
                                <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="faculty" {{ request('role') === 'faculty' ? 'selected' : '' }}>Faculty</option>
                                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter by Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                🔍 Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Accounts</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $users->total() }} total users</span>
                    </div>

                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($users as $user)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-medium text-sm mr-4">
                                                        {{ substr($user->name, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($user->role === 'admin') bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200
                                                    @elseif($user->role === 'faculty') bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-200
                                                    @else bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200 @endif">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($user->deleted_at)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                                                        Inactive
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                                                        Active
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $user->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        Edit
                                                    </a>
                                                    @if($user->id !== Auth::id())
                                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" 
                                                                    class="text-{{ $user->deleted_at ? 'green' : 'yellow' }}-600 hover:text-{{ $user->deleted_at ? 'green' : 'yellow' }}-900 dark:text-{{ $user->deleted_at ? 'green' : 'yellow' }}-400">
                                                                {{ $user->deleted_at ? 'Activate' : 'Deactivate' }}
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                                              onsubmit="return confirm('Are you sure you want to permanently delete this user?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Bulk Actions -->
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <form method="POST" action="{{ route('admin.users.bulk-action') }}" id="bulk-form">
                                @csrf
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">With selected:</span>
                                    <select name="action" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                        <option value="">Choose action...</option>
                                        <option value="activate">Activate</option>
                                        <option value="deactivate">Deactivate</option>
                                        <option value="delete">Permanently Delete</option>
                                    </select>
                                    <button type="submit" 
                                            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition"
                                            onclick="return confirm('Are you sure you want to perform this bulk action?')">
                                        Apply
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $users->withQueryString()->links() }}
                        </div>

                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No users found</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">No users match your current filter criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for bulk actions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const bulkForm = document.getElementById('bulk-form');

            // Select all functionality
            selectAll.addEventListener('change', function() {
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Bulk form submission
            bulkForm.addEventListener('submit', function(e) {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one user.');
                    return;
                }

                // Add checked user IDs to form
                checkedBoxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'user_ids[]';
                    input.value = checkbox.value;
                    this.appendChild(input);
                });
            });
        });
    </script>
</x-app-layout> 