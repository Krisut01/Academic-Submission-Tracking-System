<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🔔 {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Actions -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Your Notifications</h1>
                    <p class="text-gray-600 dark:text-gray-400">Stay updated with your thesis progress and important announcements</p>
                </div>
                
                <div class="flex items-center space-x-3">
                    <!-- Mark All Read Button -->
                    @if($notifications->where('is_read', false)->count() > 0)
                    <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mark All Read
                        </button>
                    </form>
                    @endif
                    
                    <!-- Filter Buttons -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('notifications.index') }}" 
                           class="px-3 py-2 text-sm font-medium rounded-lg {{ !request('unread_only') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }} transition-colors">
                            All
                        </a>
                        <a href="{{ route('notifications.index', ['unread_only' => 1]) }}" 
                           class="px-3 py-2 text-sm font-medium rounded-lg {{ request('unread_only') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }} transition-colors">
                            Unread
                        </a>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            @if($notifications->count() > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden {{ !$notification->is_read ? 'ring-2 ring-blue-500/20' : '' }}">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-4 flex-1">
                                        <!-- Notification Icon -->
                                        <div class="p-3 rounded-xl {{ $notification->is_read ? 'bg-gray-100 dark:bg-gray-700' : 'bg-blue-100 dark:bg-blue-900/50' }}">
                                            @if($notification->type === 'defense_scheduled')
                                                <svg class="w-6 h-6 {{ $notification->is_read ? 'text-gray-600 dark:text-gray-400' : 'text-blue-600 dark:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"></path>
                                                </svg>
                                            @elseif($notification->type === 'thesis_submitted')
                                                <svg class="w-6 h-6 {{ $notification->is_read ? 'text-gray-600 dark:text-gray-400' : 'text-green-600 dark:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            @elseif($notification->type === 'panel_assignment')
                                                <svg class="w-6 h-6 {{ $notification->is_read ? 'text-gray-600 dark:text-gray-400' : 'text-purple-600 dark:text-purple-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6 {{ $notification->is_read ? 'text-gray-600 dark:text-gray-400' : 'text-indigo-600 dark:text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        </div>

                                        <!-- Notification Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-2">
                                                <h3 class="text-lg font-semibold {{ $notification->is_read ? 'text-gray-700 dark:text-gray-300' : 'text-gray-900 dark:text-white' }}">
                                                    {{ $notification->title }}
                                                </h3>
                                                @if(!$notification->is_read)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">
                                                        New
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-gray-600 dark:text-gray-400 mb-3 leading-relaxed">
                                                {{ $notification->message }}
                                            </p>
                                            
                                            <!-- Notification Metadata -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-500">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $notification->created_at->format('M j, Y g:i A') }}
                                                    </span>
                                                    <span class="text-gray-400">•</span>
                                                    <span>{{ $notification->created_at->diffForHumans() }}</span>
                                                </div>
                                                
                                                <!-- Priority Badge -->
                                                @if($notification->priority === 'high')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                                                        High Priority
                                                    </span>
                                                @elseif($notification->priority === 'urgent')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-200">
                                                        Urgent
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex items-center space-x-2 ml-4">
                                        @if(!$notification->is_read)
                                            <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                                        title="Mark as read">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                                                    title="Delete notification"
                                                    onclick="return confirm('Delete this notification?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    @if($notification->type === 'defense_scheduled' && isset($notification->data['type']) && $notification->data['type'] === 'final_defense_scheduled')
                                        <!-- Defense Scheduled - Go to Defense Details -->
                                        <a href="{{ route('student.thesis.defense') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"></path>
                                            </svg>
                                            View Defense Details
                                        </a>
                                    @else
                                        <!-- Default Action - Go to Thesis History -->
                                        <a href="{{ route('student.thesis.history') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            View Thesis Details
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="mt-8">
                        {{ $notifications->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="px-8 py-12 text-center">
                        <div class="p-6 bg-gray-100 dark:bg-gray-700 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM12 6V4a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2v-2h6a2 2 0 002-2V8a2 2 0 00-2-2h-6z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            @if(request('unread_only'))
                                No Unread Notifications
                            @else
                                No Notifications Yet
                            @endif
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                            @if(request('unread_only'))
                                You're all caught up! All your notifications have been read.
                            @else
                                You'll receive notifications here about your thesis progress, defense schedules, and important updates.
                            @endif
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('student.thesis.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Go to Thesis
                            </a>
                            @if(request('unread_only'))
                            <a href="{{ route('notifications.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM12 6V4a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2v-2h6a2 2 0 002-2V8a2 2 0 00-2-2h-6z"></path>
                                </svg>
                                View All Notifications
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
