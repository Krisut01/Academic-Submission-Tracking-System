@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Panel Assignment Reviews</h1>
        <p class="text-gray-600 dark:text-gray-400">Review and approve panel assignments assigned to you</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Assignments</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_assignments'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Reviews</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['pending_reviews'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed Reviews</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['completed_reviews'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select name="status" id="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">All Statuses</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="postponed" {{ request('status') === 'postponed' ? 'selected' : '' }}>Postponed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label for="defense_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Defense Type</label>
                <select name="defense_type" id="defense_type" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">All Types</option>
                    <option value="Proposal Defense" {{ request('defense_type') === 'Proposal Defense' ? 'selected' : '' }}>Proposal Defense</option>
                    <option value="Final Defense" {{ request('defense_type') === 'Final Defense' ? 'selected' : '' }}>Final Defense</option>
                    <option value="Comprehensive Defense" {{ request('defense_type') === 'Comprehensive Defense' ? 'selected' : '' }}>Comprehensive Defense</option>
                </select>
            </div>

            <div>
                <label for="student_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Student</label>
                <input type="text" name="student_search" id="student_search" value="{{ request('student_search') }}" 
                       placeholder="Student name or email" 
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Panel Assignments List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        @if($panelAssignments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thesis Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Defense Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Your Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Review Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($panelAssignments as $assignment)
                            @php
                                $userRole = auth()->id() === $assignment->panel_chair_id ? 'Panel Chair' : 
                                           (auth()->id() === $assignment->secretary_id ? 'Secretary' : 'Panel Member');
                                $userReview = $assignment->reviews->where('reviewer_id', auth()->id())->first();
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                    {{ substr($assignment->student->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $assignment->student->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $assignment->student->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ Str::limit($assignment->thesis_title, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $assignment->defense_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                        {{ $userRole }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($assignment->status === 'scheduled') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($assignment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($assignment->status === 'postponed') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @endif">
                                        {{ ucfirst($assignment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($userReview)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($userReview->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($userReview->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @elseif($userReview->status === 'needs_revision') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $userReview->status)) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                            Not Started
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('faculty.panel-assignments.show', $assignment) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $panelAssignments->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No panel assignments</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You haven't been assigned to any panel assignments yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
