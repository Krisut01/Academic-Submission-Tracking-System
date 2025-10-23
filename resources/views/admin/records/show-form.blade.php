<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📋 {{ __('Academic Form Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.records') }}?tab=academic_forms" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Records
                </a>
            </div>

            <!-- Form Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Academic Form #{{ $form->id }}</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Submitted by {{ $form->user->name }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Form Type Badge -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($form->form_type === 'registration') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                @elseif($form->form_type === 'clearance') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200 @endif">
                                {{ ucfirst($form->form_type) }}
                            </span>
                            
                            <!-- Status Badge -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($form->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                                @elseif($form->status === 'under_review') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                @elseif($form->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                @else bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 @endif">
                                {{ ucfirst(str_replace('_', ' ', $form->status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Form Info Grid -->
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Student Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Student Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Student Name</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $form->user->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $form->user->email }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Student ID</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $form->student_id ?? 'Not specified' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Course/Program</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $form->course_program ?? 'Not specified' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Form Details</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Form Type</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ ucfirst($form->form_type) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $form->status)) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Submission Date</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $form->submission_date->format('F j, Y') }}</p>
                                </div>
                                @if($form->reviewed_at)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Reviewed Date</label>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $form->reviewed_at->format('F j, Y') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Files Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Files</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Files</label>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $form->uploaded_files ? count($form->uploaded_files) : 0 }} file(s)
                                    </p>
                                </div>
                                @if($form->uploaded_files && count($form->uploaded_files) > 0)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Size</label>
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ number_format(array_sum(array_column($form->uploaded_files, 'size')) / 1024 / 1024, 2) }} MB
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Metadata -->
            @if($form->form_metadata && count($form->form_metadata) > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Form Metadata</h3>
                    </div>
                    <div class="px-6 py-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($form->form_metadata as $key => $value)
                                @if($value && !is_array($value))
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 capitalize">
                                            {{ str_replace('_', ' ', $key) }}
                                        </label>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $value }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Uploaded Files -->
            @if($form->uploaded_files && count($form->uploaded_files) > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Uploaded Files</h3>
                    </div>
                    <div class="px-6 py-5">
                        <div class="space-y-4">
                            @foreach($form->uploaded_files as $index => $file)
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
                                                    {{ $form->submission_date->format('M j, Y g:i A') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Download Button -->
                                    <a href="{{ route('admin.records.download', ['type' => 'form', 'id' => $form->id, 'fileIndex' => $index]) }}" 
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
            @if($form->review_comments)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Review Feedback</h3>
                    </div>
                    <div class="px-6 py-5">
                        <div class="space-y-4">
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $form->reviewer ? $form->reviewer->name : 'Reviewer' }}
                                        </h4>
                                        @if($form->reviewed_at)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $form->reviewed_at->format('F j, Y g:i A') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $form->review_comments }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
