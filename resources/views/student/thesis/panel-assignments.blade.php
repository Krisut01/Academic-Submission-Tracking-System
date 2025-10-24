@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">My Panel Assignments</h1>
        <p class="text-gray-600 dark:text-gray-400">Track the status of your panel assignments and reviews</p>
    </div>

    <!-- Panel Assignments List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        @if($panelAssignments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thesis Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Defense Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Panel Chair</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Review Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($panelAssignments as $assignment)
                            @php
                                $completedReviews = $assignment->reviews->whereIn('status', ['approved', 'rejected', 'needs_revision'])->count();
                                $totalReviews = $assignment->reviews->count();
                                $progressPercentage = $totalReviews > 0 ? ($completedReviews / $totalReviews) * 100 : 0;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($assignment->thesis_title, 50) }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Submitted {{ $assignment->created_at->format('M j, Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $assignment->defense_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $assignment->panelChair ? $assignment->panelChair->name : 'Not assigned' }}
                                    </div>
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
                                    <div class="flex items-center">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600 dark:text-gray-400">{{ $completedReviews }}/{{ $totalReviews }} reviews</span>
                                                <span class="text-gray-900 dark:text-white font-medium">{{ round($progressPercentage) }}%</span>
                                            </div>
                                            <div class="mt-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                                     style="width: {{ $progressPercentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('student.thesis.panel-assignment.show', $assignment) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        View Details
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
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You haven't submitted any panel assignments yet.</p>
                <div class="mt-6">
                    <a href="{{ route('student.thesis.create', ['type' => 'panel_assignment']) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Submit Panel Assignment
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
