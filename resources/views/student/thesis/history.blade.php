<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📊 {{ __('Thesis Documents History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('student.thesis.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Thesis Documents
                </a>
            </div>

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

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Documents -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Documents</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $documents->total() }}</p>
                        </div>
                        <div class="p-3 bg-blue-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-2xl p-6 border border-yellow-200/50 dark:border-yellow-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Pending</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $documents->where('status', 'pending')->count() }}</p>
                        </div>
                        <div class="p-3 bg-yellow-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-6 border border-green-200/50 dark:border-green-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Approved</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $documents->where('status', 'approved')->count() }}</p>
                        </div>
                        <div class="p-3 bg-green-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Success Rate -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200/50 dark:border-purple-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Success Rate</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                @if($documents->total() > 0)
                                    {{ number_format(($documents->where('status', 'approved')->count() / $documents->total()) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </p>
                        </div>
                        <div class="p-3 bg-purple-500 rounded-xl">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                        </svg>
                    </div>
                    Filter & Search
                </h3>

                <form method="GET" action="{{ route('student.thesis.history') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Document Type Filter -->
                    <div>
                        <label for="document_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Document Type</label>
                        <select name="document_type" id="document_type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-900 dark:text-white">
                            <option value="">All Types</option>
                            <option value="proposal" {{ request('document_type') === 'proposal' ? 'selected' : '' }}>Proposal Form</option>
                            <option value="approval_sheet" {{ request('document_type') === 'approval_sheet' ? 'selected' : '' }}>Approval Sheet</option>
                            <option value="panel_assignment" {{ request('document_type') === 'panel_assignment' ? 'selected' : '' }}>Panel Assignment</option>
                            <option value="final_manuscript" {{ request('document_type') === 'final_manuscript' ? 'selected' : '' }}>Final Manuscript</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-900 dark:text-white">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="returned_for_revision" {{ request('status') === 'returned_for_revision' ? 'selected' : '' }}>Returned for Revision</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-900 dark:text-white">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-900 dark:text-white">
                    </div>

                    <!-- Filter Buttons -->
                    <div class="md:col-span-4 flex gap-3 pt-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                            </svg>
                            Apply Filters
                        </button>
                        <a href="{{ route('student.thesis.history') }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Documents List -->
            @if($documents->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Table Header -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Your Thesis Document Submissions</h3>
                    </div>

                    <!-- Table Content -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Document</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Submitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Files</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($documents as $document)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                        <!-- Document Info -->
                                        <td class="px-6 py-4">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $document->title }}</div>
                                                @if($document->description)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                                        {{ Str::limit($document->description, 50) }}
                                                    </div>
                                                @endif
                                                <div class="text-xs text-gray-400">ID: {{ $document->student_id }}</div>
                                            </div>
                                        </td>

                                        <!-- Document Type -->
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($document->document_type === 'proposal') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                                @elseif($document->document_type === 'approval_sheet') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                                @elseif($document->document_type === 'panel_assignment') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                                                @else bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200 @endif">
                                                {{ $document->document_type_label }}
                                            </span>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($document->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                                                @elseif($document->status === 'under_review') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                                @elseif($document->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 @endif">
                                                @if($document->status === 'pending') ⏳ Pending Review
                                                @elseif($document->status === 'under_review') 📋 Under Review
                                                @elseif($document->status === 'approved') ✅ Approved
                                                @else ❌ Needs Revision @endif
                                            </span>
                                        </td>

                                        <!-- Submission Date -->
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            <div>{{ $document->submission_date->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $document->created_at->diffForHumans() }}</div>
                                        </td>

                                        <!-- Files -->
                                        <td class="px-6 py-4">
                                            @if($document->uploaded_files && count($document->uploaded_files) > 0)
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                    </svg>
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ count($document->uploaded_files) }} file(s)</span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400">No files</span>
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('student.thesis.show', $document) }}" 
                                                   class="bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-200 dark:hover:bg-blue-800/50 transition-colors duration-200">
                                                    View Details
                                                </a>
                                                @if($document->status === 'returned_for_revision')
                                                    <a href="{{ route('student.thesis.edit', $document) }}" 
                                                       class="bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-emerald-200 dark:hover:bg-emerald-800/50 transition-colors duration-200">
                                                        Edit Document
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($documents->hasPages())
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                            {{ $documents->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No documents found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">You haven't submitted any thesis documents yet.</p>
                    <a href="{{ route('student.thesis.index') }}" 
                       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Submit Your First Document
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 
