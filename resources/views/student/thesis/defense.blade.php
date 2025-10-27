<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🎓 {{ $panelAssignment ? $panelAssignment->defense_type_label . ' Schedule' : 'Defense Schedule' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('student.thesis.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Thesis
                </a>
            </div>

            @if($panelAssignment)
                <!-- Defense Scheduled -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/20 dark:to-emerald-800/20 rounded-3xl border border-green-200/50 dark:border-green-700/50 shadow-sm overflow-hidden mb-8">
                    <div class="px-8 py-6 bg-gradient-to-r from-green-500/10 to-emerald-500/10 border-b border-green-200 dark:border-green-700">
                        <div class="flex items-center space-x-4">
                            <div class="p-4 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $panelAssignment->defense_type_label }} Scheduled</h1>
                                <p class="text-green-700 dark:text-green-300 font-medium">Your {{ strtolower($panelAssignment->defense_type_label) }} has been officially scheduled</p>
                            </div>
                        </div>
                    </div>

                    <!-- Defense Details -->
                    <div class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Schedule Information -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">📅 Schedule Details</h3>
                                
                                <!-- Date & Time -->
                                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-xl">
                                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">Defense Date & Time</h4>
                                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                                {{ $panelAssignment->defense_date->format('F j, Y') }}
                                            </p>
                                            <p class="text-lg text-gray-600 dark:text-gray-400">
                                                {{ $panelAssignment->defense_date->format('g:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Venue -->
                                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-purple-100 dark:bg-purple-900/50 rounded-xl">
                                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">Venue</h4>
                                            <p class="text-lg text-gray-600 dark:text-gray-400">{{ $panelAssignment->defense_venue }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-{{ $panelAssignment->status_color }}-100 dark:bg-{{ $panelAssignment->status_color }}-900/50 rounded-xl">
                                            <svg class="w-6 h-6 text-{{ $panelAssignment->status_color }}-600 dark:text-{{ $panelAssignment->status_color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">Status</h4>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $panelAssignment->status_color }}-100 text-{{ $panelAssignment->status_color }}-800 dark:bg-{{ $panelAssignment->status_color }}-900/50 dark:text-{{ $panelAssignment->status_color }}-200">
                                                {{ ucfirst($panelAssignment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel Information -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">👥 Panel Members</h3>
                                
                                <!-- Panel Chair -->
                                @if($panelAssignment->panelChair)
                                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/50 rounded-xl">
                                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">Panel Chair</h4>
                                            <p class="text-lg text-gray-600 dark:text-gray-400">{{ $panelAssignment->panelChair->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-500">{{ $panelAssignment->panelChair->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Secretary -->
                                @if($panelAssignment->secretary)
                                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl">
                                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">Secretary</h4>
                                            <p class="text-lg text-gray-600 dark:text-gray-400">{{ $panelAssignment->secretary->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-500">{{ $panelAssignment->secretary->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Panel Members -->
                                @if($panelMembers->count() > 0)
                                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Panel Members</h4>
                                    <div class="space-y-3">
                                        @foreach($panelMembers as $member)
                                        <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                            <div class="p-2 bg-gray-200 dark:bg-gray-600 rounded-lg">
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $member->name }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-500">{{ $member->email }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Defense Instructions -->
                        @if($panelAssignment->defense_instructions)
                        <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-700">
                            <h4 class="font-semibold text-blue-900 dark:text-blue-200 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Special Instructions
                            </h4>
                            <p class="text-blue-800 dark:text-blue-300">{{ $panelAssignment->defense_instructions }}</p>
                        </div>
                        @endif

                        <!-- Post-Defense Actions -->
                        @if($panelAssignment->status === 'scheduled')
                        @php
                            $isDefenseToday = $panelAssignment->defense_date->isToday();
                            $isDefensePassed = $panelAssignment->defense_date <= now();
                            $isDefenseUpcoming = $panelAssignment->defense_date > now();
                        @endphp
                        
                        <div class="mt-8 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl p-6 border border-green-200 dark:border-green-700">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-green-900 dark:text-green-200 mb-2">🎓 Defense Completed?</h4>
                                    
                                    @if($isDefenseToday)
                                        <p class="text-green-800 dark:text-green-300 mb-3">Today is your defense day! If you have completed your defense, mark it as done to proceed to the next step.</p>
                                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-3 mb-3">
                                            <p class="text-sm text-blue-800 dark:text-blue-300">
                                                <strong>Scheduled for:</strong> {{ $panelAssignment->defense_date->format('F j, Y \a\t g:i A') }}
                                            </p>
                                        </div>
                                    @elseif($isDefensePassed)
                                        <p class="text-green-800 dark:text-green-300 mb-3">Your defense date has passed. If you have completed your defense, mark it as done to proceed to the next step.</p>
                                    @else
                                        <p class="text-green-800 dark:text-green-300 mb-3">Your defense is scheduled for <strong>{{ $panelAssignment->defense_date->format('F j, Y \a\t g:i A') }}</strong>. You can mark it as completed if you've already finished your defense.</p>
                                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-3 mb-3">
                                            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                                <strong>Note:</strong> You can mark your defense as completed even before the scheduled date for flexibility and accessibility.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="ml-4">
                                    <form method="POST" action="{{ route('student.thesis.mark-defense-completed', $panelAssignment) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-colors duration-200 shadow-lg hover:shadow-xl"
                                                onclick="return confirm('Are you sure you have completed your defense? This action will mark your defense as completed and cannot be easily undone.')">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Mark Defense as Completed
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @elseif($panelAssignment->status === 'completed')
                        <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-200 mb-2">✅ Defense Completed</h4>
                                    <p class="text-blue-800 dark:text-blue-300">Your defense has been marked as completed. You can now proceed to submit your approval sheet.</p>
                                </div>
                                <a href="{{ route('student.thesis.create', ['type' => 'approval_sheet']) }}" 
                                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors duration-200 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Submit Approval Sheet
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Approval Status -->
                        @php
                            $approvalStatus = $panelAssignment->getApprovalStatus();
                        @endphp
                        <div class="mt-8 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-700">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white">📋 Approval Status</h4>
                                <div class="flex items-center space-x-3">
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-{{ $panelAssignment->approval_status_color }}-600 dark:text-{{ $panelAssignment->approval_status_color }}-400">
                                            {{ $approvalStatus['completion_percentage'] }}%
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Complete</div>
                                    </div>
                                    <div class="w-16 h-16 relative">
                                        <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                                            <path class="text-gray-300 dark:text-gray-600" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                            <path class="text-{{ $panelAssignment->approval_status_color }}-600 dark:text-{{ $panelAssignment->approval_status_color }}-400" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="{{ $approvalStatus['completion_percentage'] }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Approval Details -->
                            <div class="space-y-4">
                                <!-- Adviser Approval -->
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
                                            @elseif($approvalStatus['adviser']['status'] === 'rejected')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Rejected
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

                                <!-- Panel Chair Approval -->
                                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg">
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-1.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="font-semibold text-gray-900 dark:text-white">{{ $approvalStatus['panel_chair']['name'] }}</h5>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $approvalStatus['panel_chair']['role'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($approvalStatus['panel_chair']['status'] === 'approved')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Approved
                                                </span>
                                            @elseif($approvalStatus['panel_chair']['status'] === 'rejected')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Rejected
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
                                    @if($approvalStatus['panel_chair']['approved_at'])
                                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            Approved on {{ \Carbon\Carbon::parse($approvalStatus['panel_chair']['approved_at'])->format('M j, Y g:i A') }}
                                        </div>
                                    @endif
                                    @if($approvalStatus['panel_chair']['comments'])
                                        <div class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-2 rounded">
                                            {{ $approvalStatus['panel_chair']['comments'] }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Secretary Approval -->
                                @if($approvalStatus['secretary']['name'] !== 'Not Assigned')
                                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 bg-orange-100 dark:bg-orange-900/50 rounded-lg">
                                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5H7l2-5m2 2l4 4"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="font-semibold text-gray-900 dark:text-white">{{ $approvalStatus['secretary']['name'] }}</h5>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $approvalStatus['secretary']['role'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($approvalStatus['secretary']['status'] === 'approved')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Approved
                                                </span>
                                            @elseif($approvalStatus['secretary']['status'] === 'rejected')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Rejected
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
                                    @if($approvalStatus['secretary']['approved_at'])
                                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            Approved on {{ \Carbon\Carbon::parse($approvalStatus['secretary']['approved_at'])->format('M j, Y g:i A') }}
                                        </div>
                                    @endif
                                    @if($approvalStatus['secretary']['comments'])
                                        <div class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-2 rounded">
                                            {{ $approvalStatus['secretary']['comments'] }}
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
                                                @elseif($member['status'] === 'rejected')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Rejected
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

                        <!-- Thesis Information -->
                        <div class="mt-8 bg-gray-50 dark:bg-gray-700/30 rounded-2xl p-6">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">📖 Thesis Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</label>
                                    <p class="text-gray-900 dark:text-white">{{ $panelAssignment->thesis_title }}</p>
                                </div>
                                @if($panelAssignment->thesis_description)
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                                    <p class="text-gray-900 dark:text-white">{{ $panelAssignment->thesis_description }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- No Defense Scheduled -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-12 text-center">
                        <div class="p-6 bg-gray-100 dark:bg-gray-700 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">No Defense Scheduled Yet</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                            Your final defense has not been scheduled yet. Make sure you have submitted all required documents and your panel assignment request has been approved.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('student.thesis.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Check Thesis Status
                            </a>
                            <a href="{{ route('student.thesis.history') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                View History
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
