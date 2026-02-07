<div>
    <!-- Notifications Overlay -->
    <div 
        x-data="{
            show: @entangle('showNotifications'),
            progress: 100,
            autoCloseTimer: null,
            startAutoClose() {
                if (this.autoCloseTimer) clearInterval(this.autoCloseTimer);
                this.progress = 100;
                const duration = 8000; // 8 seconds
                const interval = 50; // Update every 50ms
                const decrement = (100 / duration) * interval;
                
                this.autoCloseTimer = setInterval(() => {
                    this.progress -= decrement;
                    if (this.progress <= 0) {
                        clearInterval(this.autoCloseTimer);
                        this.show = false;
                        $wire.closeNotifications();
                    }
                }, interval);
            },
            stopAutoClose() {
                if (this.autoCloseTimer) {
                    clearInterval(this.autoCloseTimer);
                    this.autoCloseTimer = null;
                }
            }
        }"
        x-show="show"
        x-init="
            $watch('show', value => {
                if (value) {
                    startAutoClose();
                } else {
                    stopAutoClose();
                }
            });
        "
        @mouseenter="stopAutoClose()"
        @mouseleave="if (show) startAutoClose()"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-gray-900/90 backdrop-blur-sm"
        @click.self="show = false; $wire.closeNotifications(); stopAutoClose()"
        style="display: none;"
    >
        <!-- Notifications Container -->
        <div 
            class="fixed inset-0 flex items-start justify-center pt-20 px-4"
            wire:click.stop
        >
            <div 
                class="w-full max-w-2xl transform transition-all duration-500"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 -translate-y-10 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-10 scale-95"
            >
                <!-- Progress Bar -->
                <div class="h-1 bg-gray-800 rounded-t-xl overflow-hidden">
                    <div 
                        class="h-full bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 transition-all duration-50 ease-linear"
                        :style="`width: ${progress}%`"
                    ></div>
                </div>

                <!-- Header -->
                <div class="bg-gray-800 rounded-t-xl p-4 border-b border-gray-700 flex items-center justify-between shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-lg animate-pulse"></div>
                            <div class="relative bg-gradient-to-br from-blue-600 to-purple-600 p-2 rounded-full">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                                Notifications
                                @if($this->unreadCount > 0)
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full animate-pulse">
                                        {{ $this->unreadCount }}
                                    </span>
                                @endif
                            </h2>
                            <p class="text-sm text-gray-400 mt-1">
                                @if($this->unreadCount > 0)
                                    You have <span class="font-semibold text-blue-400">{{ $this->unreadCount }}</span> unread notification{{ $this->unreadCount > 1 ? 's' : '' }}
                                @else
                                    All caught up! 🎉
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($this->unreadCount > 0)
                            <button 
                                wire:click="markAllAsRead"
                                class="px-3 py-1.5 text-xs bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg border border-blue-500/50 transition-all transform hover:scale-105 active:scale-95 shadow-lg"
                            >
                                Mark all as read
                            </button>
                        @endif
                        <button 
                            wire:click="closeNotifications"
                            x-on:click="stopAutoClose()"
                            class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-all transform hover:rotate-90 active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- List -->
                <div class="bg-gray-800 rounded-b-xl max-h-[60vh] overflow-y-auto shadow-2xl">
                    @if($notifications->count() > 0)
                        <div class="divide-y divide-gray-700">
                            @foreach($notifications as $index => $notification)
                                <div 
                                    class="flex items-start gap-3 p-4 transition-all duration-300 hover:bg-gray-700/50 {{ $notification->is_read ? 'bg-gray-800' : 'bg-gray-900/80 border-l-4 border-blue-500' }}"
                                    x-data="{ 
                                        show: false,
                                        init() {
                                            setTimeout(() => {
                                                this.show = true;
                                            }, {{ $index * 50 }});
                                        }
                                    }"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-x-4"
                                    x-transition:enter-end="opacity-100 translate-x-0"
                                    x-show="show"
                                >
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

