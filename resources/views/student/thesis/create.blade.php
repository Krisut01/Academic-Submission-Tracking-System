<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                {{ __('Submit ') . ucfirst(str_replace('_', ' ', $documentType)) }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </x-slot>

    <!-- Enhanced Custom Styles -->
    <style>
        .form-section {
            transition: all 0.3s ease;
        }
        .form-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1);
        }
        .field-group {
            background: rgba(249, 250, 251, 0.5);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(229, 231, 235, 0.6);
        }
        .dark .field-group {
            background: rgba(31, 41, 55, 0.5);
            border-color: rgba(75, 85, 99, 0.6);
        }
        .submit-btn {
            background: linear-gradient(135deg, #3B82F6, #1D4ED8);
            transition: all 0.3s ease;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }
    </style>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl shadow-2xl mb-6">
                    @if($documentType === 'proposal')
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    @elseif($documentType === 'approval_sheet')
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @elseif($documentType === 'panel_assignment')
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    @else
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.168 18.477 18.754 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    @endif
                </div>
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Submit {{ ucfirst(str_replace('_', ' ', $documentType)) }}
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Complete all required fields and upload necessary documents for your {{ str_replace('_', ' ', $documentType) }} submission.
                </p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-2xl shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-500 rounded-xl shadow-lg mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-green-800 dark:text-green-300 font-semibold text-lg">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-6 bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-800 rounded-2xl shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-500 rounded-xl shadow-lg mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-red-800 dark:text-red-300 font-semibold text-lg">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Main Form -->
            <form method="POST" action="{{ route('student.thesis.store') }}" enctype="multipart/form-data" id="thesisForm" class="space-y-8">
                @csrf
                <input type="hidden" name="document_type" value="{{ $documentType }}">

                <!-- Basic Information Section -->
                <div class="form-section bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Basic Information</h3>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <div class="field-group">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Student ID -->
                                <div>
                                    <label for="student_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                        Student ID <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                           placeholder="Enter your student ID">
                                    @error('student_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Full Name -->
                                <div>
                                    <label for="full_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name', auth()->user()->name) }}" required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                           placeholder="Enter your full name">
                                    @error('full_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Course/Program -->
                                <div>
                                    <label for="course_program" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                        Course / Program <span class="text-red-500">*</span>
                                    </label>
                                    <select name="course_program" id="course_program" required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <option value="">Select Course/Program</option>
                                        <option value="BS in Information Technology" {{ old('course_program') == 'BS in Information Technology' ? 'selected' : '' }}>BS in Information Technology</option>
                                        <option value="BS in Computer Science" {{ old('course_program') == 'BS in Computer Science' ? 'selected' : '' }}>BS in Computer Science</option>
                                        <option value="BS in Information System" {{ old('course_program') == 'BS in Information System' ? 'selected' : '' }}>BS in Information System</option>
                                    </select>
                                    @error('course_program')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Date of Submission -->
                                <div>
                                    <label for="submission_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                        Date of Submission <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="submission_date" id="submission_date" value="{{ old('submission_date', now()->format('Y-m-d')) }}" required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    @error('submission_date')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Thesis Title -->
                            <div class="mt-6">
                                <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    Thesis Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                       placeholder="Enter your thesis title">
                                @error('title')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mt-6">
                                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    Brief Description
                                </label>
                                <textarea name="description" id="description" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                          placeholder="Provide a brief description of your thesis...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Adviser Selection -->
                            <div class="mt-6">
                                <label for="adviser_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    Adviser <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <select name="adviser_id" id="adviser_id"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <option value="">Select from Faculty List</option>
                                        @foreach(\App\Models\User::where('role', 'faculty')->orderBy('name')->get() as $faculty)
                                            <option value="{{ $faculty->id }}" {{ old('adviser_id') == $faculty->id ? 'selected' : '' }}>
                                                {{ $faculty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="adviser_name" id="adviser_name" value="{{ old('adviser_name') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                           placeholder="Or enter adviser name manually">
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Select from the dropdown or enter manually if not listed</p>
                                @error('adviser_name') @error('adviser_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document-Specific Fields -->
                @if($documentType === 'proposal')
                    @include('student.thesis.partials.proposal-fields')
                @elseif($documentType === 'approval_sheet')
                    @include('student.thesis.partials.approval-fields')
                @elseif($documentType === 'panel_assignment')
                    @include('student.thesis.partials.panel-fields')
                @elseif($documentType === 'final_manuscript')
                    @include('student.thesis.partials.manuscript-fields')
                @endif

                <!-- File Upload Section -->
                <div class="form-section bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Document Upload</h3>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <div class="field-group">
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-8 text-center hover:border-purple-400 transition-colors">
                                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div>
                                    <label for="files" class="cursor-pointer">
                                        <span class="text-xl font-semibold text-gray-900 dark:text-gray-100 block mb-2">
                                            Upload Required Documents <span class="text-red-500">*</span>
                                        </span>
                                        <span class="text-gray-500 dark:text-gray-400 block mb-4">
                                            Click to upload or drag and drop files here
                                        </span>
                                        <span class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Choose Files
                                        </span>
                                    </label>
                                    <input id="files" name="files[]" type="file" class="sr-only" multiple required
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="displaySelectedFiles(this)">
                                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                                        Supported formats: PDF, DOC, DOCX, JPG, PNG (Max 10MB each)
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Selected Files Display -->
                            <div id="selected-files" class="mt-6 hidden">
                                <h5 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Selected Files:</h5>
                                <div id="file-list" class="space-y-3"></div>
                            </div>
                            @error('files.*')
                                <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Comments and Remarks Section -->
                <div class="form-section bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 bg-gradient-to-r from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Additional Information</h3>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <div class="field-group space-y-6">
                            <!-- Comments -->
                            <div>
                                <label for="comments" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    Comments (Optional)
                                </label>
                                <textarea name="comments" id="comments" rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                          placeholder="Add any comments or questions regarding this submission...">{{ old('comments') }}</textarea>
                                @error('comments')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remarks -->
                            <div>
                                <label for="remarks" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    Remarks (Optional)
                                </label>
                                <textarea name="remarks" id="remarks" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                          placeholder="Any additional notes or special considerations...">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="flex flex-col sm:flex-row gap-6 justify-end">
                    <a href="{{ route('student.thesis.index') }}" 
                       class="px-8 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-center font-semibold">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="submit-btn px-8 py-4 text-white rounded-xl font-bold text-lg shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Submit {{ ucfirst(str_replace('_', ' ', $documentType)) }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function displaySelectedFiles(input) {
            const fileList = document.getElementById('file-list');
            const selectedFilesDiv = document.getElementById('selected-files');
            
            fileList.innerHTML = '';
            
            if (input.files.length > 0) {
                selectedFilesDiv.classList.remove('hidden');
                
                Array.from(input.files).forEach((file, index) => {
                    const fileDiv = document.createElement('div');
                    fileDiv.className = 'flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border';
                    fileDiv.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">${file.name}</p>
                                <p class="text-sm text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                            </div>
                        </div>
                        <div class="text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    `;
                    fileList.appendChild(fileDiv);
                });
            } else {
                selectedFilesDiv.classList.add('hidden');
            }
        }

        // Form validation and submission
        document.getElementById('thesisForm').addEventListener('submit', function(e) {
            const submitButton = e.target.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Submitting...
            `;
        });

        // Adviser selection sync
        document.getElementById('adviser_id').addEventListener('change', function() {
            if (this.value) {
                document.getElementById('adviser_name').value = this.options[this.selectedIndex].text;
            }
        });

        document.getElementById('adviser_name').addEventListener('input', function() {
            if (this.value) {
                document.getElementById('adviser_id').value = '';
            }
        });
    </script>
</x-app-layout> 