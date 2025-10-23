<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Manage and view all your notifications</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if($notifications->count() > 0)
                            <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-gray-900 dark:text-white rounded-lg font-medium transition-colors">
                                    Mark All as Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <form method="GET" action="{{ route('notifications.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label for="unread_only" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter</label>
                        <select name="unread_only" id="unread_only" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Notifications</option>
                            <option value="1" {{ request('unread_only') == '1' ? 'selected' : '' }}>Unread Only</option>
                        </select>
                    </div>
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</label>
                        <select name="priority" id="priority" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Priorities</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-gray-900 dark:text-white rounded-lg font-medium transition-colors">
                            Apply Filters
                        </button>
                    </div>
                    @if(request('unread_only') || request('priority'))
                        <div>
                            <a href="{{ route('notifications.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg font-medium transition-colors">
                                Clear Filters
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Notifications List -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                @if($notifications->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($notifications as $notification)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ !$notification->is_read ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ $notification->title }}
                                            </h3>
                                            @if(!$notification->is_read)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                                                    New
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @if($notification->priority === 'urgent') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                                @elseif($notification->priority === 'high') bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-300
                                                @elseif($notification->priority === 'normal') bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300
                                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                                @endif">
                                                {{ ucfirst($notification->priority) }}
                                            </span>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-400 mb-3">{{ $notification->message }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $notification->created_at->format('M j, Y \a\t g:i A') }}
                                            </span>
                                            <div class="flex items-center space-x-2">
                                                @if(!$notification->is_read)
                                                    <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors">
                                                            Mark as Read
                                                        </button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium transition-colors">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($notifications->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $notifications->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto h-12 w-12 text-gray-400">
                            <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No notifications</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if(request('unread_only') || request('priority'))
                                No notifications match your current filters.
                            @else
                                You're all caught up! No notifications to display.
                            @endif
                        </p>
                        @if(request('unread_only') || request('priority'))
                            <div class="mt-6">
                                <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-900 dark:text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    View All Notifications
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
