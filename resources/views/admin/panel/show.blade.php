<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📋 {{ __('Panel Assignment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.panel') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Panel Assignments
                </a>
            </div>

            <!-- Assignment Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $assignment->thesis_title }}</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Defense Assignment #{{ $assignment->id }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Status Badge -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($assignment->status === 'scheduled') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                @elseif($assignment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                @elseif($assignment->status === 'postponed') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                                @else bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 @endif">
                                {{ ucfirst($assignment->status) }}
                            </span>
                            
                            <!-- Result Badge (if completed) -->
                            @if($assignment->status === 'completed' && $assignment->result)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($assignment->result === 'passed') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                    @elseif($assignment->result === 'failed') bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200 @endif">
                                    {{ ucfirst($assignment->result) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Assignment Info Grid -->
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Student Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Student Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Student Name</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->student->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->student->email }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Student ID</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->student->student_id ?? 'Not specified' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Defense Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Defense Details</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Defense Date</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->defense_date->format('F j, Y') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Defense Time</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->defense_date->format('h:i A') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Venue</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->defense_venue }}</p>
                                </div>
                                @if($assignment->is_upcoming)
                                    <div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-200">
                                            Upcoming ({{ $assignment->defense_date->diffForHumans() }})
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Panel Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Panel Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Panel Chair</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->panelChair->name ?? 'Not assigned' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Secretary</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->secretary->name ?? 'Not assigned' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Members</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ count($assignment->panel_members) }} members</p>
                                </div>
                                @if($assignment->final_grade)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Final Grade</label>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->final_grade }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Members -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Panel Members</h3>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($assignment->panel_members as $member)
                            <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 dark:text-blue-400 font-medium text-sm">
                                        {{ substr($member->name, 0, 2) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $member->email }}</p>
                                    @if($assignment->panel_chair_id == $member->id)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 mt-1">
                                            Chair
                                        </span>
                                    @elseif($assignment->secretary_id == $member->id)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200 mt-1">
                                            Secretary
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Thesis Information -->
            @if($assignment->thesisDocument)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Thesis Information</h3>
                </div>
                <div class="px-6 py-5">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Thesis Title</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->thesisDocument->title }}</p>
                        </div>
                        @if($assignment->thesis_description)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->thesis_description }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Document Type</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->thesisDocument->document_type_label }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Submission Date</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->thesisDocument->submission_date->format('F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Defense Instructions -->
            @if($assignment->defense_instructions)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Defense Instructions</h3>
                </div>
                <div class="px-6 py-5">
                    <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->defense_instructions }}</p>
                </div>
            </div>
            @endif

            <!-- Panel Feedback -->
            @if($assignment->panel_feedback)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Panel Feedback</h3>
                </div>
                <div class="px-6 py-5">
                    <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->panel_feedback }}</p>
                </div>
            </div>
            @endif

            <!-- Assignment Metadata -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assignment Details</h3>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Created By</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->creator->name ?? 'System' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Created Date</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->created_at->format('F j, Y g:i A') }}</p>
                        </div>
                        @if($assignment->updated_at != $assignment->created_at)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</label>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->updated_at->format('F j, Y g:i A') }}</p>
                            </div>
                        @endif
                        @if($assignment->notification_sent_at)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Notifications Sent</label>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $assignment->notification_sent_at->format('F j, Y g:i A') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.panel.edit', $assignment) }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white rounded-xl font-medium transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Assignment
                </a>
                @if($assignment->status === 'scheduled')
                    <form method="POST" action="{{ route('admin.panel.notify', $assignment) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-gray-900 dark:text-white rounded-xl font-medium transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM12 6V4a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2v-2h6a2 2 0 002-2V8a2 2 0 00-2-2h-6z"></path>
                            </svg>
                            Resend Notifications
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
