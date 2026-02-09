<div 
    x-data="{ show: false }"
    x-init="
        // Always start closed on page load or Livewire re-render
        show = false;
        window.openNotifications = () => { show = true };
    "
>
    <!-- Notifications Modal -->
    <div 
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center"
        @click.self="show = false"
        @keydown.escape.window="show = false"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"></div>

        <!-- Modal -->
        <div 
            class="relative w-full max-w-2xl max-h-[90vh] mx-4 bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden"
            @click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-500 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Notifications
                            @if($this->unreadCount > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2.5 py-0.5 text-xs font-bold text-white bg-red-500 rounded-full">
                                    {{ $this->unreadCount }}
                                </span>
                            @endif
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            @if($this->unreadCount > 0)
                                You have {{ $this->unreadCount }} unread notification{{ $this->unreadCount > 1 ? 's' : '' }}
                            @else
                                All caught up!
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($this->unreadCount > 0)
                        <button 
                            wire:click="markAllAsRead"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors"
                        >
                            Mark all as read
                        </button>
                    @endif
                    <button 
                        type="button"
                        class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        @click="show = false"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
                @if($notifications->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($notifications as $notification)
                            <div 
                                class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ !$notification->is_read ? 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500' : '' }}"
                            >
                                <div class="flex items-start gap-3">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 mt-1">
                                        @switch($notification->type)
                                            @case('welcome')
                                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10.011 10.011 0 0012 2z"></path>
                                                    </svg>
                                                </div>
                                                @break
                                            @case('follow')
                                                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9a3 3 0 11-3-3 3 3 0 013 3zm-2 8a4 4 0 00-8 0v1h8zM8 9a3 3 0 11-3-3 3 3 0 013 3zM4 17a4 4 0 014-4"></path>
                                                    </svg>
                                                </div>
                                                @break
                                            @case('new_post_from_following')
                                                <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5h14M5 9h14M5 15h10M5 19h6"></path>
                                                    </svg>
                                                </div>
                                                @break
                                            @default
                                                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10.011 10.011 0 0012 2z"></path>
                                                    </svg>
                                                </div>
                                        @endswitch
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ $notification->message }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                            @if($notification->post)
                                                <a 
                                                    href="{{ route('posts.show', $notification->post->slug) }}"
                                                    class="text-xs text-blue-600 dark:text-blue-400 hover:underline"
                                                    @click="show = false"
                                                >
                                                    View post →
                                                </a>
                                            @endif
                                        </div>
                                        @if(!$notification->is_read)
                                            <button 
                                                wire:click="markAsRead({{ $notification->id }})"
                                                class="mt-2 text-xs text-blue-600 dark:text-blue-400 hover:underline"
                                            >
                                                Mark as read
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($notifications->hasPages())
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No notifications yet</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">You'll see updates here when something new happens.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
