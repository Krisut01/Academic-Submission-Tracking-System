<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @if(isset($userRole))
                {{ __('Dashboard') }} - {{ ucfirst($userRole) }}
            @else
                {{ __('Dashboard') }} - {{ ucfirst(Auth::user()->role) }}
            @endif
        </h2>
    </x-slot>

    <!-- Custom Styles for Button Text Visibility -->
    <style>
        .btn-primary {
            background-color: #2563eb !important;
            color: #ffffff !important;
            border: none !important;
        }
        .btn-primary:hover {
            background-color: #1d4ed8 !important;
            color: #ffffff !important;
        }
        .btn-secondary {
            background-color: #374151 !important;
            color: #ffffff !important;
            border: none !important;
        }
        .btn-secondary:hover {
            background-color: #1f2937 !important;
            color: #ffffff !important;
        }
        .btn-text-white {
            color: #ffffff !important;
            text-decoration: none !important;
        }
        .btn-text-white:hover {
            color: #ffffff !important;
            text-decoration: none !important;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center gap-4">
                        @if(Auth::user()->role === 'admin')
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        @elseif(Auth::user()->role === 'faculty')
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.168 18.477 18.754 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-lg font-semibold">Welcome back, {{ Auth::user()->name }}!</h3>
                            <p class="text-gray-600 dark:text-gray-400">You are logged in as {{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role-specific content -->
            @if(Auth::user()->role === 'admin')
                <!-- Modern Admin Dashboard -->
                <div class="space-y-8">
                    <!-- Dashboard Header with Refresh Button -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
                            <p class="text-gray-600 dark:text-gray-400">Real-time system overview and statistics</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span>Live Updates</span>
                            </div>
                            <button onclick="refreshDashboard()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Refresh
                            </button>
                        </div>
                    </div>

                    <!-- Quick Stats Overview -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Total Submissions -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200/50 dark:border-purple-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-purple-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['total_submissions'] ?? 0 }}</span>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Submissions Received</h3>
                            <p class="text-xs text-purple-600 dark:text-purple-400 font-medium">+{{ $dashboardData['submissions_this_week'] ?? 0 }} this week</p>
                        </div>

                        <!-- Active Users -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-blue-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['active_users'] ?? 0 }}</span>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Active Users</h3>
                            <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">{{ $dashboardData['active_students'] ?? 0 }} Students, {{ $dashboardData['active_faculty'] ?? 0 }} Faculty</p>
                        </div>

                        <!-- Defense Schedule -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-2xl p-6 border border-orange-200/50 dark:border-orange-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-orange-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 6v6a1 1 0 001 1h4a1 1 0 001-1v-6M8 13h8"></path>
                                    </svg>
                                </div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['scheduled_defenses'] ?? 0 }}</span>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Defense Schedule</h3>
                            <p class="text-xs text-orange-600 dark:text-orange-400 font-medium">
                                @php
                                    try {
                                        $upcomingCount = class_exists('\App\Models\PanelAssignment') ? \App\Models\PanelAssignment::upcoming()->count() : 0;
                                        echo $upcomingCount . ' this week';
                                    } catch (\Exception $e) {
                                        echo '0 this week';
                                    }
                                @endphp
                            </p>
                        </div>

                        <!-- System Status -->
                        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-2xl p-6 border border-emerald-200/50 dark:border-emerald-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-emerald-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                @php
                                    $totalItems = ($dashboardData['total_submissions'] ?? 0);
                                    $overdueItems = ($dashboardData['overdue_reviews'] ?? 0);
                                    $systemHealth = $totalItems > 0 ? round((($totalItems - $overdueItems) / $totalItems) * 100) : 100;
                                @endphp
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $systemHealth }}%</span>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">System Health</h3>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                                @if($systemHealth >= 95)
                                    All systems online
                                @elseif($systemHealth >= 80)
                                    Minor issues detected
                                @else
                                    Attention required
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                        <!-- Recent Activity & System Overview -->
                        <div class="xl:col-span-2">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-8">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                                        <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
                                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        Recent System Activity
                                    </h2>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        @if(isset($dashboardData['recent_activities']) && $dashboardData['recent_activities']->count() > 0)
                                            Last updated {{ $dashboardData['recent_activities']->first()->created_at->diffForHumans() }}
                                        @else
                                            No recent activity
                                        @endif
                                    </span>
                                </div>

                                <!-- Critical Alert -->
                                <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 rounded-xl p-6 mb-6 border border-red-100 dark:border-red-800/50">
                                    <div class="flex items-start gap-4">
                                        <div class="p-2 bg-red-500 rounded-lg shadow-sm">
                                            <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $dashboardData['overdue_reviews'] ?? 0 }} Overdue Documents</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                                                @if(($dashboardData['overdue_reviews'] ?? 0) > 0)
                                                    Faculty reviews pending for more than 5 days require immediate attention
                                                @else
                                                    All reviews are up to date. Great work!
                                                @endif
                                            </p>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ ($dashboardData['overdue_reviews'] ?? 0) > 0 ? 'red' : 'green' }}-100 dark:bg-{{ ($dashboardData['overdue_reviews'] ?? 0) > 0 ? 'red' : 'green' }}-900/50 text-{{ ($dashboardData['overdue_reviews'] ?? 0) > 0 ? 'red' : 'green' }}-800 dark:text-{{ ($dashboardData['overdue_reviews'] ?? 0) > 0 ? 'red' : 'green' }}-200">
                                                {{ ($dashboardData['overdue_reviews'] ?? 0) > 0 ? 'Critical Priority' : 'All Good' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Recent Activity Items -->
                                @if(isset($dashboardData['recent_activities']) && $dashboardData['recent_activities']->count() > 0)
                                    <div class="space-y-4">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-4">Recent Activities</h4>
                                        
                                        @foreach($dashboardData['recent_activities'] as $activity)
                                            @php
                                                $eventColor = match($activity->event_type) {
                                                    'form_submitted' => 'blue',
                                                    'thesis_reviewed' => 'green',
                                                    'user_login' => 'purple',
                                                    'role_changed' => 'orange',
                                                    default => 'gray'
                                                };
                                            @endphp
                                            <!-- Activity Item -->
                                            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                                <div class="w-3 h-3 bg-{{ $eventColor }}-500 rounded-full flex-shrink-0"></div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->description }}</p>
                                                        <span class="text-xs text-{{ $eventColor }}-600 dark:text-{{ $eventColor }}-400 font-medium">{{ ucfirst(str_replace('_', ' ', $activity->event_type)) }}</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->user?->name ?? 'System' }} • {{ $activity->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-4">Recent Activities</h4>
                                        <div class="text-center py-6">
                                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">No recent activity</p>
                                            <p class="text-gray-400 dark:text-gray-500 text-xs">Activity will appear here when you submit forms or documents</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Admin Actions -->
                                <div class="flex gap-3 mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('admin.records') }}" class="flex-1 bg-purple-600 hover:bg-purple-700 text-gray-900 dark:text-white px-4 py-2 rounded-lg font-medium transition duration-200 shadow-sm text-center">
                                        View All Records
                                    </a>
                                    <a href="{{ route('admin.reports') }}" class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg font-medium transition duration-200 text-center">
                                        Generate Reports
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- Quick Admin Actions -->
                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                    <div class="p-1 bg-red-100 dark:bg-red-900/50 rounded-lg">
                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    Admin Controls
                                </h3>

                                <div class="space-y-3">
                                    <!-- User Management - Priority -->
                                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-4 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group border-l-4 border-blue-500">
                                        <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white">User Management</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Manage accounts & roles</p>
                                        </div>
                                    </a>

                                    <!-- Records & RMT Table -->
                                    <a href="{{ route('admin.records') }}" class="flex items-center gap-3 p-4 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200 group border-l-4 border-purple-500">
                                        <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg group-hover:bg-purple-200 dark:group-hover:bg-purple-800/50 transition-colors">
                                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white">Records & RMT Table</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">View all submissions</p>
                                        </div>
                                    </a>

                                    <!-- Panel Assignment -->
                                    <a href="{{ route('admin.panel') }}" class="flex items-center gap-3 p-4 rounded-xl hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-all duration-200 group border-l-4 border-orange-500">
                                        <div class="p-2 bg-orange-100 dark:bg-orange-900/50 rounded-lg group-hover:bg-orange-200 dark:group-hover:bg-orange-800/50 transition-colors">
                                            <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 6v6a1 1 0 001 1h4a1 1 0 001-1v-6M8 13h8"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white">Panel Assignment</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Defense scheduling</p>
                                        </div>
                                    </a>

                                    <!-- Generate Reports -->
                                    <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 p-4 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 group border-l-4 border-green-500">
                                        <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg group-hover:bg-green-200 dark:group-hover:bg-green-800/50 transition-colors">
                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white">Generate Reports</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Analytics & exports</p>
                                        </div>
                                    </a>

                                    <!-- System Settings -->
                                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 group">
                                        <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors">
                                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white">System Settings</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Configuration & security</p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- System Alerts -->
                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                        <div class="p-1 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg">
                                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                        </div>
                                        System Status
                                    </h3>
                                    @php
                                        $criticalAlerts = ($dashboardData['overdue_reviews'] ?? 0) > 0 ? 1 : 0;
                                    @endphp
                                    @if($criticalAlerts > 0)
                                        <span class="bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 text-xs px-2 py-1 rounded-full font-medium">
                                            {{ $criticalAlerts }} Alert{{ $criticalAlerts > 1 ? 's' : '' }}
                                        </span>
                                    @else
                                        <span class="bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 text-xs px-2 py-1 rounded-full font-medium">
                                            All Good
                                        </span>
                                    @endif
                                </div>

                                @if(($dashboardData['overdue_reviews'] ?? 0) > 0)
                                    <div class="space-y-3">
                                        <!-- Overdue Reviews Alert -->
                                        <div class="p-3 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 rounded-r-lg">
                                            <p class="text-sm font-medium text-red-800 dark:text-red-300 mb-1">Overdue Faculty Reviews</p>
                                            <p class="text-xs text-red-600 dark:text-red-400">{{ $dashboardData['overdue_reviews'] }} documents pending review for 5+ days</p>
                                            <span class="text-xs text-red-500 dark:text-red-400 font-medium">Needs Attention</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-6">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">System Running Smoothly</h4>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">No issues detected</p>
                                    </div>
                                @endif

                                @if(($dashboardData['overdue_reviews'] ?? 0) > 0)
                                    <a href="{{ route('admin.records') }}" class="w-full mt-4 text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium transition-colors text-center block">
                                        View Overdue Items →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @elseif(Auth::user()->role === 'faculty')
                <!-- Modern Faculty Dashboard -->
                <div class="space-y-8">
                    <!-- Quick Stats Overview -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Pending Reviews -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-2xl p-6 border border-red-200/50 dark:border-red-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-red-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['pending_reviews'] ?? 0 }}</span>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Pending Reviews</h3>
                            <p class="text-xs text-red-600 dark:text-red-400 font-medium">{{ $dashboardData['urgent_reviews'] ?? 0 }} urgent</p>
                        </div>

                        <!-- Completed Reviews -->
                        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-2xl p-6 border border-emerald-200/50 dark:border-emerald-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-emerald-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['completed_reviews'] ?? 0 }}</span>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Completed Reviews</h3>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">This month</p>
                        </div>

                        <!-- Active Students -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-blue-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['assigned_students'] ?? 0 }}</span>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Assigned Students</h3>
                            <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Under supervision</p>
                        </div>

                        <!-- Avg Review Time -->
                        <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 rounded-2xl p-6 border border-amber-200/50 dark:border-amber-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-amber-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['avg_review_time'] ?? 0 }}</span>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Avg Review Time</h3>
                            <p class="text-xs text-amber-600 dark:text-amber-400 font-medium">Days</p>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                        <!-- Recent Reviews & Alerts -->
                        <div class="xl:col-span-2">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-8">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                                        <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                        </div>
                                        Pending Reviews & Alerts
                                    </h2>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        @if(isset($dashboardData['recent_submissions']) && $dashboardData['recent_submissions']->count() > 0)
                                            Last updated {{ $dashboardData['recent_submissions']->first()->created_at->diffForHumans() }}
                                        @else
                                            No recent submissions
                                        @endif
                                    </span>
                                </div>

                                <!-- Urgent Alert -->
                                <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 rounded-xl p-6 mb-6 border border-red-100 dark:border-red-800/50">
                                    <div class="flex items-start gap-4">
                                        <div class="p-2 bg-red-500 rounded-lg shadow-sm">
                                            <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $dashboardData['urgent_reviews'] ?? 0 }} Urgent Reviews</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                                                @if(($dashboardData['urgent_reviews'] ?? 0) > 0)
                                                    Thesis proposals pending review for more than 5 days
                                                @else
                                                    All reviews are up to date
                                                @endif
                                            </p>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ ($dashboardData['urgent_reviews'] ?? 0) > 0 ? 'red' : 'green' }}-100 dark:bg-{{ ($dashboardData['urgent_reviews'] ?? 0) > 0 ? 'red' : 'green' }}-900/50 text-{{ ($dashboardData['urgent_reviews'] ?? 0) > 0 ? 'red' : 'green' }}-800 dark:text-{{ ($dashboardData['urgent_reviews'] ?? 0) > 0 ? 'red' : 'green' }}-200">
                                                {{ ($dashboardData['urgent_reviews'] ?? 0) > 0 ? 'Action Required' : 'All Good' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Recent Submissions -->
                                @if(isset($dashboardData['recent_submissions']) && $dashboardData['recent_submissions']->count() > 0)
                                    <div class="space-y-4">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-4">Recent Submissions</h4>
                                        
                                        @foreach($dashboardData['recent_submissions'] as $submission)
                                            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                                <div class="w-3 h-3 bg-{{ $submission->status === 'pending' ? 'yellow' : 'green' }}-500 rounded-full flex-shrink-0"></div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $submission->user->name }} - {{ ucfirst(str_replace('_', ' ', $submission->document_type)) }}</p>
                                                        <span class="text-xs text-{{ $submission->status === 'pending' ? 'yellow' : 'green' }}-600 dark:text-{{ $submission->status === 'pending' ? 'yellow' : 'green' }}-400 font-medium">{{ ucfirst($submission->status) }}</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Submitted {{ $submission->created_at->diffForHumans() }} • {{ Str::limit($submission->title, 50) }}</p>
                                                </div>
                                                @if($submission->status === 'pending')
                                                    <a href="{{ route('faculty.thesis.show', $submission) }}" class="px-3 py-1.5 bg-blue-600 text-gray-900 dark:text-white text-xs rounded-lg hover:bg-blue-700 transition">
                                                        Review
                                                    </a>
                                                @else
                                                    <span class="px-3 py-1.5 bg-green-100 text-green-700 text-xs rounded-lg">
                                                        ✓ Complete
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-4">Recent Submissions</h4>
                                        <div class="text-center py-6">
                                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">No recent submissions</p>
                                            <p class="text-gray-400 dark:text-gray-500 text-xs">New submissions will appear here</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex gap-3 mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('faculty.thesis.reviews') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white px-4 py-2 rounded-lg font-medium transition duration-200 shadow-sm text-center">
                                        Review All Pending
                                    </a>
                                    <a href="{{ route('faculty.thesis.progress') }}" class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg font-medium transition duration-200 text-center">
                                        Track Progress
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- Quick Actions -->
                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                    <div class="p-1 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    Quick Actions
                                </h3>

                                <div class="space-y-3">
                                    <!-- Review Thesis Forms - Priority -->
                                    <a href="{{ route('faculty.thesis.reviews') }}" class="flex items-center gap-3 p-4 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 group border-l-4 border-red-500">
                                        <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg group-hover:bg-red-200 dark:group-hover:bg-red-800/50 transition-colors">
                                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white">Review Thesis Forms</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $dashboardData['pending_reviews'] ?? 0 }} pending submissions</p>
                                        </div>
                                    </a>

                                    <!-- Track Thesis Progress -->
                                    <a href="{{ route('faculty.thesis.progress') }}" class="flex items-center gap-3 p-4 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group border-l-4 border-blue-500">
                                        <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white">Track Thesis Progress</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Monitor student progress</p>
                                        </div>
                                    </a>

                                    <!-- Manage Exams -->
                                    <a href="#" class="flex items-center gap-3 p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 group">
                                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/50 transition-colors">
                                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white">Manage Exams</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Create & edit examinations</p>
                                        </div>
                                    </a>

                                    <!-- Student Results -->
                                    <a href="#" class="flex items-center gap-3 p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 group">
                                        <div class="p-2 bg-amber-100 dark:bg-amber-900/50 rounded-lg group-hover:bg-amber-200 dark:group-hover:bg-amber-800/50 transition-colors">
                                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white">Student Results</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Review performance</p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Recent Notifications -->
                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                        <div class="p-1 bg-red-100 dark:bg-red-900/50 rounded-lg">
                                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM8.88 14.88a3 3 0 104.24-4.24M8.88 14.88L12 18l3.12-3.12M8.88 14.88a3 3 0 104.24-4.24"></path>
                                            </svg>
                                        </div>
                                        Notifications
                                    </h3>
                                    <span class="bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 text-xs px-2 py-1 rounded-full font-medium">
                                        {{ $dashboardData['unread_notifications'] ?? 0 }} New
                                    </span>
                                </div>

                                @if(Auth::user()->notifications()->take(3)->count() > 0)
                                    <div class="space-y-3">
                                        @foreach(Auth::user()->notifications()->orderBy('created_at', 'desc')->take(3)->get() as $notification)
                                            <div class="p-3 bg-{{ $notification->priority_color }}-50 dark:bg-{{ $notification->priority_color }}-900/20 border-l-4 border-{{ $notification->priority_color }}-400 rounded-r-lg">
                                                <p class="text-sm font-medium text-{{ $notification->priority_color }}-800 dark:text-{{ $notification->priority_color }}-300 mb-1">{{ $notification->title }}</p>
                                                <p class="text-xs text-{{ $notification->priority_color }}-600 dark:text-{{ $notification->priority_color }}-400">{{ Str::limit($notification->message, 50) }}</p>
                                                <span class="text-xs text-{{ $notification->priority_color }}-500 dark:text-{{ $notification->priority_color }}-400 font-medium">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-6">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">No Notifications</h4>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">You're all caught up!</p>
                                    </div>
                                @endif

                                @if(Auth::user()->notifications()->count() > 0)
                                    <a href="{{ route('notifications.index') }}" class="w-full mt-4 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors text-center block">
                                        View All Notifications →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Enhanced Student Dashboard -->
                <div class="space-y-8">
                    <!-- Dashboard Header with Refresh Button -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Student Dashboard</h1>
                            <p class="text-gray-600 dark:text-gray-400">Track your academic progress and submissions</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                <span>Auto-refresh</span>
                            </div>
                            <button data-refresh="dashboard" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Refresh
                            </button>
                        </div>
                    </div>
                    <!-- Quick Stats Overview with improved contrast -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Forms Submitted -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-blue-600 rounded-lg shadow-sm">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['total_forms'] ?? 0 }}</span>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Forms Submitted</h3>
                            <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">+{{ $dashboardData['forms_this_week'] ?? 0 }} this week</p>
                        </div>

                        <!-- Thesis Documents -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-emerald-600 rounded-lg shadow-sm">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.168 18.477 18.754 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['total_thesis_documents'] ?? 0 }}</span>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Thesis Documents</h3>
                            <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">+{{ $dashboardData['thesis_this_week'] ?? 0 }} this week</p>
                        </div>

                        <!-- Approved Items -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-green-600 rounded-lg shadow-sm">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ ($dashboardData['approved_forms'] ?? 0) + ($dashboardData['approved_thesis'] ?? 0) }}</span>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Approved Items</h3>
                            @php
                                $totalApproved = ($dashboardData['approved_forms'] ?? 0) + ($dashboardData['approved_thesis'] ?? 0);
                                $totalItems = ($dashboardData['total_forms'] ?? 0) + ($dashboardData['total_thesis_documents'] ?? 0);
                                $approvalRate = $totalItems > 0 ? round(($totalApproved / $totalItems) * 100) : 0;
                            @endphp
                            <p class="text-sm text-green-600 dark:text-green-400 font-medium">{{ $approvalRate }}% approval rate</p>
                        </div>

                        <!-- Pending Reviews -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-orange-600 rounded-lg shadow-sm">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ ($dashboardData['pending_forms'] ?? 0) + ($dashboardData['pending_thesis'] ?? 0) }}</span>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Pending Reviews</h3>
                            @php
                                $urgentCount = 0;
                                if (isset($dashboardData['latest_form']) && $dashboardData['latest_form']?->status === 'pending') {
                                    $urgentCount++;
                                }
                                if (isset($dashboardData['latest_thesis']) && $dashboardData['latest_thesis']?->status === 'pending') {
                                    $urgentCount++;
                                }
                            @endphp
                            <p class="text-sm text-orange-600 dark:text-orange-400 font-medium">{{ $urgentCount }} need attention</p>
                        </div>
                    </div>

                    <!-- Main Content Layout with Better Spacing -->
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                        <!-- Research Progress Section with Improved Layout -->
                        <div class="xl:col-span-2">
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg">
                                <!-- Header with Better Padding -->
                                <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Research Progress</h2>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            @if(isset($dashboardData['current_thesis_progress']))
                                                Updated {{ $dashboardData['current_thesis_progress']->updated_at->diffForHumans() }}
                                            @else
                                                No recent activity
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Content with Proper Spacing -->
                                <div class="p-8">
                                    <!-- Defense Schedule Notification Popup -->
                                    @if(isset($dashboardData['defense_notification']) && $dashboardData['defense_notification'])
                                        <div id="defenseNotification" class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 rounded-xl p-6 mb-6 shadow-lg">
                                            <div class="flex items-start justify-between">
                                                <div class="flex items-start space-x-4">
                                                    <div class="p-3 bg-blue-600 rounded-lg flex-shrink-0">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 6v6a1 1 0 001 1h4a1 1 0 001-1v-6M8 13h8"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100">{{ $dashboardData['defense_notification']['title'] }}</h3>
                                                            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full">
                                                                {{ $dashboardData['defense_notification']['defense_type'] }}
                                                            </span>
                                                            @if(isset($dashboardData['defense_notification']['is_upcoming']) && $dashboardData['defense_notification']['is_upcoming'])
                                                                <span class="px-2 py-1 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 text-xs font-medium rounded-full">
                                                                    {{ $dashboardData['defense_notification']['days_until'] }} days away
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <p class="text-blue-800 dark:text-blue-200 font-medium mb-3">{{ $dashboardData['defense_notification']['message'] }}</p>
                                                        
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                            <div class="flex items-center space-x-2">
                                                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 6v6a1 1 0 001 1h4a1 1 0 001-1v-6M8 13h8"></path>
                                                                </svg>
                                                                <span class="text-blue-700 dark:text-blue-300">
                                                                    <strong>Date:</strong> {{ $dashboardData['defense_notification']['defense_date']->format('F j, Y \a\t g:i A') }}
                                                                </span>
                                                            </div>
                                                            <div class="flex items-center space-x-2">
                                                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                </svg>
                                                                <span class="text-blue-700 dark:text-blue-300">
                                                                    <strong>Venue:</strong> {{ $dashboardData['defense_notification']['venue'] }}
                                                                </span>
                                                            </div>
                                                            @if($dashboardData['defense_notification']['panel_chair'])
                                                            <div class="flex items-center space-x-2">
                                                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                </svg>
                                                                <span class="text-blue-700 dark:text-blue-300">
                                                                    <strong>Panel Chair:</strong> {{ $dashboardData['defense_notification']['panel_chair'] }}
                                                                </span>
                                                            </div>
                                                            @endif
                                                            @if($dashboardData['defense_notification']['secretary'])
                                                            <div class="flex items-center space-x-2">
                                                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                </svg>
                                                                <span class="text-blue-700 dark:text-blue-300">
                                                                    <strong>Secretary:</strong> {{ $dashboardData['defense_notification']['secretary'] }}
                                                                </span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        
                                                        @if($dashboardData['defense_notification']['instructions'])
                                                        <div class="mt-4 p-3 bg-blue-100 dark:bg-blue-800/30 rounded-lg">
                                                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                                                <strong>Instructions:</strong> {{ $dashboardData['defense_notification']['instructions'] }}
                                                            </p>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex flex-col space-y-2 ml-4">
                                                    <button onclick="viewDefenseDetails({{ $dashboardData['defense_notification']['assignment_id'] }})" 
                                                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        View Details
                                                    </button>
                                                    <button onclick="dismissDefenseNotification()" 
                                                            class="inline-flex items-center px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white text-xs font-medium rounded transition-colors duration-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Dismiss
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Real-time Research Progress Tracking -->
                                    @if(isset($dashboardData['research_progress']))
                                        @php $researchProgress = $dashboardData['research_progress']; @endphp
                                        
                                        <!-- Progress Overview -->
                                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 mb-6 border border-blue-200 dark:border-blue-700">
                                            <div class="flex items-center justify-between mb-4">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Research Progress</h3>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">Current Phase: {{ $researchProgress['current_phase'] }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $researchProgress['progress_percentage'] }}%</div>
                                                    <div class="text-sm text-gray-600 dark:text-gray-400">Complete</div>
                                                </div>
                                            </div>
                                            
                                            <!-- Progress Bar -->
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-4">
                                                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-3 rounded-full transition-all duration-500" 
                                                     style="width: {{ $researchProgress['progress_percentage'] }}%"></div>
                                            </div>
                                            
                                            <!-- Milestones Status -->
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-3 h-3 rounded-full {{ $researchProgress['milestones']['proposal_submitted'] ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                                    <span class="text-gray-700 dark:text-gray-300">Proposal</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-3 h-3 rounded-full {{ $researchProgress['milestones']['approval_sheet_submitted'] ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                                    <span class="text-gray-700 dark:text-gray-300">Approval</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-3 h-3 rounded-full {{ $researchProgress['milestones']['defense_scheduled'] ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                                    <span class="text-gray-700 dark:text-gray-300">Defense</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-3 h-3 rounded-full {{ $researchProgress['milestones']['final_manuscript_submitted'] ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                                    <span class="text-gray-700 dark:text-gray-300">Final</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Pending Actions -->
                                        @if(!empty($researchProgress['pending_actions']))
                                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-6 mb-6 border border-yellow-200 dark:border-yellow-700">
                                                <h4 class="text-lg font-semibold text-yellow-900 dark:text-yellow-100 mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Pending Actions
                                                </h4>
                                                <div class="space-y-3">
                                                    @foreach($researchProgress['pending_actions'] as $action)
                                                        <div class="flex items-start space-x-3 p-3 bg-yellow-100 dark:bg-yellow-800/30 rounded-lg">
                                                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 flex-shrink-0"></div>
                                                            <div>
                                                                <h5 class="font-medium text-yellow-900 dark:text-yellow-100">{{ $action['title'] }}</h5>
                                                                <p class="text-sm text-yellow-700 dark:text-yellow-300">{{ $action['description'] }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Current Thesis Status -->
                                        @if(isset($dashboardData['current_thesis_progress']))
                                            @php $currentThesis = $dashboardData['current_thesis_progress']; @endphp
                                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6 mb-6 border border-blue-200 dark:border-blue-700">
                                                <div class="flex items-start gap-4">
                                                    <div class="p-3 bg-blue-600 rounded-lg flex-shrink-0">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $currentThesis->title }}</h3>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                                                            {{ ucfirst(str_replace('_', ' ', $currentThesis->document_type)) }}
                                                            @if($currentThesis->reviewed_by && $currentThesis->reviewer)
                                                                - Under review by {{ $currentThesis->reviewer->name }}
                                                            @endif
                                                        </p>
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                                            {{ ucfirst(str_replace('_', ' ', $currentThesis->status)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-8 mb-6 text-center">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Active Thesis</h3>
                                                <p class="text-gray-600 dark:text-gray-400 mb-4">Start by submitting your thesis proposal</p>
                                                <a href="{{ route('student.thesis.index') }}" class="inline-flex items-center px-6 py-3 btn-primary font-semibold rounded-lg transition duration-200 shadow-sm btn-text-gray-900 dark:text-white">
                                                    Submit Thesis Document
                                                </a>
                                            </div>
                                        @endif

                                        <!-- All Documents Overview -->
                                        @if($researchProgress['all_documents']->isNotEmpty())
                                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 mb-6">
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">All Documents</h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    @foreach($researchProgress['all_documents'] as $document)
                                                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                                            <div class="flex items-center space-x-3">
                                                                <div class="w-3 h-3 rounded-full {{ $document->status === 'approved' ? 'bg-green-500' : ($document->status === 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}"></div>
                                                                <div>
                                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</p>
                                                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $document->status)) }}</p>
                                                                </div>
                                                            </div>
                                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $document->submission_date->format('M j') }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    <!-- Recent Activity with Better Spacing -->
                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Recent Activity</h4>
                                        
                                        @if(isset($dashboardData['recent_activities']) && $dashboardData['recent_activities']->count() > 0)
                                            <div class="space-y-4">
                                                @foreach($dashboardData['recent_activities'] as $activity)
                                                    @php
                                                        $eventColor = match($activity->event_type) {
                                                            'form_submitted' => 'blue',
                                                            'thesis_reviewed' => 'green',
                                                            'user_login' => 'purple',
                                                            'role_changed' => 'orange',
                                                            default => 'gray'
                                                        };
                                                    @endphp
                                                    <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                                        <div class="w-3 h-3 bg-{{ $eventColor }}-500 rounded-full flex-shrink-0"></div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center justify-between mb-1">
                                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->description }}</p>
                                                                <span class="text-xs text-{{ $eventColor }}-600 dark:text-{{ $eventColor }}-400 font-medium">{{ ucfirst(str_replace('_', ' ', $activity->event_type)) }}</span>
                                                            </div>
                                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $activity->user?->name ?? 'System' }} • {{ $activity->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-8">
                                                <p class="text-gray-600 dark:text-gray-400 mb-2">No recent activity</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-500">Activity will appear here when you submit forms or documents</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons with Better Styling -->
                                <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 rounded-b-xl">
                                    <div class="flex gap-4">
                                        <a href="{{ route('student.forms.history') }}" class="flex-1 btn-primary px-6 py-3 rounded-lg font-semibold transition duration-200 text-center shadow-sm btn-text-gray-900 dark:text-white">
                                            View Form History
                                        </a>
                                        <a href="{{ route('student.thesis.history') }}" class="flex-1 btn-secondary px-6 py-3 rounded-lg font-semibold transition duration-200 text-center shadow-sm btn-text-gray-900 dark:text-white">
                                            View Thesis History
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar with Better Spacing -->
                        <div class="space-y-8">
                            <!-- Quick Actions with Improved Layout -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg">
                                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
                                </div>
                                
                                <div class="p-6 space-y-4">
                                    <!-- Submit Academic Forms -->
                                    <a href="{{ route('student.forms.index') }}" class="flex items-center gap-4 p-4 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group border border-blue-200 dark:border-blue-700">
                                        <div class="p-3 bg-blue-600 rounded-lg group-hover:bg-blue-700 transition-colors flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-white">Submit Academic Forms</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Registration, Clearance, Evaluation</p>
                                        </div>
                                    </a>

                                    <!-- Submit Thesis Documents -->
                                    @if(isset($dashboardData['current_thesis_progress']))
                                        <!-- Show "Continue Thesis Work" if thesis exists -->
                                        <a href="{{ route('student.thesis.index') }}" class="flex items-center gap-4 p-4 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all duration-200 group border border-emerald-200 dark:border-emerald-700">
                                            <div class="p-3 bg-emerald-600 rounded-lg group-hover:bg-emerald-700 transition-colors flex-shrink-0">
                                                <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-900 dark:text-white">Continue Thesis Work</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Manage your active thesis</p>
                                            </div>
                                        </a>
                                    @else
                                        <!-- Show "Submit Thesis Documents" if no active thesis -->
                                        <a href="{{ route('student.thesis.index') }}" class="flex items-center gap-4 p-4 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all duration-200 group border border-emerald-200 dark:border-emerald-700">
                                            <div class="p-3 bg-emerald-600 rounded-lg group-hover:bg-emerald-700 transition-colors flex-shrink-0">
                                                <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.168 18.477 18.754 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-900 dark:text-white">Submit Thesis Documents</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Proposal, Approval, Panel, Manuscript</p>
                                            </div>
                                        </a>
                                    @endif

                                    <!-- View Submission History -->
                                    <a href="{{ route('student.forms.history') }}" class="flex items-center gap-4 p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 group">
                                        <div class="p-3 bg-gray-600 rounded-lg group-hover:bg-gray-700 transition-colors flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-white">View Submission History</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Track all submissions</p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Notifications with Better Layout -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg">
                                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                                        <span class="bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 text-sm px-3 py-1 rounded-full font-medium">
                                            {{ $dashboardData['unread_notifications'] ?? 0 }} New
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6">
                                    @if(Auth::user()->notifications()->take(3)->count() > 0)
                                        <div class="space-y-4">
                                            @foreach(Auth::user()->notifications()->orderBy('created_at', 'desc')->take(3)->get() as $notification)
                                                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 rounded-r-lg">
                                                    <p class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-1">{{ $notification->title }}</p>
                                                    <p class="text-sm text-blue-700 dark:text-blue-400 mb-2">{{ Str::limit($notification->message, 60) }}</p>
                                                    <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">{{ $notification->created_at->diffForHumans() }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Notifications</h4>
                                            <p class="text-gray-600 dark:text-gray-400">You're all caught up!</p>
                                        </div>
                                    @endif

                                    @if(Auth::user()->notifications()->count() > 0)
                                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                            <a href="{{ route('notifications.index') }}" class="w-full text-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors block">
                                                View All Notifications →
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if(Auth::user()->role === 'admin')
    <!-- Real-time Dashboard Updates -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global function for manual refresh
            window.refreshDashboard = function() {
                updateDashboardStats();
                updateRecentActivity();
                
                // Show refresh indicator
                const refreshBtn = document.querySelector('button[onclick="refreshDashboard()"]');
                if (refreshBtn) {
                    const originalText = refreshBtn.innerHTML;
                    refreshBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Refreshing...';
                    refreshBtn.disabled = true;
                    
                    setTimeout(() => {
                        refreshBtn.innerHTML = originalText;
                        refreshBtn.disabled = false;
                    }, 2000);
                }
            };
            
            // Function to update dashboard statistics
            function updateDashboardStats() {
                fetch('{{ route("admin.dashboard.stats") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update submissions received
                    const submissionsElement = document.querySelector('.bg-gradient-to-br.from-purple-50 .text-2xl');
                    if (submissionsElement) {
                        submissionsElement.textContent = data.total_submissions || 0;
                    }
                    
                    const submissionsWeekElement = document.querySelector('.bg-gradient-to-br.from-purple-50 .text-xs');
                    if (submissionsWeekElement) {
                        submissionsWeekElement.textContent = `+${data.submissions_this_week || 0} this week`;
                    }
                    
                    // Update active users
                    const activeUsersElement = document.querySelector('.bg-gradient-to-br.from-blue-50 .text-2xl');
                    if (activeUsersElement) {
                        activeUsersElement.textContent = data.active_users || 0;
                    }
                    
                    const usersBreakdownElement = document.querySelector('.bg-gradient-to-br.from-blue-50 .text-xs');
                    if (usersBreakdownElement) {
                        usersBreakdownElement.textContent = `${data.active_students || 0} Students, ${data.active_faculty || 0} Faculty`;
                    }
                    
                    // Update defense schedule
                    const defenseElement = document.querySelector('.bg-gradient-to-br.from-orange-50 .text-2xl');
                    if (defenseElement) {
                        defenseElement.textContent = data.scheduled_defenses || 0;
                    }
                    
                    const defenseWeekElement = document.querySelector('.bg-gradient-to-br.from-orange-50 .text-xs');
                    if (defenseWeekElement) {
                        defenseWeekElement.textContent = `${data.defenses_this_week || 0} this week`;
                    }
                    
                    // Update system health
                    const systemHealthElement = document.querySelector('.bg-gradient-to-br.from-emerald-50 .text-2xl');
                    if (systemHealthElement) {
                        systemHealthElement.textContent = `${data.system_health || 100}%`;
                    }
                    
                    const systemStatusElement = document.querySelector('.bg-gradient-to-br.from-emerald-50 .text-xs');
                    if (systemStatusElement) {
                        if (data.system_health >= 95) {
                            systemStatusElement.textContent = 'All systems online';
                        } else if (data.system_health >= 80) {
                            systemStatusElement.textContent = 'Minor issues detected';
                        } else {
                            systemStatusElement.textContent = 'Attention required';
                        }
                    }
                })
                .catch(error => {
                    console.log('Dashboard stats update failed:', error);
                });
            }
            
            // Function to update recent activity
            function updateRecentActivity() {
                fetch('{{ route("admin.dashboard.activity") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update recent activity section if it exists
                    const activityContainer = document.querySelector('.recent-activity-container');
                    if (activityContainer && data.length > 0) {
                        // You can implement activity updates here if needed
                        console.log('Recent activity updated:', data.length, 'items');
                    }
                })
                .catch(error => {
                    console.log('Recent activity update failed:', error);
                });
            }
            
            // Update stats immediately on page load
            updateDashboardStats();
            updateRecentActivity();
            
            // Set up periodic updates every 30 seconds
            setInterval(updateDashboardStats, 30000);
            setInterval(updateRecentActivity, 60000);
            
            // Add visual indicator for real-time updates
            const updateIndicator = document.createElement('div');
            updateIndicator.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg z-50';
            updateIndicator.innerHTML = '🟢 Live Updates';
            document.body.appendChild(updateIndicator);
            
            // Hide indicator after 3 seconds
            setTimeout(() => {
                updateIndicator.style.opacity = '0';
                setTimeout(() => {
                    updateIndicator.remove();
                }, 500);
            }, 3000);
        });
    </script>
    @endif

    @if(Auth::user()->role === 'student')
    <!-- Real-time Student Dashboard Updates -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to refresh student dashboard data
            function refreshStudentDashboard() {
                // Reload the page to get fresh data
                window.location.reload();
            }
            
            // Auto-refresh every 2 minutes for students
            setInterval(refreshStudentDashboard, 120000);
            
            // Add manual refresh button functionality if it exists
            const refreshButtons = document.querySelectorAll('[data-refresh="dashboard"]');
            refreshButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<svg class="w-4 h-4 animate-spin inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Refreshing...';
                    this.disabled = true;
                    
                    setTimeout(() => {
                        refreshStudentDashboard();
                    }, 1000);
                });
            });
            
            // Show live update indicator
            const updateIndicator = document.createElement('div');
            updateIndicator.className = 'fixed bottom-4 right-4 bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg z-50';
            updateIndicator.innerHTML = '🔄 Auto-refresh enabled';
            document.body.appendChild(updateIndicator);
            
            // Hide indicator after 3 seconds
            setTimeout(() => {
                updateIndicator.style.opacity = '0';
                setTimeout(() => {
                    updateIndicator.remove();
                }, 500);
            }, 3000);
        });
    </script>
    @endif

    @if(Auth::user()->role === 'student')
    <!-- Defense Notification JavaScript -->
    <script>
        // Function to view defense details
        function viewDefenseDetails(assignmentId) {
            // Redirect to defense details page or show modal
            window.location.href = `/student/thesis/defense?assignment=${assignmentId}`;
        }

        // Function to dismiss defense notification
        function dismissDefenseNotification() {
            const notification = document.getElementById('defenseNotification');
            if (notification) {
                // Add fade out animation
                notification.style.transition = 'opacity 0.3s ease-out';
                notification.style.opacity = '0';
                
                // Remove from DOM after animation
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }

        // Auto-dismiss notification after 30 seconds if not interacted with
        document.addEventListener('DOMContentLoaded', function() {
            const defenseNotification = document.getElementById('defenseNotification');
            if (defenseNotification) {
                // Add pulsing animation to draw attention
                defenseNotification.style.animation = 'pulse 2s infinite';
                
                // Auto-dismiss after 30 seconds
                setTimeout(() => {
                    if (defenseNotification && defenseNotification.parentNode) {
                        dismissDefenseNotification();
                    }
                }, 30000);
            }
        });
    </script>
    @endif
</x-app-layout>
