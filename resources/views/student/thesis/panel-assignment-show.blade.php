@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Panel Assignment Details</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ $panelAssignment->defense_type_label }}</p>
            </div>
            <a href="{{ route('student.thesis.panel-assignments') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                Back to Assignments
            </a>
        </div>
    </div>

    <!-- Assignment Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Thesis Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Thesis Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thesis Title</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $panelAssignment->thesis_title }}</p>
                    </div>
                    @if($panelAssignment->thesis_description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $panelAssignment->thesis_description }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Defense Type</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $panelAssignment->defense_type }}</p>
                    </div>
                    @if($panelAssignment->defense_venue)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Defense Venue</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $panelAssignment->defense_venue }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Submitted Documents -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Submitted Documents</h2>
                @if($panelAssignment->thesisDocument->uploaded_files && count($panelAssignment->thesisDocument->uploaded_files) > 0)
                    <div class="space-y-3">
                        @foreach($panelAssignment->thesisDocument->uploaded_files as $index => $file)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $file['original_name'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($file['size'] / 1024, 2) }} KB</p>
                                    </div>
                                </div>
                                <a href="{{ route('student.thesis.download', [$panelAssignment->thesisDocument, $index]) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors duration-200">
                                    Download
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No documents uploaded</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No documents were uploaded for this panel assignment.</p>
                    </div>
                @endif
            </div>

            <!-- Panel Reviews -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Panel Reviews</h2>
                @if($panelAssignment->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($panelAssignment->reviews as $review)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                                {{ substr($review->reviewer->name, 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $review->reviewer->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $review->reviewer_role_label }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($review->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($review->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @elseif($review->status === 'needs_revision') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                                    </span>
                                </div>
                                
                                @if($review->review_comments)
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comments</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $review->review_comments }}</p>
                                </div>
                                @endif
                                
                                @if($review->recommendations)
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Recommendations</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $review->recommendations }}</p>
                                </div>
                                @endif
                                
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    @if($review->reviewed_at)
                                        Reviewed on {{ $review->reviewed_at->format('F j, Y \a\t g:i A') }}
                                    @else
                                        Pending review
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No reviews yet</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Panel members haven't submitted their reviews yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Assignment Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assignment Status</h3>
                <div class="text-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($panelAssignment->status === 'scheduled') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @elseif($panelAssignment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($panelAssignment->status === 'postponed') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @endif">
                        {{ ucfirst($panelAssignment->status) }}
                    </span>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Created {{ $panelAssignment->created_at->format('F j, Y') }}
                    </p>
                </div>
            </div>

            <!-- Panel Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Panel Information</h3>
                <div class="space-y-3">
                    @if($panelAssignment->panelChair)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Panel Chair</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $panelAssignment->panelChair->name }}</p>
                    </div>
                    @endif
                    @if($panelAssignment->secretary)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Secretary</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $panelAssignment->secretary->name }}</p>
                    </div>
                    @endif
                    @if($panelAssignment->panel_members && count($panelAssignment->panel_members) > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Panel Members</label>
                        <ul class="mt-1 text-sm text-gray-900 dark:text-white space-y-1">
                            @foreach($panelAssignment->panel_members as $memberId)
                                @php
                                    $member = \App\Models\User::find($memberId);
                                @endphp
                                @if($member)
                                    <li>• {{ $member->name }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Review Progress -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Review Progress</h3>
                @php
                    $completedReviews = $panelAssignment->reviews->whereIn('status', ['approved', 'rejected', 'needs_revision'])->count();
                    $totalReviews = $panelAssignment->reviews->count();
                    $progressPercentage = $totalReviews > 0 ? ($completedReviews / $totalReviews) * 100 : 0;
                @endphp
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ round($progressPercentage) }}%</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $completedReviews }} of {{ $totalReviews }} reviews completed</div>
                    <div class="mt-3 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                             style="width: {{ $progressPercentage }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Overall Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Overall Status</h3>
                <div class="text-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($panelAssignment->overall_approval_status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($panelAssignment->overall_approval_status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @elseif($panelAssignment->overall_approval_status === 'conditional') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @endif">
                        {{ ucfirst($panelAssignment->overall_approval_status) }}
                    </span>
                    @if($panelAssignment->overall_approval_status === 'approved')
                        <p class="mt-2 text-sm text-green-600 dark:text-green-400">Congratulations! Your panel assignment has been approved.</p>
                    @elseif($panelAssignment->overall_approval_status === 'rejected')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">Your panel assignment has been rejected. Please review the feedback and resubmit.</p>
                    @elseif($panelAssignment->overall_approval_status === 'conditional')
                        <p class="mt-2 text-sm text-orange-600 dark:text-orange-400">Your panel assignment has conditional approval. Please address the feedback.</p>
                    @else
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Waiting for all panel members to complete their reviews.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
