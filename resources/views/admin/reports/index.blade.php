<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📊 {{ __('Reports & Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- System Overview Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-4 text-xs">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">Students</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $stats['active_students'] ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">Faculty</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $stats['active_faculty'] ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">Admins</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $stats['admins'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Submissions -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-6 border border-green-200/50 dark:border-green-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Total Submissions</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ ($stats['total_forms'] ?? 0) + ($stats['total_documents'] ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-green-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">Forms</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $stats['total_forms'] ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">Documents</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $stats['total_documents'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Approval Rate -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-2xl p-6 border border-yellow-200/50 dark:border-yellow-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Approval Rate</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                @php
                                    $totalSubmissions = ($stats['total_forms'] ?? 0) + ($stats['total_documents'] ?? 0);
                                    $approvedSubmissions = ($stats['approved_forms'] ?? 0) + ($stats['approved_documents'] ?? 0);
                                    $approvalRate = $totalSubmissions > 0 ? round(($approvedSubmissions / $totalSubmissions) * 100, 1) : 0;
                                @endphp
                                {{ $approvalRate }}%
                            </p>
                        </div>
                        <div class="p-3 bg-yellow-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">Approved</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $approvedSubmissions }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">Pending</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ ($stats['pending_forms'] ?? 0) + ($stats['pending_documents'] ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                <!-- System Health -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200/50 dark:border-purple-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">System Health</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                @php
                                    $overdueCount = ($stats['overdue_forms'] ?? 0) + ($stats['overdue_documents'] ?? 0);
                                    $healthScore = $overdueCount > 10 ? 'Poor' : ($overdueCount > 5 ? 'Fair' : 'Good');
                                @endphp
                                {{ $healthScore }}
                            </p>
                        </div>
                        <div class="p-3 bg-purple-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">Overdue</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $overdueCount }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">Active</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $stats['active_users'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Categories -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Submission Analytics -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">📈 Submission Analytics</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track submission trends and patterns</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <a href="{{ route('admin.reports.submission-rates') }}" 
                           class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-500 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Submission Rates</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Monthly and yearly submission trends</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <a href="{{ route('admin.reports.overdue-documents') }}" 
                           class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/20 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-red-500 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Overdue Documents</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Track pending and overdue submissions</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <a href="{{ route('admin.reports.approval-trends') }}" 
                           class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-green-500 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Approval Trends</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Approval rates and processing times</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- User & Performance Analytics -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">👥 User & Performance Analytics</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Monitor user activity and performance</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <a href="{{ route('admin.reports.user-activity') }}" 
                           class="flex items-center justify-between p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-purple-500 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">User Activity</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">User engagement and activity patterns</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <a href="{{ route('admin.reports.faculty-performance') }}" 
                           class="flex items-center justify-between p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-indigo-500 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Faculty Performance</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Review faculty efficiency and workload</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <a href="{{ route('admin.reports.export-pdf') }}" 
                           class="flex items-center justify-between p-4 bg-orange-50 dark:bg-orange-900/20 rounded-xl hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-orange-500 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Export Reports</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Download reports in PDF format</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">⚡ Quick Actions</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Common administrative tasks</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.users') }}" 
                           class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                            <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Manage Users</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Add, edit, or remove users</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.records') }}" 
                           class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                            <div class="p-2 bg-green-500 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">View Records</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Browse all submissions</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.panel') }}" 
                           class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                            <div class="p-2 bg-purple-500 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Panel Management</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Assign thesis panels</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
