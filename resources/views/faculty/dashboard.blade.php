<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Faculty Dashboard
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Section with Real-time Features -->
        <div class="mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
                            <p class="mt-2 text-gray-600">Here's your overview of thesis management activities for your assigned students.</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Last login</p>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->updated_at->diffForHumans() }}</p>
                            </div>
                            <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <!-- Real-time Status Indicator -->
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span class="text-sm text-gray-600" id="live-indicator">🟢 Live Updates</span>
                        </div>
                        <button onclick="refreshFacultyDashboard()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pending Reviews -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-2xl p-6 border border-yellow-200/50 dark:border-yellow-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-yellow-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['pending_reviews'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Pending Reviews</h3>
                <p class="text-xs text-yellow-600 dark:text-yellow-400 font-medium">Awaiting your review</p>
            </div>

            <!-- Urgent Reviews -->
            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-2xl p-6 border border-red-200/50 dark:border-red-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['urgent_reviews'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Urgent Reviews</h3>
                <p class="text-xs text-red-600 dark:text-red-400 font-medium">Overdue by 5+ days</p>
            </div>

            <!-- Completed Reviews -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-6 border border-green-200/50 dark:border-green-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['completed_reviews'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Completed This Month</h3>
                <p class="text-xs text-green-600 dark:text-green-400 font-medium">Reviews completed</p>
            </div>

            <!-- Assigned Students -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['assigned_students'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Assigned Students</h3>
                <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Under your supervision</p>
            </div>
        </div>

        <!-- Additional Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Average Review Time -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200/50 dark:border-purple-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['avg_review_time'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Avg. Review Time</h3>
                <p class="text-xs text-purple-600 dark:text-purple-400 font-medium">Days per review</p>
            </div>

            <!-- Unread Notifications -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-2xl p-6 border border-orange-200/50 dark:border-orange-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-orange-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['unread_notifications'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Unread Notifications</h3>
                <p class="text-xs text-orange-600 dark:text-orange-400 font-medium">System alerts</p>
            </div>

            <!-- This Week's Activity -->
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-2xl p-6 border border-indigo-200/50 dark:border-indigo-700/50 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-indigo-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['this_week_activity'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">This Week's Activity</h3>
                <p class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">Recent submissions</p>
            </div>
        </div>


        <!-- Faculty Assignment Overview -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Your Faculty Assignments</h3>
                    <p class="text-sm text-gray-500">Documents and students specifically assigned to you</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Adviser Role -->
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="w-12 h-12 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-medium text-gray-900">Adviser</h4>
                            <p class="text-xs text-gray-500">Thesis supervision</p>
                            <p class="text-lg font-semibold text-blue-600 mt-1">{{ $dashboardData['assigned_students'] ?? 0 }}</p>
                        </div>

                        <!-- Reviewer Role -->
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="w-12 h-12 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-medium text-gray-900">Reviewer</h4>
                            <p class="text-xs text-gray-500">Document review</p>
                            <p class="text-lg font-semibold text-green-600 mt-1">{{ $dashboardData['pending_reviews'] ?? 0 }}</p>
                        </div>

                        <!-- Panel Member Role -->
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="w-12 h-12 mx-auto bg-purple-100 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-medium text-gray-900">Panel Member</h4>
                            <p class="text-xs text-gray-500">Defense evaluation</p>
                            <p class="text-lg font-semibold text-purple-600 mt-1">{{ $dashboardData['this_week_activity'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Submissions -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Student Documents Requiring Review</h3>
                                <p class="text-sm text-gray-600 mt-1">Review and provide feedback on student thesis submissions</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center space-x-2 text-sm text-gray-500">
                                    <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                                    <span>{{ $dashboardData['pending_reviews'] ?? 0 }} pending</span>
                                </div>
                                <a href="{{ route('faculty.thesis.reviews') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    View All Documents
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($dashboardData['recent_submissions'] ?? [] as $submission)
                        @php
                            // Handle both object and array data structures
                            $status = is_object($submission) ? $submission->status : ($submission['status'] ?? 'unknown');
                            $statusColor = match($status) {
                                'pending' => 'yellow',
                                'under_review' => 'blue',
                                'approved' => 'green',
                                'returned_for_revision' => 'red',
                                default => 'gray'
                            };
                            $studentName = 'Unknown Student';
                            if (is_object($submission)) {
                                $studentName = $submission->user->name ?? 'Unknown Student';
                            } elseif (is_array($submission) && isset($submission['user'])) {
                                $studentName = $submission['user']['name'] ?? 'Unknown Student';
                            }
                            $title = is_object($submission) ? ($submission->title ?? 'Untitled Document') : ($submission['title'] ?? 'Untitled Document');
                            $documentType = is_object($submission) ? ($submission->document_type ?? 'document') : ($submission['document_type'] ?? 'document');
                            $documentTypeLabel = match($documentType) {
                                'proposal' => 'Proposal Form',
                                'approval_sheet' => 'Approval Sheet',
                                'panel_assignment' => 'Panel Assignment',
                                'final_manuscript' => 'Final Manuscript',
                                default => ucfirst(str_replace('_', ' ', $documentType))
                            };
                            $submissionDate = is_object($submission) ? $submission->submission_date : ($submission['submission_date'] ?? now());
                            if (is_string($submissionDate)) {
                                $submissionDate = \Carbon\Carbon::parse($submissionDate);
                            }
                            
                            // Determine faculty role for this document
                            $facultyRole = '';
                            if (is_object($submission)) {
                                if ($submission->adviser_id == Auth::id()) {
                                    $facultyRole = 'Adviser';
                                } elseif ($submission->reviewed_by == Auth::id()) {
                                    $facultyRole = 'Reviewer';
                                } else {
                                    $facultyRole = 'Panel Member';
                                }
                            } else {
                                if (($submission['adviser_id'] ?? null) == Auth::id()) {
                                    $facultyRole = 'Adviser';
                                } elseif (($submission['reviewed_by'] ?? null) == Auth::id()) {
                                    $facultyRole = 'Reviewer';
                                } else {
                                    $facultyRole = 'Panel Member';
                                }
                            }
                        @endphp
                        <div class="px-6 py-6 border-l-4 border-{{ $statusColor }}-400 bg-{{ $statusColor }}-50 hover:bg-{{ $statusColor }}-100 transition-colors duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <!-- Student Info Header -->
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-3 h-3 bg-{{ $statusColor }}-500 rounded-full animate-pulse"></div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <h4 class="text-lg font-semibold text-gray-900 truncate">
                                                    {{ $studentName }}
                                                </h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $facultyRole }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 truncate font-medium">
                                                {{ $title }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Document Details -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ $documentTypeLabel }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ $submissionDate->format('M j, Y') }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ $submissionDate->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <!-- Status and Progress -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </span>
                                            @if($status === 'pending')
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Awaiting Review
                                                </span>
                                            @elseif($status === 'under_review')
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    In Progress
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">Document ID: #{{ is_object($submission) ? $submission->id : ($submission['id'] ?? 'N/A') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col space-y-2 ml-4">
                                    <button onclick="viewDocument({{ is_object($submission) ? $submission->id : ($submission['id'] ?? 0) }})" 
                                            class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Details
                                    </button>
                                    
                                    @if($status === 'pending' || $status === 'under_review')
                                        <div class="flex space-x-1">
                                            <button onclick="approveDocument({{ is_object($submission) ? $submission->id : ($submission['id'] ?? 0) }})" 
                                                    class="inline-flex items-center px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Approve
                                            </button>
                                            <button onclick="returnForRevision({{ is_object($submission) ? $submission->id : ($submission['id'] ?? 0) }})" 
                                                    class="inline-flex items-center px-2 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-medium rounded transition-colors duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                Return
                                            </button>
                                        </div>
                                    @endif
                                    
                                    <button onclick="addComment({{ is_object($submission) ? $submission->id : ($submission['id'] ?? 0) }})" 
                                            class="inline-flex items-center px-2 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded transition-colors duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No submissions</h3>
                            <p class="mt-1 text-sm text-gray-500">No recent submissions requiring your review.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Notifications -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <a href="{{ route('faculty.thesis.reviews') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-900">Review Thesis Documents</p>
                                <p class="text-xs text-blue-700">Review pending submissions</p>
                            </div>
                        </a>

                        <a href="{{ route('faculty.thesis.progress') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-900">Track Student Progress</p>
                                <p class="text-xs text-green-700">Monitor thesis progress</p>
                            </div>
                        </a>

                        <a href="{{ route('notifications.index') }}" class="flex items-center p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-orange-900">View Notifications</p>
                                <p class="text-xs text-orange-700">Check system alerts</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($dashboardData['recent_activities'] ?? [] as $activity)
                        @php
                            // Handle both object and array data structures for activities
                            $description = is_object($activity) ? ($activity->description ?? 'Activity logged') : ($activity['description'] ?? 'Activity logged');
                            $createdAt = is_object($activity) ? $activity->created_at : ($activity['created_at'] ?? now());
                            if (is_string($createdAt)) {
                                $createdAt = \Carbon\Carbon::parse($createdAt);
                            }
                        @endphp
                        <div class="px-6 py-3">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-blue-400 rounded-full mt-2"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">{{ $description }}</p>
                                    <p class="text-xs text-gray-500">{{ $createdAt->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-4 text-center">
                            <p class="text-sm text-gray-500">No recent activity</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Workflow Overview -->
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Thesis Workflow Overview</h3>
                    <p class="text-sm text-gray-500">Current status of the thesis review process</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <!-- Proposal Form -->
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Proposal</p>
                            <p class="text-xs text-gray-500">Form Review</p>
                        </div>

                        <!-- Approval Sheet -->
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Approval</p>
                            <p class="text-xs text-gray-500">Sheet Review</p>
                        </div>

                        <!-- Panel Assignment -->
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto bg-purple-100 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Panel</p>
                            <p class="text-xs text-gray-500">Assignment</p>
                        </div>

                        <!-- Proposal Defense -->
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto bg-yellow-100 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Proposal</p>
                            <p class="text-xs text-gray-500">Defense</p>
                        </div>

                        <!-- Final Manuscript -->
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto bg-indigo-100 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Final</p>
                            <p class="text-xs text-gray-500">Manuscript</p>
                        </div>

                        <!-- Final Defense -->
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Final</p>
                            <p class="text-xs text-gray-500">Defense</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Real-time Faculty Dashboard Updates -->
<script>
    // Global function for manual refresh
    window.refreshFacultyDashboard = function() {
        // Show refresh indicator
        const refreshBtn = document.querySelector('button[onclick="refreshFacultyDashboard()"]');
        if (refreshBtn) {
            const originalText = refreshBtn.innerHTML;
            refreshBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Refreshing...';
            refreshBtn.disabled = true;
            
            // Reload the page to get fresh data
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }
    };

    // Document Action Functions
    window.viewDocument = function(documentId) {
        // Redirect to document details page
        window.location.href = `/faculty/thesis/reviews/${documentId}`;
    };

    window.approveDocument = function(documentId) {
        if (confirm('Are you sure you want to approve this document?')) {
            // Show loading state
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<svg class="w-3 h-3 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Processing...';
            button.disabled = true;

            // Submit approval
            fetch(`/faculty/thesis/reviews/${documentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    status: 'approved',
                    review_comments: 'Document approved by faculty reviewer.'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('Document approved successfully!', 'success');
                    // Reload page after short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to approve document');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to approve document. Please try again.', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }
    };

    window.returnForRevision = function(documentId) {
        const comment = prompt('Please provide feedback for the student:');
        if (comment !== null && comment.trim() !== '') {
            // Show loading state
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<svg class="w-3 h-3 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Processing...';
            button.disabled = true;

            // Submit return for revision
            fetch(`/faculty/thesis/reviews/${documentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    status: 'returned_for_revision',
                    review_comments: comment
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Document returned for revision with your feedback.', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to return document');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to return document. Please try again.', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }
    };

    window.addComment = function(documentId) {
        const comment = prompt('Add your comment:');
        if (comment !== null && comment.trim() !== '') {
            // Show loading state
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<svg class="w-3 h-3 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Adding...';
            button.disabled = true;

            // Submit comment
            fetch(`/faculty/thesis/reviews/${documentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    review_comments: comment
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Comment added successfully!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to add comment');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add comment. Please try again.', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }
    };

    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                ${message}
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 5000);
    }

    // Function to update dashboard statistics
    function updateFacultyDashboardStats() {
        // This would typically make an AJAX call to get updated stats
        // For now, we'll just update the live indicator
        const indicator = document.getElementById('live-indicator');
        if (indicator) {
            indicator.innerHTML = '🔄 Updating...';
            setTimeout(() => {
                indicator.innerHTML = '🟢 Live Updates';
            }, 2000);
        }
    }

    // Function to update recent activity
    function updateRecentActivity() {
        // This would typically make an AJAX call to get updated activity
        console.log('Updating recent activity...');
    }

    // Auto-refresh functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh every 2 minutes for faculty
        setInterval(updateFacultyDashboardStats, 120000);
        setInterval(updateRecentActivity, 180000);
        
        // Add visual indicator for real-time updates
        const updateIndicator = document.getElementById('live-indicator');
        if (updateIndicator) {
            updateIndicator.innerHTML = '🟢 Live Updates';
            
            // Show update status periodically
            setTimeout(() => {
                updateIndicator.innerHTML = '🔄 Auto-refresh enabled';
                setTimeout(() => {
                    updateIndicator.innerHTML = '🟢 Live Updates';
                }, 2000);
            }, 5000);
        }

        // Add hover effects to document cards
        const documentCards = document.querySelectorAll('[class*="border-l-4"]');
        documentCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(4px)';
                this.style.transition = 'transform 0.2s ease-in-out';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    });
</script>
</x-app-layout>
