<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                {{ __('Review Thesis Submissions') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg mb-4">
                    <svg class="w-8 h-8 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 00.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    Thesis Review Center
                </h1>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Review and provide feedback on student thesis submissions. Monitor progress and guide research development.
                </p>
            </div>

            <!-- Success Messages -->
            @if(session('success'))
                <div class="mb-8 p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-xl shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-500 rounded-lg shadow-sm mr-3">
                            <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-green-800 dark:text-green-300 font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Pending -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_pending'] }}</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Total Pending</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Awaiting review</p>
                    </div>
                </div>

                <!-- Urgent Reviews -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['urgent_reviews'] }}</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Urgent Reviews</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Due this week</p>
                    </div>
                </div>

                <!-- Under Review -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['under_review'] }}</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Under Review</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">In progress</p>
                    </div>
                </div>

                <!-- This Week -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['this_week'] }}</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">This Week</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">New submissions</p>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-gray-500 rounded-lg shadow-sm">
                            <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Filters & Search</h3>
                    </div>
                </div>
                
                <form method="GET" action="{{ route('faculty.thesis.reviews') }}" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Document Type Filter -->
                        <div>
                            <label for="document_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Document Type</label>
                            <select name="document_type" id="document_type" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">All Types</option>
                                <option value="proposal" {{ request('document_type') === 'proposal' ? 'selected' : '' }}>Proposal</option>
                                <option value="approval_sheet" {{ request('document_type') === 'approval_sheet' ? 'selected' : '' }}>Approval Sheet</option>
                                <option value="panel_assignment" {{ request('document_type') === 'panel_assignment' ? 'selected' : '' }}>Panel Assignment</option>
                                <option value="final_manuscript" {{ request('document_type') === 'final_manuscript' ? 'selected' : '' }}>Final Manuscript</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="returned_for_revision" {{ request('status') === 'returned_for_revision' ? 'selected' : '' }}>Returned for Revision</option>
                            </select>
                        </div>

                        <!-- Student Search -->
                        <div>
                            <label for="student_search" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Search Student</label>
                            <input type="text" name="student_search" id="student_search" value="{{ request('student_search') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                   placeholder="Name, ID, or thesis title">
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex items-end space-x-3">
                            <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md border-2 border-blue-600 hover:border-blue-700">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search
                            </button>
                            <a href="{{ route('faculty.thesis.reviews') }}" class="px-4 py-3 bg-gray-600 hover:bg-gray-700 text-gray-900 dark:text-white font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md border-2 border-gray-600 hover:border-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Submissions List -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/30 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-500 rounded-lg shadow-sm">
                                <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                Thesis Submissions ({{ $thesisDocuments->total() }})
                            </h3>
                        </div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Page {{ $thesisDocuments->currentPage() }} of {{ $thesisDocuments->lastPage() }}
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($thesisDocuments->count() > 0)
                        <div class="space-y-4">
                            @foreach($thesisDocuments as $document)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 p-6 hover:shadow-lg transition-all duration-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4 flex-1">
                                            <!-- Document Type Icon -->
                                            <div class="p-3 rounded-xl shadow-sm flex-shrink-0
                                                @if($document->document_type === 'proposal') bg-blue-100 dark:bg-blue-900/30
                                                @elseif($document->document_type === 'approval_sheet') bg-green-100 dark:bg-green-900/30
                                                @elseif($document->document_type === 'panel_assignment') bg-orange-100 dark:bg-orange-900/30
                                                @else bg-purple-100 dark:bg-purple-900/30 @endif">
                                                @if($document->document_type === 'proposal')
                                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 00.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                @elseif($document->document_type === 'approval_sheet')
                                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @elseif($document->document_type === 'panel_assignment')
                                                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.168 18.477 18.754 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                @endif
                                            </div>

                                            <!-- Document Details -->
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $document->title }}</h4>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                                    <div>
                                                        <p class="text-gray-600 dark:text-gray-400 font-medium">Student</p>
                                                        <p class="text-gray-900 dark:text-white font-semibold">{{ $document->user->name }}</p>
                                                        <p class="text-gray-500 dark:text-gray-500">{{ $document->student_id }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-600 dark:text-gray-400 font-medium">Document Type</p>
                                                        <p class="text-gray-900 dark:text-white font-semibold">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</p>
                                                        <p class="text-gray-500 dark:text-gray-500">{{ $document->submission_date->format('M j, Y') }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-600 dark:text-gray-400 font-medium">Status</p>
                                                        <div class="flex items-center space-x-2 mt-1">
                                                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                                                @if($document->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                                @elseif($document->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                                @elseif($document->status === 'under_review') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                                @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                                                                {{ ucfirst(str_replace('_', ' ', $document->status)) }}
                                                            </span>
                                                        </div>
                                                        <p class="text-gray-500 dark:text-gray-500 mt-1">{{ $document->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex flex-col space-y-2 ml-4 flex-shrink-0">
                                            <a href="{{ route('faculty.thesis.show', $document) }}" 
                                               class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-gray-900 dark:text-white rounded-lg font-semibold text-sm transition-all duration-200 shadow-sm hover:shadow-md">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Review
                                            </a>
                                            @if($document->status === 'pending')
                                                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-lg font-semibold text-sm text-center border border-yellow-200 dark:border-yellow-700">
                                                    Awaiting Review
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $thesisDocuments->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-2xl w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Submissions Found</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                @if(request()->hasAny(['document_type', 'status', 'student_search']))
                                    No thesis submissions match your current filters. Try adjusting your search criteria.
                                @else
                                    No thesis submissions are currently available for review.
                                @endif
                            </p>
                            @if(request()->hasAny(['document_type', 'status', 'student_search']))
                                <a href="{{ route('faculty.thesis.reviews') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
