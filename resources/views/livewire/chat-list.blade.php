<div 
    x-data="{ 
        isOpen: false,
        init() {
            const openHandler = () => {
                // Open on frontend and backend
                this.isOpen = true;
                @this.call('open');
            };

            // Listen for browser event
            window.addEventListener('openChatList', openHandler);
            
            // Listen for Livewire event
            Livewire.on('openChatList', openHandler);
            
            // Listen for unread counts update to refresh list
            window.addEventListener('unread-counts-updated', () => {
                if (this.isOpen) {
                    @this.call('refreshChats');
                }
            });
            
            // Clean up
            this.$el.addEventListener('livewire:destroy', () => {
                window.removeEventListener('openChatList', openHandler);
            });
        }
    }"
    x-show="isOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click.away="isOpen = false; @this.call('close')"
    class="fixed inset-0 z-50 flex items-center justify-center dark:bg-black/50 bg-black/50 backdrop-blur-sm"
>
    <div 
        @click.stop
        class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-lg shadow-2xl w-full max-w-md max-h-[80vh] flex flex-col mx-4 transform transition-all duration-500"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-10"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-10"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-800 border-gray-200">
            <h2 class="text-xl font-bold dark:text-white text-gray-900">Chats</h2>
            <button
                wire:click="close"
                @click="isOpen = false"
                class="p-2 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                title="Close"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Chat List -->
        <div class="flex-1 overflow-y-auto">
            <!-- Pending Requests Section -->
            @if(count($requests) > 0)
                <div class="px-6 py-3 border-b dark:border-gray-800 border-gray-200 dark:bg-blue-600/10 bg-blue-50">
                    <h3 class="text-xs font-semibold dark:text-blue-400 text-blue-600 uppercase tracking-wider mb-3">Chat Requests</h3>
                    <div class="space-y-2">
                        @foreach($requests as $index => $request)
                            @php
                                $fromUser = $request->fromUser;
                                $requestMessage = $request->message;
                            @endphp
                            @if($fromUser)
                                <div 
                                    class="dark:bg-gray-800/50 bg-gray-50 rounded-lg p-3 border dark:border-blue-500/30 border-blue-300 dark:hover:border-blue-500/50 hover:border-blue-400 transition-all duration-300 transform hover:scale-[1.02]"
                                    x-data="{ show: false }"
                                    x-init="
                                        setTimeout(() => {
                                            show = true;
                                        }, {{ $index * 50 }});
                                    "
                                    x-show="show"
                                    x-transition:enter="transition ease-out duration-400"
                                    x-transition:enter-start="opacity-0 translate-x-4"
                                    x-transition:enter-end="opacity-100 translate-x-0"
                                >
                                    <div class="flex items-start gap-3">
                                        <div class="relative flex-shrink-0">
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-[2px]">
                                                <div class="w-full h-full rounded-full bg-gray-900 flex items-center justify-center text-sm font-semibold text-gray-100">
                                                    {{ strtoupper(substr($fromUser->name ?? 'U', 0, 1)) }}
                                                </div>
                                            </div>
                                            <span 
                                                class="absolute bottom-0 right-0 w-3 h-3 border-2 border-gray-900 rounded-full transition-all duration-300 user-status-indicator-{{ $fromUser->id }}"
                                                x-data="{ isOnline: {{ $fromUser->isActive() ? 'true' : 'false' }} }"
                                                :class="isOnline ? 'bg-green-500 animate-pulse' : 'bg-gray-500'"
                                            ></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-white mb-1">{{ $fromUser->name }}</p>
                                            @if($requestMessage)
                                                <p class="text-xs text-gray-400 mb-2 line-clamp-2">
                                                    {{ \Illuminate\Support\Str::limit($requestMessage->message, 60) }}
                                                </p>
                                            @else
                                                <p class="text-xs text-gray-500 mb-2">Sent a chat request</p>
                                            @endif
                                            <div class="flex items-center gap-2">
                                                <button
                                                    wire:click="acceptRequest({{ $request->id }})"
                                                    class="px-3 py-1.5 text-xs font-medium bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
                                                >
                                                    Accept
                                                </button>
                                                <button
                                                    wire:click="rejectRequest({{ $request->id }})"
                                                    class="px-3 py-1.5 text-xs font-medium bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                                                >
                                                    Reject
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Regular Chats Section -->
            @if(count($chats) > 0)
                @if(count($requests) > 0)
                    <div class="px-6 py-3 border-b border-gray-800">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Your Chats</h3>
                    </div>
                @endif
                <div class="divide-y divide-gray-800">
                    @foreach($chats as $index => $chat)
                        @php
                            $otherUser = $chat->other_user ?? null;
                            if (!$otherUser) {
                                continue; // Skip if no other user
                            }
                            $lastMessage = $chat->messages->first();
                            $isActive = $otherUser->isActive();
                            $unreadCount = $unreadCounts[$otherUser->id] ?? 0;
                        @endphp
                        <div
                            x-data="{ show: false }"
                            x-init="
                                setTimeout(() => {
                                    show = true;
                                }, {{ $index * 50 }});
                            "
                            x-show="show"
                            x-transition:enter="transition ease-out duration-400"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0"
                        >
                            <button
                                wire:click="openChat({{ $otherUser->id }})"
                                class="w-full flex items-center gap-3 px-6 py-4 hover:bg-gray-800/50 transition-all duration-300 transform hover:scale-[1.02] text-left group"
                            >
                                <div class="relative flex-shrink-0">
                                    <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-[2px]">
                                        <div class="w-full h-full rounded-full bg-gray-900 flex items-center justify-center text-lg font-semibold text-gray-100">
                                            {{ strtoupper(substr($otherUser->name ?? 'U', 0, 1)) }}
                                        </div>
                                    </div>
                                    <span 
                                        class="absolute bottom-0 right-0 w-4 h-4 border-2 border-gray-900 rounded-full transition-all duration-300 user-status-indicator-{{ $otherUser->id }}"
                                        x-data="{ isOnline: {{ $isActive ? 'true' : 'false' }} }"
                                        :class="isOnline ? 'bg-green-500 animate-pulse' : 'bg-gray-500'"
                                    ></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <p class="text-sm font-semibold text-white group-hover:text-blue-400 transition-colors truncate">
                                            {{ $otherUser->name }}
                                        </p>
                                        @if($lastMessage)
                                            <span class="text-xs text-gray-500 flex-shrink-0">
                                                {{ \Carbon\Carbon::parse($lastMessage->created_at)->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($lastMessage)
                                        <p class="text-xs text-gray-400 truncate">
                                            {{ $lastMessage->sender_id === auth()->id() ? 'You: ' : '' }}{{ \Illuminate\Support\Str::limit($lastMessage->message, 50) }}
                                        </p>
                                    @else
                                        <p class="text-xs text-gray-500">No messages yet</p>
                                    @endif
                                </div>
                                @if($unreadCount > 0)
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-600 text-white min-w-[24px]">
                                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                        </span>
                                    </div>
                                @endif
                            </button>
                        </div>
                    @endforeach
                </div>
            @elseif(count($requests) == 0)
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-sm text-gray-400 mb-1">No chats yet</p>
                    <p class="text-xs text-gray-500">Start a conversation with someone you follow</p>
                </div>
            @endif
        </div>
    </div>
</div>
