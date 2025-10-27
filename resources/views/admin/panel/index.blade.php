<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🧑‍⚖️ {{ __('Panel Assignment & Defense Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                <!-- Total Assignments -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_assignments'] }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Assignments</h3>
                </div>

                <!-- Scheduled -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-6 border border-green-200/50 dark:border-green-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-green-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 6v6a1 1 0 001 1h4a1 1 0 001-1v-6M8 13h8"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['scheduled_defenses'] }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Scheduled</h3>
                </div>

                <!-- Completed -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200/50 dark:border-purple-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-purple-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['completed_defenses'] }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Completed</h3>
                </div>

                <!-- Defended -->
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-2xl p-6 border border-emerald-200/50 dark:border-emerald-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-emerald-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['defended_defenses'] }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Defended</h3>
                </div>

                <!-- Upcoming -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-2xl p-6 border border-orange-200/50 dark:border-orange-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-orange-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['upcoming_defenses'] }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Upcoming</h3>
                </div>

                <!-- Overdue -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-2xl p-6 border border-red-200/50 dark:border-red-700/50 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-red-500 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['overdue_defenses'] }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Overdue</h3>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Panel Assignment Management</h3>
                        <p class="text-gray-600 dark:text-gray-400">Schedule thesis defenses and assign panel members</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.records') }}?tab=thesis_documents&document_type=panel_assignment&status=approved" 
                           class="bg-yellow-600 hover:bg-yellow-700 text-gray-900 dark:text-white px-6 py-3 rounded-lg font-medium transition duration-200 shadow-sm flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            View Student Requests
                        </a>
                        <a href="{{ route('admin.panel.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white px-6 py-3 rounded-lg font-medium transition duration-200 shadow-sm flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Schedule Defense
                        </a>
                    </div>
                </div>
            </div>

            <!-- Students Ready for Defense -->
            @if($studentsReadyForDefense->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Students Ready for Defense ({{ $studentsReadyForDefense->count() }})
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($studentsReadyForDefense as $student)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $student->name }}</h4>
                                <span class="text-xs bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 px-2 py-1 rounded-full">Ready</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $student->email }}</p>
                            <a href="{{ route('admin.panel.create', ['student_id' => $student->id, 'thesis_id' => $student->thesisDocuments->where('document_type', 'final_manuscript')->where('status', 'approved')->first()?->id]) }}" 
                               class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 text-center inline-block">
                                Schedule Final Defense
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Student Panel Assignment Requests -->
            @if($studentPanelRequests->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        Panel Assignment Requests Awaiting Review ({{ $studentPanelRequests->count() }})
                    </h3>
                    <a href="{{ route('admin.records') }}?tab=thesis_documents&document_type=panel_assignment&status=pending" 
                       class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                        View All →
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($studentPanelRequests as $request)
                        <div class="bg-gradient-to-br from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-700">
                            <!-- Header with student info and status -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $request->user->name }}</h4>
                                        <span class="text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-2 py-1 rounded-full">Admin</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $request->user->email }}</p>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <span class="text-xs bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 px-2 py-1 rounded-full">
                                        {{ $request->submission_date->diffForHumans() }}
                                    </span>
                                    <span class="text-xs bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 px-2 py-1 rounded-full">
                                        Pending
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Document details -->
                            <div class="mb-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $request->title }}</p>
                                </div>
                                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"></path>
                                        </svg>
                                        {{ $request->submission_date->format('M d, Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $request->submission_date->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-xs bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 px-2 py-1 rounded-full">Pending</span>
                                    <span class="text-xs bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 px-2 py-1 rounded-full">Awaiting Review</span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Document ID: #{{ $request->id }}</div>
                            </div>

                            <!-- Show preferred panel members if available -->
                            @if($request->panel_members && !empty($request->panel_members))
                                <div class="mb-3">
                                    <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Preferred Panel:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($request->panel_members as $key => $member)
                                            @if(isset($member['name']) && $member['name'])
                                                <span class="text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-2 py-1 rounded-full">
                                                    {{ $member['name'] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                <!-- View Details Button -->
                                <a href="{{ route('admin.records.show-document', $request) }}" 
                                   class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-lg text-xs font-medium transition duration-200 text-center flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>
                                
                                <!-- Action Buttons Row -->
                                <div class="flex gap-2">
                                    <!-- Approve Button -->
                                    <form action="{{ route('admin.panel-requests.approve', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-xs font-medium transition duration-200 text-center flex items-center justify-center gap-1"
                                                onclick="return confirm('Approve this panel assignment request?')">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Approve
                                        </button>
                                    </form>
                                    
                                    <!-- Reject Button -->
                                    <button type="button" 
                                            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-xs font-medium transition duration-200 text-center flex items-center justify-center gap-1"
                                            onclick="showRejectModal({{ $request->id }}, '{{ $request->user->name }}')">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Return
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Panel Assignments Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Panel Assignments</h3>
                </div>

                @if($assignments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thesis Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Defense Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Defense Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Panel Chair</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Secretary</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($assignments as $assignment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                                    <span class="text-blue-600 dark:text-blue-400 font-medium text-sm">
                                                        {{ substr($assignment->student->name, 0, 2) }}
                                                    </span>
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
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                @if($assignment->defense_type === 'proposal_defense') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                                @elseif($assignment->defense_type === 'final_defense') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                                @elseif($assignment->defense_type === 'redefense') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                                                @elseif($assignment->defense_type === 'oral_defense') bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200
                                                @elseif($assignment->defense_type === 'thesis_defense') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-200
                                                @endif">
                                                {{ $assignment->defense_type_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $assignment->defense_date?->format('M d, Y') }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $assignment->defense_date?->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $assignment->panelChair?->name ?? 'Not assigned' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $assignment->secretary?->name ?? 'Not assigned' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $assignment->status_color }}-100 text-{{ $assignment->status_color }}-800 dark:bg-{{ $assignment->status_color }}-900/50 dark:text-{{ $assignment->status_color }}-200">
                                                {{ $assignment->status_label }}
                                            </span>
                                            @if($assignment->status === 'completed' && $assignment->completed_at)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Completed: {{ $assignment->formatted_completed_at ?? 'Unknown' }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.panel.show', $assignment) }}" 
                                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">View</a>
                                                <a href="{{ route('admin.panel.edit', $assignment) }}" 
                                                   class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">Edit</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $assignments->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Panel Assignments</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by scheduling the first thesis defense.</p>
                        <a href="{{ route('admin.panel.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white font-medium rounded-lg transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Schedule First Defense
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/50 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white text-center mb-2">Return Panel Assignment Request</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-4">
                    Return this panel assignment request to <span id="studentName" class="font-medium"></span> for revision.
                </p>
                
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="comments" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Reason for Return (Required)
                        </label>
                        <textarea id="comments" 
                                  name="comments" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Please provide specific feedback on what needs to be revised..."
                                  required></textarea>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" 
                                onclick="closeRejectModal()"
                                class="flex-1 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                            Return Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showRejectModal(documentId, studentName) {
            document.getElementById('studentName').textContent = studentName;
            document.getElementById('rejectForm').action = `/admin/panel-requests/${documentId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('comments').value = '';
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
</x-app-layout> 
