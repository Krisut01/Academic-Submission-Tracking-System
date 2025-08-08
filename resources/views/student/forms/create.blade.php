<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📝 {{ __('Submit ') . ucfirst($formType) . __(' Form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                {{ ucfirst($formType) }} Form Submission
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Please fill out all required fields and upload necessary documents.
                            </p>
                        </div>
                        <a href="{{ route('student.forms.index') }}" 
                           class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            ← Back to Forms
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <form method="POST" action="{{ route('student.forms.store') }}" enctype="multipart/form-data" id="academicForm">
                        @csrf
                        <input type="hidden" name="form_type" value="{{ $formType }}">

                        <!-- Basic Information Section -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                Basic Information
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Student ID -->
                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Student ID <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                           placeholder="Enter your student ID">
                                    @error('student_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Academic Year -->
                                <div>
                                    <label for="academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Academic Year <span class="text-red-500">*</span>
                                    </label>
                                    <select name="academic_year" id="academic_year" required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <option value="">Select Academic Year</option>
                                        <option value="2023-2024" {{ old('academic_year') == '2023-2024' ? 'selected' : '' }}>2023-2024</option>
                                        <option value="2024-2025" {{ old('academic_year') == '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                                        <option value="2025-2026" {{ old('academic_year') == '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                                    </select>
                                    @error('academic_year')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Semester -->
                                <div>
                                    <label for="semester" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Semester <span class="text-red-500">*</span>
                                    </label>
                                    <select name="semester" id="semester" required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <option value="">Select Semester</option>
                                        <option value="1st" {{ old('semester') == '1st' ? 'selected' : '' }}>1st Semester</option>
                                        <option value="2nd" {{ old('semester') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                                        <option value="Summer" {{ old('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                                    </select>
                                    @error('semester')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Program -->
                                <div>
                                    <label for="program" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Program <span class="text-red-500">*</span>
                                    </label>
                                    <select name="program" id="program" required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <option value="">Select Program</option>
                                        <option value="BS Medical Technology" {{ old('program') == 'BS Medical Technology' ? 'selected' : '' }}>BS Medical Technology</option>
                                        <option value="BS Nursing" {{ old('program') == 'BS Nursing' ? 'selected' : '' }}>BS Nursing</option>
                                        <option value="BS Pharmacy" {{ old('program') == 'BS Pharmacy' ? 'selected' : '' }}>BS Pharmacy</option>
                                        <option value="Other" {{ old('program') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('program')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Title -->
                            <div class="mt-6">
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Form Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                       placeholder="Enter a descriptive title for this form">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mt-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Description
                                </label>
                                <textarea name="description" id="description" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                          placeholder="Provide additional details about this form submission">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Form-Specific Fields -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                                    @if($formType === 'registration')
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    @elseif($formType === 'clearance')
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                {{ ucfirst($formType) }} Specific Information
                            </h4>

                            @if($formType === 'registration')
                                @include('student.forms.partials.registration-fields')
                            @elseif($formType === 'clearance')
                                @include('student.forms.partials.clearance-fields')
                            @elseif($formType === 'evaluation')
                                @include('student.forms.partials.evaluation-fields')
                            @endif
                        </div>

                        <!-- File Upload Section -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                Supporting Documents
                            </h4>

                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="mt-4">
                                        <label for="files" class="cursor-pointer">
                                            <span class="mt-2 block text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Click to upload files or drag and drop
                                            </span>
                                            <span class="mt-1 block text-xs text-gray-500 dark:text-gray-400">
                                                PDF, DOC, DOCX, JPG, PNG up to 10MB each
                                            </span>
                                        </label>
                                        <input id="files" name="files[]" type="file" class="sr-only" multiple 
                                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="displaySelectedFiles(this)">
                                    </div>
                                </div>
                                
                                <!-- Selected Files Display -->
                                <div id="selected-files" class="mt-4 hidden">
                                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selected Files:</h5>
                                    <div id="file-list" class="space-y-2"></div>
                                </div>
                            </div>
                            @error('files.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remarks Section -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z"></path>
                                    </svg>
                                </div>
                                Additional Remarks
                            </h4>

                            <textarea name="remarks" id="remarks" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                      placeholder="Any additional information or special requests...">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Section -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-end">
                            <a href="{{ route('student.forms.index') }}" 
                               class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-center">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                Submit Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
                    fileDiv.className = 'flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-2 rounded';
                    fileDiv.innerHTML = `
                        <span class="text-sm text-gray-700 dark:text-gray-300">${file.name}</span>
                        <span class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                    `;
                    fileList.appendChild(fileDiv);
                });
            } else {
                selectedFilesDiv.classList.add('hidden');
            }
        }

        // Form validation
        document.getElementById('academicForm').addEventListener('submit', function(e) {
            const submitButton = e.target.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = 'Submitting...';
        });
    </script>
</x-app-layout> 