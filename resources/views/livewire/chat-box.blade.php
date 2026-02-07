<div 
    x-data="{
        chatId: @entangle('chatId'),
        init() {
            
            // Listen for open chat event
            window.addEventListener('open-chat', (e) => {
                @this.call('openChat', e.detail.userId);
            });
            
            // Watch for chatId changes and set up Echo listener
            let lastChatId = null;
            this.$watch('chatId', (newChatId) => {
                if (newChatId && newChatId !== lastChatId) {
                    lastChatId = newChatId;
                    console.log('🔄 Chat ID changed, setting up listener for:', newChatId);
                    // Dispatch event for chat.js to set up Echo listener
                    window.dispatchEvent(new CustomEvent('chat-opened', {
                        detail: { chatId: newChatId }
                    }));
                }
            });
            
            // Listen for new messages via Echo/Reverb
            const messageHandler = (e) => {
                console.log('📨 new-message event received:', e.detail);
                console.log('📨 Full event:', e);
                
                const currentChatId = @this.get('chatId');
                console.log('🔍 Current chatId from Livewire:', currentChatId);
                console.log('🔍 Event chatId:', e.detail?.chatId);
                console.log('🔍 Event message:', e.detail?.message);
                
                if (e.detail && e.detail.chatId == currentChatId) {
                    // Try to add message directly first
                    if (e.detail.message) {
                        console.log('➕ Calling addMessage with:', e.detail.message);
                        @this.call('addMessage', e.detail.message)
                            .then(() => {
                                console.log('✅ addMessage call completed');
                            })
                            .catch((error) => {
                                console.error('❌ addMessage error:', error);
                            });
                    } else {
                        console.log('🔄 No message in detail, refreshing messages');
                        // Fallback to refresh
                        @this.call('refreshMessages');
                    }
                } else {
                    console.log('⚠️ Message chatId mismatch:', {
                        'eventChatId': e.detail?.chatId,
                        'currentChatId': currentChatId,
                        'match': e.detail?.chatId == currentChatId
                    });
                }
            };
            
            // Listen for message status updates
            const statusHandler = (e) => {
                if (e.detail && e.detail.messageId && e.detail.status) {
                    console.log('📬 Message status update received:', e.detail);
                    @this.call('handleStatusUpdate', e.detail.messageId, e.detail.status)
                        .then(() => {
                            console.log('✅ Status update handled');
                        })
                        .catch((error) => {
                            console.error('❌ Status update error:', error);
                        });
                }
            };
            
            window.addEventListener('new-message', messageHandler);
            window.addEventListener('message-status-updated', statusHandler);
            
            // Clean up on component destroy
            this.$el.addEventListener('livewire:destroy', () => {
                window.removeEventListener('new-message', messageHandler);
                window.removeEventListener('message-status-updated', statusHandler);
            });
        }
    }"
