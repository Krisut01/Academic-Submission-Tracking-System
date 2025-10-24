<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📄 {{ __('Thesis Document Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.records') }}?tab=thesis_documents" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Records
                </a>
            </div>

            <!-- Document Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $document->title }}</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Submitted by {{ $document->user->name }}</p>
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
                                {{ ucfirst(str_replace('_', ' ', $document->status)) }}
                            </span>

                            <!-- Document Actions Dropdown -->
                            @php
                                $hasActions = false;
                                $actions = [];
                                
                                // Define actions based on document type and status
                                if ($document->document_type === 'proposal' && $document->status === 'approved') {
                                    $actions[] = [
                                        'label' => 'Ready for Panel Request',
                                        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                                        'url' => '#',
                                        'color' => 'blue',
                                        'disabled' => true,
                                        'tooltip' => 'Student needs to submit panel assignment request'
                                    ];
                                }
                                
                                if ($document->document_type === 'panel_assignment' && $document->status === 'approved') {
                                    $actions[] = [
                                        'label' => 'Create Panel Assignment',
                                        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 715.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                                        'url' => route('admin.panel.create', ['from_request' => $document->id]),
                                        'color' => 'green'
                                    ];
                                }
                                
                                if ($document->document_type === 'approval_sheet' && $document->status === 'approved') {
                                    $actions[] = [
                                        'label' => 'Ready for Final Manuscript',
                                        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                                        'url' => '#',
                                        'color' => 'purple',
                                        'disabled' => true,
                                        'tooltip' => 'Student needs to submit final manuscript'
                                    ];
                                }
                                
                                if ($document->document_type === 'final_manuscript' && $document->status === 'approved') {
                                    // Check if student already has a panel assignment
                                    $existingPanel = \App\Models\PanelAssignment::where('student_id', $document->user_id)->first();
                                    
                                    if ($existingPanel) {
                                        // Unified defense scheduling actions
                                        if ($existingPanel->status === 'scheduled') {
                                            $actions[] = [
                                                'label' => 'Reschedule Defense',
                                                'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                                                'url' => route('admin.panel.edit', $existingPanel->id),
                                                'color' => 'yellow'
                                            ];
                                        } else {
                                            $actions[] = [
                                                'label' => 'Schedule Final Defense',
                                                'icon' => 'M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z',
                                                'url' => route('admin.panel.edit', $existingPanel->id),
                                                'color' => 'green'
                                            ];
                                        }
                                        
                                        $actions[] = [
                                            'label' => 'View Panel Details',
                                            'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
                                            'url' => route('admin.panel.show', $existingPanel->id),
                                            'color' => 'blue'
                                        ];
                                    } else {
                                        $actions[] = [
                                            'label' => 'Schedule Final Defense',
                                            'icon' => 'M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z',
                                            'url' => route('admin.panel.create', ['student_id' => $document->user_id, 'thesis_id' => $document->id]),
                                            'color' => 'green'
                                        ];
                                    }
                                }
                                
                                // General actions available for all documents
                                if (in_array($document->status, ['pending', 'under_review'])) {
                                    $actions[] = [
                                        'label' => 'Mark as Reviewed',
                                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'url' => '#',
                                        'color' => 'green',
                                        'onclick' => 'markAsReviewed(' . $document->id . ')'
                                    ];
                                }
                                
                                $hasActions = !empty($actions);
                            @endphp
                            
                            @if($hasActions)
                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                        </svg>
                                        Actions
                                        <svg class="w-4 h-4 ml-2" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <div x-show="open" 
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 z-50 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 py-2">
                                        
                                        @foreach($actions as $action)
                                            @if(isset($action['disabled']) && $action['disabled'])
                                                <div class="px-4 py-3 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed flex items-center"
                                                     title="{{ $action['tooltip'] ?? '' }}">
                                                    <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}"></path>
                                                    </svg>
                                                    {{ $action['label'] }}
                                                    <span class="ml-auto text-xs">(Waiting)</span>
                                                </div>
                                            @else
                                                <a href="{{ $action['url'] }}" 
                                                   @if(isset($action['onclick'])) onclick="{{ $action['onclick'] }}" @endif
                                                   class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center
                                                   @if($action['color'] === 'green') hover:text-green-600 dark:hover:text-green-400
                                                   @elseif($action['color'] === 'blue') hover:text-blue-600 dark:hover:text-blue-400
                                                   @elseif($action['color'] === 'red') hover:text-red-600 dark:hover:text-red-400
                                                   @elseif($action['color'] === 'yellow') hover:text-yellow-600 dark:hover:text-yellow-400
                                                   @elseif($action['color'] === 'purple') hover:text-purple-600 dark:hover:text-purple-400
                                                   @endif">
                                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}"></path>
                                                    </svg>
                                                    {{ $action['label'] }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Document Info Grid -->
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Student Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Student Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Student Name</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $document->user->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $document->user->email }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Student ID</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $document->student_id }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Course/Program</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $document->course_program ?? 'Not specified' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Document Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Document Details</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Document Type</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $document->document_type_label }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $document->status)) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Submission Date</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $document->submission_date->format('F j, Y') }}</p>
                                </div>
                                @if($document->reviewed_at)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Reviewed Date</label>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $document->reviewed_at->format('F j, Y') }}</p>
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
                                    <a href="{{ route('admin.records.download', ['type' => 'document', 'id' => $document->id, 'fileIndex' => $index]) }}" 
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
                                            {{ $document->reviewer ? $document->reviewer->name : 'Reviewer' }}
                                        </h4>
                                        @if($document->reviewed_at)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $document->reviewed_at->format('F j, Y g:i A') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $document->review_comments }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Defense Confirmation Section -->
            @if($document->document_type === 'proposal' || $document->document_type === 'final_manuscript')
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Defense Confirmation</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Confirm whether this document has been defended or not</p>
                    </div>
                    <div class="px-6 py-5">
                        @if($document->defense_status)
                            <!-- Defense Status Display -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl p-4 mb-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-green-100 dark:bg-green-800 rounded-lg">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-green-900 dark:text-green-100">
                                                Defense Status: {{ ucfirst($document->defense_status) }}
                                            </h4>
                                            @if($document->defense_confirmed_at)
                                                <p class="text-xs text-green-700 dark:text-green-300">
                                                    Confirmed on {{ $document->defense_confirmed_at->format('F j, Y \a\t g:i A') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    @if($document->defense_grade)
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200">
                                                Grade: {{ $document->defense_grade }}%
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                @if($document->defense_notes)
                                    <div class="mt-3 p-3 bg-green-100 dark:bg-green-800/30 rounded-lg">
                                        <p class="text-sm text-green-800 dark:text-green-200">
                                            <strong>Notes:</strong> {{ $document->defense_notes }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Defense Confirmation Actions -->
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Quick Actions -->
                                    <div class="space-y-3">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Quick Actions</h4>
                                        
                                        <!-- Mark as Defended Button -->
                                        <button onclick="showDefenseModal('defended')" 
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Mark as Defended
                                        </button>
                                        
                                        <!-- Mark as Not Defended Button -->
                                        <button onclick="showDefenseModal('not_defended')" 
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Mark as Not Defended
                                        </button>
                                        
                                        <!-- Postponed Button -->
                                        <button onclick="showDefenseModal('postponed')" 
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Mark as Postponed
                                        </button>
                                    </div>
                                    
                                    <!-- Document Info -->
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Document Information</h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Type:</span>
                                                <span class="text-gray-900 dark:text-white">{{ $document->document_type_label }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                                <span class="text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $document->status)) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Student:</span>
                                                <span class="text-gray-900 dark:text-white">{{ $document->user->name }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Submitted:</span>
                                                <span class="text-gray-900 dark:text-white">{{ $document->submission_date->format('M j, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Defense Confirmation Modal -->
    <div id="defenseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="modalTitle">Defense Confirmation</h3>
                    <button onclick="closeDefenseModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="defenseForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Defense Status
                        </label>
                        <select id="defenseStatus" name="defense_status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="completed">Completed</option>
                            <option value="failed">Failed</option>
                            <option value="postponed">Postponed</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Defense Grade (Optional)
                        </label>
                        <input type="number" id="defenseGrade" name="defense_grade" min="0" max="100" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Enter grade (0-100)">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Defense Date (Optional)
                        </label>
                        <input type="date" id="defenseDate" name="defense_date" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Notes (Optional)
                        </label>
                        <textarea id="defenseNotes" name="defense_notes" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Enter any additional notes about the defense..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeDefenseModal()" 
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" id="submitDefense" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            Confirm Defense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alpine.js for dropdown functionality -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- JavaScript for actions -->
    <script>
        let currentDefenseAction = null;
        const documentId = {{ $document->id }};

        function markAsReviewed(documentId) {
            if (confirm('Mark this document as reviewed?')) {
                // You can implement this functionality later
                alert('This feature will be implemented to mark documents as reviewed.');
            }
        }

        // Defense Confirmation Functions
        function showDefenseModal(action) {
            currentDefenseAction = action;
            const modal = document.getElementById('defenseModal');
            const title = document.getElementById('modalTitle');
            const statusSelect = document.getElementById('defenseStatus');
            const submitBtn = document.getElementById('submitDefense');

            // Set modal title and default values based on action
            switch(action) {
                case 'defended':
                    title.textContent = 'Mark as Defended';
                    statusSelect.value = 'completed';
                    submitBtn.textContent = 'Mark as Defended';
                    submitBtn.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200';
                    break;
                case 'not_defended':
                    title.textContent = 'Mark as Not Defended';
                    statusSelect.value = 'failed';
                    submitBtn.textContent = 'Mark as Not Defended';
                    submitBtn.className = 'px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200';
                    break;
                case 'postponed':
                    title.textContent = 'Mark as Postponed';
                    statusSelect.value = 'postponed';
                    submitBtn.textContent = 'Mark as Postponed';
                    submitBtn.className = 'px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors duration-200';
                    break;
            }

            modal.classList.remove('hidden');
        }

        function closeDefenseModal() {
            const modal = document.getElementById('defenseModal');
            modal.classList.add('hidden');
            document.getElementById('defenseForm').reset();
            currentDefenseAction = null;
        }

        // Handle form submission
        document.getElementById('defenseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitDefense');
            const originalText = submitBtn.textContent;
            
            // Show loading state
            submitBtn.textContent = 'Processing...';
            submitBtn.disabled = true;

            // Determine which endpoint to use
            const endpoint = currentDefenseAction === 'defended' 
                ? `/admin/records/documents/${documentId}/mark-defended`
                : `/admin/records/documents/${documentId}/confirm-defense`;

            fetch(endpoint, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showToast('Defense status updated successfully!', 'success');
                    
                    // Reload page to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast(data.message || 'Failed to update defense status', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while updating defense status', 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });

        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium shadow-lg transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 
                'bg-blue-500'
            }`;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }

        // Close modal when clicking outside
        document.getElementById('defenseModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDefenseModal();
            }
        });
        
        // Auto-close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[x-data]');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target)) {
                    // Close dropdown logic handled by Alpine.js
                }
            });
        });
    </script>
</x-app-layout>
