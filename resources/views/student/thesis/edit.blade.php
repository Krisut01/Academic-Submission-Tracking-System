<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📝 {{ __('Edit Document') }} - {{ $document->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('student.thesis.show', $document) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Document Details
                </a>
            </div>

            <!-- Faculty Feedback -->
            @if($document->review_comments)
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-6 mb-6">
                    <div class="flex items-start">
                        <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-2">Faculty Feedback</h3>
                            <p class="text-red-700 dark:text-red-400 mb-3">{{ $document->review_comments }}</p>
                            <div class="text-sm text-red-600 dark:text-red-500">
                                <strong>Reviewer:</strong> {{ $document->reviewer ? $document->reviewer->name : 'Faculty Member' }}
                                @if($document->reviewed_at)
                                    • <strong>Date:</strong> {{ $document->reviewed_at->format('F j, Y g:i A') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Edit Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Update Your Document</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Make the necessary changes based on the faculty feedback and resubmit for review.
                    </p>
                </div>

                <form method="POST" action="{{ route('student.thesis.update', $document) }}" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                                Document Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $document->title) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-300"
                                   placeholder="Enter your document title">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-300"
                                      placeholder="Brief description of your document">{{ old('description', $document->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document Type Specific Fields -->
                        @if($document->document_type === 'proposal')
                            @include('student.thesis.partials.proposal-fields', ['isEdit' => true, 'document' => $document])
                        @endif

                        <!-- File Upload -->
                        <div>
                            <label for="files" class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                                Upload Files
                            </label>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center hover:border-emerald-500 transition-colors duration-200">
                                <input type="file" name="files[]" id="files" multiple
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       class="hidden">
                                <label for="files" class="cursor-pointer">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400 mb-2">Click to upload files or drag and drop</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500">PDF, DOC, DOCX, JPG, PNG (Max 10MB each)</p>
                                </label>
                            </div>
                            @error('files.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Files -->
                        @if($document->uploaded_files && count($document->uploaded_files) > 0)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-100 mb-3">
                                    Current Files
                                </label>
                                <div class="space-y-3" id="current-files-container">
                                    @foreach($document->uploaded_files as $index => $file)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg file-item" data-file-index="{{ $index }}">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $file['original_name'] }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($file['size'] / 1024, 2) }} KB</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('student.thesis.download', ['document' => $document, 'fileIndex' => $index]) }}" 
                                                   class="text-blue-600 dark:text-blue-400 text-sm hover:underline">
                                                    Download
                                                </a>
                                                <button type="button" 
                                                        onclick="removeFile({{ $index }}, '{{ route('student.thesis.remove-file', ['document' => $document, 'fileIndex' => $index]) }}')"
                                                        class="text-red-600 dark:text-red-400 text-sm hover:underline">
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    <strong>Note:</strong> You can remove existing files or add new ones. Files marked for removal will be deleted when you submit.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700 mt-8">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <p><strong>Version:</strong> {{ $document->version_number + 1 }}</p>
                            <p>This will resubmit your document for faculty review.</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('student.thesis.show', $document) }}" 
                               class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium transition-colors duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Update & Resubmit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for file removal -->
    <script>
        function removeFile(fileIndex, removeUrl) {
            if (confirm('Are you sure you want to remove this file? This action cannot be undone.')) {
                // Show loading state
                const fileItem = document.querySelector(`[data-file-index="${fileIndex}"]`);
                const removeButton = fileItem.querySelector('button[onclick*="removeFile"]');
                const originalText = removeButton.innerHTML;
                removeButton.innerHTML = 'Removing...';
                removeButton.disabled = true;
                
                // Make AJAX request to remove file
                fetch(removeUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hide the file item with animation
                        fileItem.style.transition = 'all 0.3s ease';
                        fileItem.style.opacity = '0';
                        fileItem.style.transform = 'translateX(-100%)';
                        
                        setTimeout(() => {
                            fileItem.remove();
                            
                            // Check if no files remain
                            const remainingFiles = document.querySelectorAll('.file-item');
                            if (remainingFiles.length === 0) {
                                const container = document.getElementById('current-files-container');
                                container.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No files remaining. Please upload at least one file.</p>';
                            }
                        }, 300);
                        
                        // Show success message
                        showNotification('File removed successfully!', 'success');
                    } else {
                        throw new Error(data.error || 'Failed to remove file');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to remove file. Please try again.', 'error');
                    removeButton.innerHTML = originalText;
                    removeButton.disabled = false;
                });
            }
        }
        
        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const hasFiles = document.querySelector('input[type="file"]').files.length > 0;
            const hasExistingFiles = document.querySelectorAll('.file-item').length > 0;
            
            if (!hasFiles && !hasExistingFiles) {
                e.preventDefault();
                alert('Please upload at least one file or keep at least one existing file.');
                return false;
            }
        });
        
        // Notification function
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</x-app-layout>