>
    @if($isOpen && $otherUser)
        @php
            $currentUser = auth()->user();
            $isFollowing = $currentUser->following()->where('following_id', $otherUser->id)->exists();
        @endphp
        @if(!$isFollowing)
            <!-- Not Following Notice -->
            <div 
                x-data="{ 
                    isMinimized: false,
                    init() {
                        this.$watch('$wire.isOpen', value => {
                            if (value) {
                                this.isMinimized = false;
                            }
                        });
                    }
                }"
                x-show="$wire.isOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-4"
                class="fixed bottom-4 right-4 z-50 w-96 dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-lg shadow-2xl flex flex-col"
                style="height: 500px;"
            >
                <div class="flex items-center justify-between px-4 py-3 dark:bg-gray-800 bg-gray-100 border-b dark:border-gray-700 border-gray-200 rounded-t-lg">
                    <h3 class="text-sm font-semibold dark:text-white text-gray-900">{{ $otherUser->name }}</h3>
                    <button
                        wire:click="closeChat"
                        class="p-1.5 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 dark:hover:bg-gray-700 hover:bg-gray-200 rounded transition-colors"
                        title="Close"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 flex items-center justify-center p-8">
                    <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <p class="text-sm dark:text-gray-400 text-gray-600 mb-2">You need to follow {{ $otherUser->name }} to start chatting</p>
                        <a 
                            href="{{ route('user.profile', $otherUser->username ?? 'unknown') }}"
                            class="inline-block px-4 py-2 text-sm font-medium dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white rounded-lg transition-colors"
                        >
                            View Profile
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Normal Chat Box -->
            <div 
                x-data="{ 
                    isMinimized: false,
                    init() {
                        this.$watch('$wire.isOpen', value => {
                            if (value) {
                                this.isMinimized = false;
                                setTimeout(() => this.$dispatch('scroll-to-bottom'), 100);
                            }
                        });
                    }
                }"
                x-show="$wire.isOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-4"
                class="fixed bottom-4 right-4 z-50 w-96 dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-lg shadow-2xl flex flex-col"
                style="height: 500px;"
            >
            <!-- Chat Header -->
            <div class="flex items-center justify-between px-4 py-3 bg-gray-800 border-b border-gray-700 rounded-t-lg">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-[2px]">
                            <div class="w-full h-full rounded-full dark:bg-gray-900 bg-gray-200 flex items-center justify-center text-sm font-semibold dark:text-gray-100 text-gray-900">
                                {{ strtoupper(substr($otherUser->name ?? 'U', 0, 1)) }}
                            </div>
                        </div>
                        <span 
                            class="absolute bottom-0 right-0 w-3 h-3 border-2 border-gray-900 rounded-full transition-all duration-300 user-status-indicator-{{ $otherUser->id }}"
                            x-data="{ isOnline: {{ $otherUser->isActive() ? 'true' : 'false' }} }"
                            :class="isOnline ? 'bg-green-500 animate-pulse' : 'bg-gray-500'"
                        ></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold dark:text-white text-gray-900 truncate">{{ $otherUser->name }}</h3>
                        <p 
                            class="text-xs user-status-text-{{ $otherUser->id }}"
                            x-data="{ isOnline: {{ $otherUser->isActive() ? 'true' : 'false' }} }"
                        >
                            <span :class="isOnline ? 'text-green-400' : 'dark:text-gray-400 text-gray-600'" x-text="isOnline ? 'Active now' : 'Offline'"></span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <button
                        @click="isMinimized = !isMinimized"
                        class="p-1.5 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 dark:hover:bg-gray-700 hover:bg-gray-200 rounded transition-colors"
                        title="Minimize"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </button>
                    <button
                        wire:click="closeChat"
                        class="p-1.5 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 dark:hover:bg-gray-700 hover:bg-gray-200 rounded transition-colors"
                        title="Close"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages Container with Sticky Banner -->
            <div 
                x-show="!isMinimized"
                x-transition
                class="flex-1 overflow-y-auto dark:bg-gray-900 bg-gray-50 relative flex flex-col"
                id="chat-messages-container-{{ $chatId }}"
            >
                <!-- Pending Request Banner (if receiver) - Sticky at top -->
                @if($pendingRequest && !$isRequest)
                    <div class="px-4 py-3 bg-blue-600/20 border-b border-blue-500/30 sticky top-0 z-10 backdrop-blur-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-blue-400">{{ $otherUser->name }} wants to chat with you</p>
                                <p class="text-xs dark:text-gray-400 text-gray-600 mt-1">Review the messages below, then accept or reject</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button
                                    wire:click="acceptRequest"
                                    class="px-4 py-1.5 text-xs font-medium bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
                                >
                                    Accept
                                </button>
                                <button
                                    wire:click="rejectRequest"
                                    class="px-4 py-1.5 text-xs font-medium bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                                >
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Request Notice (if sender) -->
                @if($isRequest)
                    <div class="px-4 py-3 bg-yellow-600/20 border-b border-yellow-500/30">
                        <p class="text-sm text-yellow-400">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $otherUser->name }} hasn't followed you back. Your message will be sent as a request.
                        </p>
                    </div>
                @endif

                <!-- Chat Messages - Scrollable -->
                <div 
                    class="flex-1 overflow-y-auto p-4 space-y-3"
                    id="chat-messages-{{ $chatId }}"
                    x-init="
                        // Scroll to bottom when messages change
                        $watch('$wire.messages', () => {
                            setTimeout(() => {
                                const container = document.getElementById('chat-messages-{{ $chatId }}');
                                if (container) {
                                    container.scrollTop = container.scrollHeight;
                                }
                            }, 50);
                        });
                        
                        // Initial scroll to bottom
                        setTimeout(() => {
                            const container = document.getElementById('chat-messages-{{ $chatId }}');
                            if (container) {
                                container.scrollTop = container.scrollHeight;
                            }
                        }, 100);
                    "
                >
                @if(count($messages) > 0)
                    @foreach($messages as $message)
                        @php
                            // Ensure we can access properties safely
                            $senderId = is_object($message) ? ($message->sender_id ?? null) : ($message['sender_id'] ?? null);
                            $messageId = is_object($message) ? ($message->id ?? null) : ($message['id'] ?? null);
                            $messageText = is_object($message) ? ($message->message ?? '') : ($message['message'] ?? '');
                            $createdAt = is_object($message) ? ($message->created_at ?? null) : ($message['created_at'] ?? null);
                            $status = is_object($message) ? ($message->status ?? 'sent') : ($message['status'] ?? 'sent');
                            $sender = is_object($message) ? ($message->sender ?? null) : ($message['sender'] ?? null);
                        @endphp
                        <div class="flex {{ $senderId === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="flex items-start gap-2 max-w-[75%] {{ $senderId === auth()->id() ? 'flex-row-reverse' : 'flex-row' }}">
                                @if($senderId !== auth()->id())
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-[1px] flex-shrink-0">
                                        <div class="w-full h-full rounded-full bg-gray-900 flex items-center justify-center text-xs font-semibold text-gray-100">
                                            {{ strtoupper(substr(is_object($sender) ? ($sender->name ?? 'U') : ($sender['name'] ?? 'U'), 0, 1)) }}
                                        </div>
                                    </div>
                                @endif
                                <div class="flex flex-col {{ $senderId === auth()->id() ? 'items-end' : 'items-start' }}">
                                    <div class="px-4 py-2 rounded-2xl {{ $senderId === auth()->id() ? 'bg-blue-600 text-white rounded-br-sm' : 'dark:bg-gray-800 bg-gray-200 dark:text-gray-100 text-gray-900 rounded-bl-sm' }}">
                                        <p class="text-sm whitespace-pre-wrap break-words">{{ $messageText }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 mt-1 px-1">
                                        <span class="text-xs dark:text-gray-500 text-gray-600">
                                            {{ $createdAt ? \Carbon\Carbon::parse($createdAt)->format('h:i A') : '' }}
                                        </span>
                                        @if($senderId === auth()->id())
                                            <span 
                                                class="text-xs message-status-{{ $messageId }}"
                                                x-data="{ 
                                                    status: '{{ $status }}',
                                                    init() {
                                                        // Listen for status updates
                                                        const handler = (e) => {
                                                            if (e.detail && parseInt(e.detail.messageId) === {{ $messageId }}) {
                                                                console.log('Alpine: Updating status for message {{ $messageId }} to', e.detail.status);
                                                                this.status = e.detail.status;
                                                            }
                                                        };
                                                        window.addEventListener('message-status-updated', handler);
                                                        
                                                        // Clean up on destroy
                                                        this.$el.addEventListener('livewire:destroy', () => {
                                                            window.removeEventListener('message-status-updated', handler);
                                                        });
                                                    }
                                                }"
                                                x-text="status === 'seen' ? '✓✓ Seen' : (status === 'delivered' ? '✓✓ Delivered' : '✓ Sent')"
                                                :class="{
                                                    'text-gray-400': status === 'sent',
                                                    'text-blue-400': status === 'delivered',
                                                    'text-green-400': status === 'seen'
                                                }"
                                            >
                                                @if($status === 'seen')
                                                    ✓✓ Seen
                                                @elseif($status === 'delivered')
                                                    ✓✓ Delivered
                                                @else
                                                    ✓ Sent
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <p class="text-sm dark:text-gray-500 text-gray-600">No messages yet. Start the conversation!</p>
                    </div>
                @endif
                </div>
            </div>

            <!-- Chat Input -->
            <div 
                x-show="!isMinimized"
                x-transition
                class="p-4 dark:bg-gray-800 bg-gray-100 border-t dark:border-gray-700 border-gray-200 rounded-b-lg"
            >
                @if($pendingRequest && !$isRequest)
                    <!-- Disabled input when there's a pending request (receiver side) -->
                    <div class="flex items-center justify-center py-3">
                        <p class="text-xs dark:text-gray-400 text-gray-600">Accept or reject the request to start chatting</p>
                    </div>
                @else
                    <form wire:submit.prevent="sendMessage" class="flex items-end gap-2">
                        <div class="flex-1">
                            <textarea
                                wire:model="newMessage"
                                wire:keydown.enter.prevent="sendMessage"
                                rows="1"
                                placeholder="{{ $isRequest ? 'Your message will be sent as a request...' : 'Type a message...' }}"
                                class="w-full px-4 py-2 dark:bg-gray-900 bg-white border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                style="min-height: 40px; max-height: 120px;"
                                x-on:input="
                                    $el.style.height = 'auto';
                                    $el.style.height = Math.min($el.scrollHeight, 120) + 'px';
                                "
                            ></textarea>
                        </div>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="p-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0"
                            title="Send message"
                        >
                        <svg wire:loading.remove wire:target="sendMessage" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <svg wire:loading wire:target="sendMessage" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endif
    @endif
</div>
