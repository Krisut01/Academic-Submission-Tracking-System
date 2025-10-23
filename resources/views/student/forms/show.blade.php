<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📄 {{ __('Form Details') }} - {{ $form->form_type_label }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('student.forms.history') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to History
                </a>
            </div>

            <!-- Form Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-lg flex items-center justify-center
                                @if($form->form_type === 'registration') bg-blue-100 dark:bg-blue-900
                                @elseif($form->form_type === 'clearance') bg-green-100 dark:bg-green-900
                                @else bg-purple-100 dark:bg-purple-900 @endif">
                                @if($form->form_type === 'registration')
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                @elseif($form->form_type === 'clearance')
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @else
                                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-1">
                                    {{ $form->title }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ $form->form_type_label }} • Student ID: {{ $form->student_id }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">
                                    Submitted on {{ $form->submission_date->format('F j, Y') }} ({{ $form->created_at->diffForHumans() }})
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($form->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($form->status === 'under_review') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($form->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                @if($form->status === 'pending') ⏳ Pending Review
                                @elseif($form->status === 'under_review') 📋 Under Review
                                @elseif($form->status === 'approved') ✅ Approved
                                @else ❌ Rejected @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                Basic Information
                            </h4>

                            @if($form->description)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $form->description }}</p>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($form->form_data && isset($form->form_data['academic_year']))
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Year</label>
                                        <p class="text-gray-900 dark:text-gray-100">{{ $form->form_data['academic_year'] }}</p>
                                    </div>
                                @endif

                                @if($form->form_data && isset($form->form_data['semester']))
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Semester</label>
                                        <p class="text-gray-900 dark:text-gray-100">{{ $form->form_data['semester'] }}</p>
                                    </div>
                                @endif

                                @if($form->form_data && isset($form->form_data['program']))
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Program</label>
                                        <p class="text-gray-900 dark:text-gray-100">{{ $form->form_data['program'] }}</p>
                                    </div>
                                @endif

                                @if($form->form_data && isset($form->form_data['year_level']))
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year Level</label>
                                        <p class="text-gray-900 dark:text-gray-100">{{ $form->form_data['year_level'] }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Form-Specific Data -->
                    @if($form->form_data)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                                        @if($form->form_type === 'registration')
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        @elseif($form->form_type === 'clearance')
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    {{ ucfirst($form->form_type) }} Specific Details
                                </h4>

                                @if($form->form_type === 'registration')
                                    @if(isset($form->form_data['subjects']) && is_array($form->form_data['subjects']))
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Enrolled Subjects</label>
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                @foreach($form->form_data['subjects'] as $index => $subject)
                                                    @if($subject)
                                                        <div class="flex justify-between items-center py-1">
                                                            <span class="text-gray-900 dark:text-gray-100">{{ $subject }}</span>
                                                            @if(isset($form->form_data['subject_units'][$index]))
                                                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $form->form_data['subject_units'][$index] }} units</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if(isset($form->form_data['units_total']))
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Units</label>
                                                <p class="text-gray-900 dark:text-gray-100">{{ $form->form_data['units_total'] }}</p>
                                            </div>
                                        @endif

                                        @if(isset($form->form_data['previous_gpa']))
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Previous GPA</label>
                                                <p class="text-gray-900 dark:text-gray-100">{{ $form->form_data['previous_gpa'] }}</p>
                                            </div>
                                        @endif

                                        @if(isset($form->form_data['scholarship_status']))
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Scholarship Status</label>
                                                <p class="text-gray-900 dark:text-gray-100">{{ $form->form_data['scholarship_status'] }}</p>
                                            </div>
                                        @endif
                                    </div>

                                @elseif($form->form_type === 'clearance')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        @if(isset($form->form_data['clearance_type']))
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Clearance Type</label>
                                                <p class="text-gray-900 dark:text-gray-100">{{ $form->form_data['clearance_type'] }}</p>
                                            </div>
                                        @endif

                                        @if(isset($form->form_data['expected_graduation']))
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expected Graduation</label>
                                                <p class="text-gray-900 dark:text-gray-100">{{ $form->form_data['expected_graduation'] }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    @if(isset($form->form_data['reason']))
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason for Request</label>
                                            <p class="text-gray-900 dark:text-gray-100">{{ $form->form_data['reason'] }}</p>
                                        </div>
                                    @endif

                                    @if(isset($form->form_data['outstanding_obligations']) && is_array($form->form_data['outstanding_obligations']))
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Outstanding Obligations</label>
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                @foreach($form->form_data['outstanding_obligations'] as $obligation)
                                                    <div class="py-1 text-gray-900 dark:text-gray-100">• {{ $obligation }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                @elseif($form->form_type === 'evaluation')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        @if(isset($form->form_data['evaluation_type']))
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Evaluation Type</label>
                                                <p class="text-gray-900 dark:text-gray-100">{{ $form->form_data['evaluation_type'] }}</p>
                                            </div>
                                        @endif

                                        @if(isset($form->form_data['thesis_status']))
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thesis Status</label>
                                                <p class="text-gray-900 dark:text-gray-100">{{ $form->form_data['thesis_status'] }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    @if(isset($form->form_data['completed_subjects']) && is_array($form->form_data['completed_subjects']))
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Completed Subjects</label>
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                @foreach($form->form_data['completed_subjects'] as $index => $subject)
                                                    @if($subject)
                                                        <div class="flex justify-between items-center py-1">
                                                            <span class="text-gray-900 dark:text-gray-100">{{ $subject }}</span>
                                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                                @if(isset($form->form_data['grades'][$index]))
                                                                    Grade: {{ $form->form_data['grades'][$index] }}
                                                                @endif
                                                                @if(isset($form->form_data['subject_units'][$index]))
                                                                    • {{ $form->form_data['subject_units'][$index] }} units
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($form->form_data['special_requirements']) && is_array($form->form_data['special_requirements']))
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Special Requirements</label>
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                @foreach($form->form_data['special_requirements'] as $requirement)
                                                    <div class="py-1 text-gray-900 dark:text-gray-100">• {{ $requirement }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Additional Remarks -->
                    @if($form->remarks)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z"></path>
                                        </svg>
                                    </div>
                                    Additional Remarks
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $form->remarks }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Review Comments -->
                    @if($form->review_comments)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    Review Comments
                                </h4>
                                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                    <p class="text-red-800 dark:text-red-300 whitespace-pre-wrap">{{ $form->review_comments }}</p>
                                    @if($form->reviewed_at)
                                        <p class="text-xs text-red-600 dark:text-red-400 mt-2">
                                            Reviewed on {{ $form->reviewed_at->format('F j, Y \a\t g:i A') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Timeline -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                📋 Status Timeline
                            </h4>
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Form Submitted</p>
                                        <p class="text-xs text-gray-500">{{ $form->created_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                </div>

                                @if($form->status === 'under_review' || $form->status === 'approved' || $form->status === 'rejected')
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Under Review</p>
                                            <p class="text-xs text-gray-500">Processing started</p>
                                        </div>
                                    </div>
                                @endif

                                @if($form->status === 'approved' || $form->status === 'rejected')
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 {{ $form->status === 'approved' ? 'bg-green-500' : 'bg-red-500' }} rounded-full"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $form->status === 'approved' ? 'Approved' : 'Rejected' }}
                                            </p>
                                            @if($form->reviewed_at)
                                                <p class="text-xs text-gray-500">{{ $form->reviewed_at->format('M j, Y g:i A') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @elseif($form->status === 'pending')
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Awaiting Review</p>
                                            <p class="text-xs text-gray-400">Pending</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Uploaded Files -->
                    @if($form->uploaded_files && count($form->uploaded_files) > 0)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    📎 Uploaded Files
                                </h4>
                                <div class="space-y-3">
                                    @foreach($form->uploaded_files as $index => $file)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $file['original_name'] }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ round($file['size'] / 1024 / 1024, 2) }} MB
                                                    </p>
                                                </div>
                                            </div>
                                            <a href="{{ route('student.forms.download', [$form, $index]) }}" 
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm transition">
                                                Download
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                ⚡ Actions
                            </h4>
                            <div class="space-y-3">
                                <a href="{{ route('student.forms.index') }}" 
                                   class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-gray-900 dark:text-white rounded-lg hover:bg-blue-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Submit New Form
                                </a>
                                <a href="{{ route('student.forms.history') }}" 
                                   class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    View All Forms
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
