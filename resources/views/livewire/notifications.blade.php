<div>
    <!-- Notifications Overlay -->
    <div 
        x-data="{ show: @entangle('showNotifications') }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-gray-900/90 backdrop-blur-sm"
        @click.self="show = false; $wire.closeNotifications()"
    >
        <!-- Notifications Container -->
        <div 
            class="fixed inset-0 flex items-start justify-center pt-20 px-4"
            wire:click.stop
        >
            <div class="w-full max-w-2xl">
                <!-- Header -->
                <div class="bg-gray-800 rounded-t-xl p-4 border-b border-gray-700 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Notifications</h2>
                        <p class="text-sm text-gray-400 mt-1">
                            You have <span class="font-semibold">{{ $this->unreadCount }}</span> unread notifications
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button 
                            wire:click="markAllAsRead"
                            class="px-3 py-1.5 text-xs bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg border border-gray-600 transition-colors"
                        >
                            Mark all as read
                        </button>
                        <button 
                            wire:click="closeNotifications"
                            class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- List -->
                <div class="bg-gray-800 rounded-b-xl max-h-[60vh] overflow-y-auto">
                    @if($notifications->count() > 0)
                        <div class="divide-y divide-gray-700">
                            @foreach($notifications as $notification)
                                <div class="flex items-start gap-3 p-4 {{ $notification->is_read ? 'bg-gray-800' : 'bg-gray-900/80' }}">
                                    <div class="mt-1">
                                        @switch($notification->type)
                                            @case('welcome')
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600/20 text-blue-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10.011 10.011 0 0012 2z"></path>
                                                    </svg>
                                                </span>
                                                @break
                                            @case('follow')
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-600/20 text-green-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9a3 3 0 11-3-3 3 3 0 013 3zm-2 8a4 4 0 00-8 0v1h8zM8 9a3 3 0 11-3-3 3 3 0 013 3zM4 17a4 4 0 014-4"></path>
                                                    </svg>
                                                </span>
                                                @break
                                            @case('new_post_from_following')
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-600/20 text-purple-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5h14M5 9h14M5 15h10M5 19h6"></path>
                                                    </svg>
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-600/40 text-gray-200">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10.011 10.011 0 0012 2z"></path>
                                                    </svg>
                                                </span>
                                        @endswitch
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="text-sm text-gray-100">
                                                {{ $notification->message }}
                                            </p>
                                            <span class="ml-2 text-xs text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        @if($notification->post)
                                            <a 
                                                href="{{ route('posts.show', $notification->post->slug) }}"
                                                class="inline-flex items-center text-xs text-blue-400 hover:text-blue-300 mt-1"
                                                wire:click="closeNotifications"
                                            >
                                                View post
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @endif

                                        @if(!$notification->is_read)
                                            <button 
                                                wire:click="markAsRead({{ $notification->id }})"
                                                class="mt-2 text-xs text-gray-300 hover:text-gray-100 underline"
                                            >
                                                Mark as read
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="p-4 border-t border-gray-700">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10.011 10.011 0 0012 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-400 mb-2">No notifications yet</h3>
                            <p class="text-sm text-gray-500">You’ll see updates here when something new happens.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

