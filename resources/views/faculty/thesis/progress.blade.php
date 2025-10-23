<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📊 {{ __('Track Thesis Progress') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters and Search -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-8 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                        </svg>
                    </div>
                    Filter Student Progress
                </h3>

                <form method="GET" action="{{ route('faculty.thesis.progress') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Student Search -->
                    <div>
                        <label for="student_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Students</label>
                        <input type="text" name="student_search" id="student_search" value="{{ request('student_search') }}" 
                               placeholder="Search by name or email"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-900 dark:text-white">
                    </div>

                    <!-- Progress Filter -->
                    <div>
                        <label for="progress_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Progress Stage</label>
                        <select name="progress_filter" id="progress_filter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-900 dark:text-white">
                            <option value="">All Students</option>
                            <option value="proposal_submitted" {{ request('progress_filter') === 'proposal_submitted' ? 'selected' : '' }}>Proposal Submitted</option>
                            <option value="panel_assigned" {{ request('progress_filter') === 'panel_assigned' ? 'selected' : '' }}>Panel Assigned</option>
                            <option value="final_defense" {{ request('progress_filter') === 'final_defense' ? 'selected' : '' }}>Final Defense</option>
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex items-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>
                        <a href="{{ route('faculty.thesis.progress') }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Student Progress Grid -->
            @if($students->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($students as $student)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                            <!-- Student Header -->
                            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-600/50 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $student->name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $student->email }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($student->progress['percentage'], 0) }}%</div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Complete</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <!-- Progress Bar -->
                                <div class="mb-6">
                                    <div class="flex justify-between text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span>Thesis Progress</span>
                                        <span>{{ $student->progress['completed_stages'] }}/{{ $student->progress['total_stages'] }} stages</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-300" 
                                             style="width: {{ $student->progress['percentage'] }}%"></div>
                                    </div>
                                </div>

                                <!-- Current Stage -->
                                @if($student->progress['current_stage'])
                                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                                        <div class="flex items-center">
                                            <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                                <svg class="w-4 h-4 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Current Stage</p>
                                                <p class="text-sm text-blue-600 dark:text-blue-300">{{ $student->progress['stage_labels'][$student->progress['current_stage']] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Progress Timeline -->
                                <div class="space-y-4">
                                    <h4 class="font-medium text-gray-900 dark:text-white">Progress Timeline</h4>
                                    
                                    @foreach($student->progress['status_details'] as $stage => $details)
                                        <div class="flex items-start gap-3">
                                            <!-- Status Indicator -->
                                            <div class="flex-shrink-0 mt-1">
                                                @if($details['status'] === 'approved')
                                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                                @elseif(in_array($details['status'], ['pending', 'under_review', 'returned_for_revision']))
                                                    <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                                                @else
                                                    <div class="w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                                                @endif
                                            </div>

                                            <!-- Stage Content -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium {{ $details['status'] === 'approved' ? 'text-gray-900 dark:text-white' : ($details['submitted'] ? 'text-gray-700 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400') }}">
                                                        {{ $student->progress['stage_labels'][$stage] }}
                                                    </p>
                                                    
                                                    @if($details['status'] === 'approved')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                                                            ✅ Completed
                                                        </span>
                                                    @elseif($details['status'] === 'pending')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                                                            ⏳ Pending Review
                                                        </span>
                                                    @elseif($details['status'] === 'under_review')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">
                                                            🔍 Under Review
                                                        </span>
                                                    @elseif($details['status'] === 'returned_for_revision')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-200">
                                                            📝 Needs Revision
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-200">
                                                            ⭕ Not Submitted
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                @if($details['date'])
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ \Carbon\Carbon::parse($details['date'])->format('M j, Y') }}
                                                    </p>
                                                @endif

                                                @if($details['document'] && in_array($details['status'], ['pending', 'under_review']))
                                                    <div class="mt-2">
                                                        <a href="{{ route('faculty.thesis.show', $details['document']) }}" 
                                                           class="inline-flex items-center text-xs bg-blue-100 hover:bg-blue-200 text-blue-600 px-2 py-1 rounded transition-colors duration-200">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                            Review Document
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-3 mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                                    @php
                                        $pendingDocs = collect($student->progress['status_details'])->filter(function($details) {
                                            return in_array($details['status'], ['pending', 'under_review']);
                                        });
                                    @endphp
                                    
                                    @if($pendingDocs->count() > 0)
                                        <a href="{{ route('faculty.thesis.reviews') }}?student_search={{ urlencode($student->name) }}" 
                                           class="flex-1 bg-red-600 hover:bg-red-700 text-gray-900 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 text-center">
                                            Review Documents ({{ $pendingDocs->count() }})
                                        </a>
                                    @else
                                        <div class="flex-1 bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 px-4 py-2 rounded-lg text-sm font-medium text-center">
                                            ✅ All Documents Reviewed
                                        </div>
                                    @endif
                                    
                                    <button class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                        Contact Student
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($students->hasPages())
                    <div class="mt-8">
                        {{ $students->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 text-center shadow-sm">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No students found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">No students match your current filter criteria, or no students have thesis documents yet.</p>
                    <a href="{{ route('faculty.thesis.reviews') }}" 
                       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Review Thesis Documents
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 
