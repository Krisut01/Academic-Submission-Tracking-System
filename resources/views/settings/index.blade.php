<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ⚙️ {{ __('Settings & Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                        <span class="text-blue-600 dark:text-blue-400 font-bold text-xl">
                            {{ substr($user->name, 0, 2) }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-{{ $user->role === 'admin' ? 'red' : ($user->role === 'faculty' ? 'purple' : 'blue') }}-100 text-{{ $user->role === 'admin' ? 'red' : ($user->role === 'faculty' ? 'purple' : 'blue') }}-800 dark:bg-{{ $user->role === 'admin' ? 'red' : ($user->role === 'faculty' ? 'purple' : 'blue') }}-900/50 dark:text-{{ $user->role === 'admin' ? 'red' : ($user->role === 'faculty' ? 'purple' : 'blue') }}-200">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Notifications -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM7 7h10l-5-5 5 5H7z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $notificationStats['total_notifications'] ?? 0 }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Notifications</h3>
                </div>

                <!-- Unread Notifications -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-2xl p-6 border border-orange-200/50 dark:border-orange-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-orange-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $notificationStats['unread_notifications'] ?? 0 }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Unread Notifications</h3>
                </div>

                <!-- Urgent Notifications -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-2xl p-6 border border-red-200/50 dark:border-red-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-red-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $notificationStats['urgent_notifications'] ?? 0 }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Urgent Notifications</h3>
                </div>
            </div>

            <!-- Settings Menu -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Settings Navigation -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Settings Menu</h3>
                        <nav class="space-y-2">
                            <a href="{{ route('settings.profile') }}" 
                               class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile Settings
                            </a>
                            <a href="{{ route('settings.password') }}" 
                               class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Change Password
                            </a>
                            <a href="{{ route('settings.notifications') }}" 
                               class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM7 7h10l-5-5 5 5H7z"></path>
                                </svg>
                                Notification Preferences
                            </a>
                            <a href="{{ route('settings.activity') }}" 
                               class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Activity Log
                            </a>
                            <a href="{{ route('settings.privacy') }}" 
                               class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Privacy & Security
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <!-- Recent Activity -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                            <a href="{{ route('settings.activity') }}" 
                               class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                View All
                            </a>
                        </div>

                        @if($recentActivities->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentActivities->take(5) as $activity)
                                    <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $activity->description }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                        </div>
                                        <span class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-full">
                                            {{ ucfirst(str_replace('_', ' ', $activity->event_type)) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">No recent activity to display</p>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Quick Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('settings.download-data') }}" 
                               class="flex items-center gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition duration-200">
                                <div class="p-2 bg-blue-500 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Download Data</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Export your account data</p>
                                </div>
                            </a>

                            <a href="{{ route('settings.export-activity') }}" 
                               class="flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 rounded-lg transition duration-200">
                                <div class="p-2 bg-green-500 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Export Activity</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Download activity log as CSV</p>
                                </div>
                            </a>

                            <form method="POST" action="{{ route('settings.clear-notifications') }}" class="w-full">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center gap-3 p-4 bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 rounded-lg transition duration-200">
                                    <div class="p-2 bg-yellow-500 rounded-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-medium text-gray-900 dark:text-white">Clear Notifications</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Remove all notifications</p>
                                    </div>
                                </button>
                            </form>

                            <a href="{{ route('notifications.index') }}" 
                               class="flex items-center gap-3 p-4 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 rounded-lg transition duration-200">
                                <div class="p-2 bg-purple-500 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM7 7h10l-5-5 5 5H7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">View Notifications</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage all notifications</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 