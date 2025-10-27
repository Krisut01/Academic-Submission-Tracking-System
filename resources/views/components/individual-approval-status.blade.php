@props(['document'])

@php
    $approvalStatus = $document->calculateOverallApprovalStatus();
    $totalApprovals = $approvalStatus['total_approvals'];
    $approvedCount = $approvalStatus['approved_count'];
    $completionPercentage = $totalApprovals > 0 ? round(($approvedCount / $totalApprovals) * 100, 1) : 0;
@endphp

<div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-700">
    <div class="flex items-center justify-between mb-6">
        <h4 class="text-xl font-bold text-gray-900 dark:text-white">👥 Individual Faculty Approvals</h4>
        <div class="flex items-center space-x-3">
            <div class="text-right">
                <div class="text-2xl font-bold text-{{ $approvalStatus['overall_status'] === 'approved' ? 'green' : ($approvalStatus['overall_status'] === 'returned_for_revision' ? 'red' : 'yellow') }}-600 dark:text-{{ $approvalStatus['overall_status'] === 'approved' ? 'green' : ($approvalStatus['overall_status'] === 'returned_for_revision' ? 'red' : 'yellow') }}-400">
                    {{ $completionPercentage }}%
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $approvedCount }}/{{ $totalApprovals }} Approved</p>
            </div>
            <div class="w-16 h-16 relative">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-gray-300 dark:text-gray-600" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                    <path class="text-{{ $approvalStatus['overall_status'] === 'approved' ? 'green' : ($approvalStatus['overall_status'] === 'returned_for_revision' ? 'red' : 'yellow') }}-600 dark:text-{{ $approvalStatus['overall_status'] === 'approved' ? 'green' : ($approvalStatus['overall_status'] === 'returned_for_revision' ? 'red' : 'yellow') }}-400" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="{{ $completionPercentage }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $completionPercentage }}%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($approvalStatus['individual_approvals'] as $approval)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h5 class="font-semibold text-gray-900 dark:text-white">{{ $approval['faculty_name'] }}</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            @switch($approval['faculty_role'])
                                @case('adviser')
                                    👨‍🏫 Adviser
                                    @break
                                @case('reviewer')
                                    👩‍💼 Reviewer
                                    @break
                                @case('panel_chair')
                                    🪑 Panel Chair
                                    @break
                                @case('secretary')
                                    📝 Secretary
                                    @break
                                @case('panel_member')
                                    👥 Panel Member
                                    @break
                                @default
                                    👤 {{ ucfirst(str_replace('_', ' ', $approval['faculty_role'])) }}
                            @endswitch
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @if($approval['status'] === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Approved
                        </span>
                    @elseif($approval['status'] === 'returned_for_revision')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Needs Revision
                        </span>
                    @elseif($approval['status'] === 'under_review')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Under Review
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Pending
                        </span>
                    @endif
                </div>
            </div>
            @if($approval['approved_at'])
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Reviewed on {{ \Carbon\Carbon::parse($approval['approved_at'])->format('M j, Y g:i A') }}
                </div>
            @endif
            @if($approval['comments'])
                <div class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-2 rounded">
                    {{ $approval['comments'] }}
                </div>
            @endif
        </div>
        @empty
        <div class="text-center py-8">
            <p class="text-gray-500 dark:text-gray-400">No faculty approvals yet.</p>
        </div>
        @endforelse
    </div>
</div>


