<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📄 {{ __('Document Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('student.thesis.history') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to History
                </a>
            </div>

            <!-- Document Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $document->title }}</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Document ID: {{ $document->id }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Document Type Badge -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($document->document_type === 'proposal') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                @elseif($document->document_type === 'approval_sheet') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                @elseif($document->document_type === 'panel_assignment') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                                @else bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200 @endif">
                                {{ $document->document_type_label }}
                            </span>
                            
                            <!-- Status Badge -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($document->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                                @elseif($document->status === 'under_review') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                @elseif($document->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                @else bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 @endif">
                                @if($document->status === 'pending') ⏳ Pending Review
                                @elseif($document->status === 'under_review') 📋 Under Review
                                @elseif($document->status === 'approved') ✅ Approved
                                @else ❌ Needs Revision @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Document Info Grid -->
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Student ID</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $document->student_id }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Course/Program</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $document->course_program ?? 'Not specified' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Adviser</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $document->adviser_name ?? 'Not specified' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Submission Date</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $document->submission_date->format('F j, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Document Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Document Details</h3>
                            <div class="space-y-3">
                                @if($document->description)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $document->description }}</p>
                                    </div>
                                @endif
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $document->created_at->format('F j, Y \a\t g:i A') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $document->updated_at->format('F j, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Files Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Files</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Files</label>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $document->uploaded_files ? count($document->uploaded_files) : 0 }} file(s)
                                    </p>
                                </div>
                                @if($document->uploaded_files && count($document->uploaded_files) > 0)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Size</label>
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ number_format(array_sum(array_column($document->uploaded_files, 'size')) / 1024 / 1024, 2) }} MB
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!-- Individual Faculty Approvals -->
<x-individual-approval-status :document="$document" />

            <!-- Document Metadata -->
            @if($document->document_metadata && count($document->document_metadata) > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Document Metadata</h3>
                    </div>
                    <div class="px-6 py-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($document->document_metadata as $key => $value)
                                @if($value && !is_array($value))
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 capitalize">
                                            {{ str_replace('_', ' ', $key) }}
                                        </label>
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            @if($key === 'keywords' && is_array($value))
                                                {{ implode(', ', $value) }}
                                            @elseif($key === 'expected_start_date' || $key === 'expected_completion_date' || $key === 'plagiarism_check_date')
                                                {{ \Carbon\Carbon::parse($value)->format('F j, Y') }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Uploaded Files -->
            @if($document->uploaded_files && count($document->uploaded_files) > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Uploaded Files</h3>
                    </div>
                    <div class="px-6 py-5">
                        <div class="space-y-4">
                            @foreach($document->uploaded_files as $index => $file)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <div class="flex items-center space-x-4">
                                        <!-- File Icon -->
                                        <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        
                                        <!-- File Info -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $file['original_name'] }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ number_format($file['size'] / 1024 / 1024, 2) }} MB • 
                                                @if(isset($file['uploaded_at']))
                                                    {{ \Carbon\Carbon::parse($file['uploaded_at'])->format('M j, Y g:i A') }}
                                                @else
                                                    {{ $document->submission_date->format('M j, Y g:i A') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Download Button -->
                                    <a href="{{ route('student.thesis.download', ['document' => $document, 'fileIndex' => $index]) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Review Feedback -->
            @if($document->review_comments)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Review Feedback</h3>
                            @if($document->status === 'returned_for_revision')
                                <a href="{{ route('student.thesis.edit', $document) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit Document
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <div class="space-y-4">
                            <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="text-sm font-medium text-red-800 dark:text-red-300">
                                            {{ $document->reviewer ? $document->reviewer->name : 'Reviewer' }}
                                        </h4>
                                        @if($document->reviewed_at)
                                            <p class="text-xs text-red-600 dark:text-red-400">
                                                {{ $document->reviewed_at->format('F j, Y g:i A') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-sm text-red-700 dark:text-red-300">{{ $document->review_comments }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
