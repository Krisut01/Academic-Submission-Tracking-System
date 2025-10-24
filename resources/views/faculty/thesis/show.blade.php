<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                📝 Review: {{ $document->document_type_label }}
            </h2>
            <a href="{{ route('faculty.thesis.reviews') }}" 
               class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Reviews
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="xl:col-span-2 space-y-8">
                    <!-- Document Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $document->title }}</h3>
                                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $document->user->name }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-.707.293H7a4 4 0 01-4-4V7a4 4 0 014-4z"></path>
                                            </svg>
                                            ID: {{ $document->student_id }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 6v6a1 1 0 001 1h4a1 1 0 001-1v-6M8 13h8"></path>
                                            </svg>
                                            {{ $document->submission_date->format('M j, Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($document->document_type === 'proposal') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                        @elseif($document->document_type === 'approval_sheet') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                        @elseif($document->document_type === 'panel_assignment') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                                        @else bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200 @endif">
                                        {{ $document->document_type_label }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($document->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                                        @elseif($document->status === 'under_review') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                        @elseif($document->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                        @else bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $document->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <!-- Description -->
                            @if($document->description)
                                <div class="mb-6">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Description</h4>
                                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $document->description }}</p>
                                </div>
                            @endif

                            <!-- Document Metadata -->
                            @if($document->document_metadata)
                                <div class="mb-6">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-4">Document Details</h4>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($document->document_metadata as $key => $value)
                                                @if($value && !is_array($value))
                                                    <div>
                                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                                        <dd class="text-sm text-gray-900 dark:text-white mt-1">{{ $value }}</dd>
                                                    </div>
                                                @elseif(is_array($value) && !empty($value))
                                                    <div class="md:col-span-2">
                                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                                        <dd class="text-sm text-gray-900 dark:text-white">
                                                            <div class="flex flex-wrap gap-2">
                                                                @foreach($value as $item)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">
                                                                        {{ $item }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </dd>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Student Comments -->
                            @if($document->comments)
                                <div class="mb-6">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Student Comments</h4>
                                    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 p-4 rounded-r-lg">
                                        <p class="text-gray-700 dark:text-gray-300">{{ $document->comments }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Uploaded Files -->
                            @if($document->uploaded_files && count($document->uploaded_files) > 0)
                                <div class="mb-6">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-4">Uploaded Files</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($document->uploaded_files as $index => $file)
                                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg mr-3">
                                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $file['original_name'] }}</p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($file['size'] / 1024, 1) }} KB</p>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('faculty.thesis.download', [$document, $index]) }}" 
                                                       class="bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors duration-200">
                                                        Download
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Previous Review Comments -->
                            @if($document->review_comments)
                                <div class="mb-6">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Previous Review Comments</h4>
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                                        <p class="text-gray-700 dark:text-gray-300">{{ $document->review_comments }}</p>
                                        @if($document->reviewer)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                Reviewed by {{ $document->reviewer->name }} on {{ $document->reviewed_at->format('M j, Y \a\t g:i A') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Review Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <div class="p-1 bg-green-100 dark:bg-green-900/50 rounded-lg mr-2">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Review Actions
                        </h3>

                        <form method="POST" action="{{ route('faculty.thesis.update', $document) }}" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <!-- Status Selection -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Review Decision</label>
                                <select name="status" id="status" required 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-900 dark:text-white">
                                    <option value="">Select Decision...</option>
                                    <option value="approved" {{ $document->status === 'approved' ? 'selected' : '' }}>✅ Approve</option>
                                    <option value="returned_for_revision" {{ $document->status === 'returned_for_revision' ? 'selected' : '' }}>📝 Return for Revision</option>
                                    <option value="under_review" {{ $document->status === 'under_review' ? 'selected' : '' }}>🔍 Mark Under Review</option>
                                </select>
                            </div>

                            <!-- Review Comments -->
                            <div>
                                <label for="review_comments" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Review Comments & Feedback</label>
                                <textarea name="review_comments" id="review_comments" rows="6" 
                                          placeholder="Provide detailed feedback for the student..."
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-900 dark:text-white">{{ old('review_comments', $document->review_comments) }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Submit Review
                            </button>
                        </form>
                    </div>

                    <!-- Document Timeline -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <div class="p-1 bg-blue-100 dark:bg-blue-900/50 rounded-lg mr-2">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Timeline
                        </h3>

                        <div class="space-y-4">
                            <!-- Submitted -->
                            <div class="flex items-start gap-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full flex-shrink-0 mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Document Submitted</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $document->submission_date->format('M j, Y \a\t g:i A') }}</p>
                                </div>
                            </div>

                            @if($document->reviewed_at)
                                <!-- Reviewed -->
                                <div class="flex items-start gap-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full flex-shrink-0 mt-2"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Reviewed</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $document->reviewed_at->format('M j, Y \a\t g:i A') }}</p>
                                        @if($document->reviewer)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">by {{ $document->reviewer->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <!-- Pending Review -->
                                <div class="flex items-start gap-3">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full flex-shrink-0 mt-2 animate-pulse"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Pending Review</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Awaiting faculty review</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <div class="p-1 bg-gray-100 dark:bg-gray-700 rounded-lg mr-2">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            Document Info
                        </h3>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Days since submission:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $document->submission_date->diffInDays(now()) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Uploaded files:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $document->uploaded_files ? count($document->uploaded_files) : 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Document type:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $document->document_type_label }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
