<div 
    x-data="{ 
        isOpen: @js($inline ?? false),
        activeTab: 'chats',
        init() {
            if (!@js($inline ?? false)) {
                const openHandler = () => {
                    this.isOpen = true;
                    @this.call('open');
                };
                window.addEventListener('openChatList', openHandler);
                Livewire.on('openChatList', openHandler);
                this.$el.addEventListener('livewire:destroy', () => {
                    window.removeEventListener('openChatList', openHandler);
                });
            }
            
            window.addEventListener('unread-counts-updated', () => {
                if (this.isOpen) {
                    @this.call('refreshChats');
                }
            });
        }
    }"
    x-show="isOpen"
    x-cloak
    @if(!($inline ?? false))
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.away="isOpen = false; @this.call('close')"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-md"
    @else
        class="mb-6"
    @endif
>
    <div 
        @click.stop
        class="bg-white/[0.04] border border-white/10 rounded-[2.5rem] shadow-2xl flex flex-col overflow-hidden transform transition-all duration-500 @if(!($inline ?? false)) w-full max-w-md max-h-[80vh] mx-4 @endif"
        @if(!($inline ?? false))
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 scale-95 -translate-y-10"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        @endif
    >
        <div class="px-6 py-4 flex items-center justify-between bg-gradient-to-b from-white/[0.02] to-transparent">
            <h2 class="text-sm font-black text-white uppercase tracking-[0.2em] flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Messages
            </h2>
            @if(array_sum($unreadCounts) > 0)
                <span class="px-2 py-0.5 rounded-full bg-red-500 text-[10px] font-black text-white animate-pulse">
                    {{ array_sum($unreadCounts) }}
                </span>
            @endif
        </div>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto max-h-[600px] scrollbar-hide py-4">
            
            <!-- CHATS ONLY -->
            <div>
                 <!-- Pending Requests Section -->
                 @if(count($requests) > 0)
                    <div class="px-6 mb-6">
                        <div class="p-4 rounded-2xl bg-brand-deep/30 border border-white/5">
                            <h3 class="text-[10px] font-black text-brand-violet uppercase tracking-[0.2em] mb-4">Chat Requests</h3>
                            <div class="space-y-4">
                                @foreach($requests as $request)
                                    @php $fromUser = $request->fromUser ?? null; @endphp
                                    @if($fromUser)
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $fromUser->profile_photo_url }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-white/5">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-bold dark:text-white truncate">{{ $fromUser->name }}</p>
                                                <div class="flex gap-2 mt-2">
                                                    <button wire:click="acceptRequest({{ $request->id }})" class="flex-1 py-1 bg-brand-purple text-[10px] font-black rounded-lg text-white uppercase tracking-widest hover:opacity-80 transition-all">Accept</button>
                                                    <button wire:click="rejectRequest({{ $request->id }})" class="flex-1 py-1 bg-white/5 text-[10px] font-black rounded-lg text-gray-400 uppercase tracking-widest hover:bg-white/10 transition-all">Ignore</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if(count($chats) > 0)
                    <div class="space-y-1">
                        @foreach($chats as $chat)
                            @php
                                $otherUser = $chat->other_user ?? null;
                                if (!$otherUser) continue;
                                $lastMessage = $chat->messages->first();
                                $unreadCount = $unreadCounts[$otherUser->id] ?? 0;
                            @endphp
                            <button
                                wire:click="openChat({{ $otherUser->id }})"
                                class="w-full flex items-center gap-4 px-6 py-4 hover:bg-white/5 transition-all duration-300 text-left group relative"
                            >
                                @if($unreadCount > 0)
                                    <div class="absolute left-1.5 h-8 w-1 bg-brand-violet rounded-full"></div>
                                @endif

                                <div class="relative flex-shrink-0">
                                    <div class="w-14 h-14 rounded-2xl overflow-hidden ring-4 ring-transparent group-hover:ring-brand-purple/30 transition-all">
                                        <img src="{{ $otherUser->profile_photo_url }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                                    </div>
                                    @if($otherUser->isActive())
                                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-4 dark:border-black border-white rounded-full"></span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <p class="text-sm font-black text-white truncate group-hover:text-brand-violet transition-colors uppercase tracking-tight">
                                            {{ $otherUser->name }}
                                        </p>
                                        @if($lastMessage)
                                            <span class="text-[10px] font-bold text-gray-500 uppercase">{{ \Carbon\Carbon::parse($lastMessage->created_at)->shortRelativeDiffForHumans() }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-xs font-medium dark:text-gray-500 text-gray-500 truncate italic">
                                            {{ $lastMessage ? ($lastMessage->sender_id === auth()->id() ? 'You: ' : '') . $lastMessage->message : 'No messages' }}
                                        </p>
                                        @if($unreadCount > 0)
                                            <span class="px-2 py-0.5 rounded-full bg-brand-purple text-[10px] font-black text-white">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="py-20 text-center px-10">
                        <div class="w-16 h-16 dark:bg-brand-deep/20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">No chats active</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
