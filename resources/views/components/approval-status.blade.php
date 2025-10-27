@props(['approvalStatus', 'title' => 'Approval Status'])

<div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-700">
    <div class="flex items-center justify-between mb-6">
        <h4 class="text-xl font-bold text-gray-900 dark:text-white">📋 {{ $title }}</h4>
        <div class="flex items-center space-x-3">
            <div class="text-right">
                <div class="text-2xl font-bold text-{{ $approvalStatus['overall_status'] === 'approved' ? 'green' : ($approvalStatus['overall_status'] === 'partial' ? 'yellow' : 'gray') }}-600 dark:text-{{ $approvalStatus['overall_status'] === 'approved' ? 'green' : ($approvalStatus['overall_status'] === 'partial' ? 'yellow' : 'gray') }}-400">
                    {{ $approvalStatus['completion_percentage'] }}%
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Complete</div>
            </div>
            <div class="w-16 h-16 relative">
                <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-gray-300 dark:text-gray-600" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                    <path class="text-{{ $approvalStatus['overall_status'] === 'approved' ? 'green' : ($approvalStatus['overall_status'] === 'partial' ? 'yellow' : 'gray') }}-600 dark:text-{{ $approvalStatus['overall_status'] === 'approved' ? 'green' : ($approvalStatus['overall_status'] === 'partial' ? 'yellow' : 'gray') }}-400" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="{{ $approvalStatus['completion_percentage'] }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Approval Details -->
    <div class="space-y-4">
        <!-- Adviser Approval -->
        @if($approvalStatus['adviser']['name'] !== 'Not Assigned')
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h5 class="font-semibold text-gray-900 dark:text-white">{{ $approvalStatus['adviser']['name'] }}</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $approvalStatus['adviser']['role'] }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @if($approvalStatus['adviser']['status'] === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Approved
                        </span>
                    @elseif($approvalStatus['adviser']['status'] === 'needs_revision')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Needs Revision
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Pending
                        </span>
                    @endif
                </div>
            </div>
            @if($approvalStatus['adviser']['approved_at'])
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Approved on {{ \Carbon\Carbon::parse($approvalStatus['adviser']['approved_at'])->format('M j, Y g:i A') }}
                </div>
            @endif
            @if($approvalStatus['adviser']['comments'])
                <div class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-2 rounded">
                    {{ $approvalStatus['adviser']['comments'] }}
                </div>
            @endif
        </div>
        @endif

        <!-- Reviewer Approval -->
        @if($approvalStatus['reviewer']['name'] !== 'Not Assigned')
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h5 class="font-semibold text-gray-900 dark:text-white">{{ $approvalStatus['reviewer']['name'] }}</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $approvalStatus['reviewer']['role'] }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @if($approvalStatus['reviewer']['status'] === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Approved
                        </span>
                    @elseif($approvalStatus['reviewer']['status'] === 'needs_revision')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Needs Revision
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Pending
                        </span>
                    @endif
                </div>
            </div>
            @if($approvalStatus['reviewer']['approved_at'])
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Approved on {{ \Carbon\Carbon::parse($approvalStatus['reviewer']['approved_at'])->format('M j, Y g:i A') }}
                </div>
            @endif
            @if($approvalStatus['reviewer']['comments'])
                <div class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-2 rounded">
                    {{ $approvalStatus['reviewer']['comments'] }}
                </div>
            @endif
        </div>
        @endif

        <!-- Panel Members Approval -->
        @if(!empty($approvalStatus['panel_members']))
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <h5 class="font-semibold text-gray-900 dark:text-white mb-3">Panel Members</h5>
            <div class="space-y-3">
                @foreach($approvalStatus['panel_members'] as $member)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="font-medium text-gray-900 dark:text-white">{{ $member['name'] }}</h6>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $member['role'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($member['status'] === 'approved')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Approved
                            </span>
                        @elseif($member['status'] === 'needs_revision')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Needs Revision
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Pending
                            </span>
                        @endif
                    </div>
                </div>
                @if($member['approved_at'])
                    <div class="ml-11 text-xs text-gray-500 dark:text-gray-400">
                        Approved on {{ \Carbon\Carbon::parse($member['approved_at'])->format('M j, Y g:i A') }}
                    </div>
                @endif
                @if($member['comments'])
                    <div class="ml-11 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-2 rounded">
                        {{ $member['comments'] }}
                    </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

