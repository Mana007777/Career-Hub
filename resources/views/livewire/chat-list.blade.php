<div 
    x-data="{ 
        isOpen: @entangle('isOpen'),
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
    @if(!($inline ?? false))
        class="contents"
    @else
        x-show="isOpen"
        x-cloak
        class="mb-6"
    @endif
>
    @if(!($inline ?? false))
        <div
            x-show="isOpen"
            x-cloak
            class="fixed inset-0 z-[100] bg-zinc-950/90 backdrop-blur-2xl"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-250"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="isOpen = false; @this.call('close')"
        ></div>
        <div
            x-show="isOpen"
            x-cloak
            class="fixed inset-x-0 bottom-0 z-[101] flex justify-center items-end pb-40 px-4 pointer-events-none"
        >
    @endif
    <div 
        @if(!($inline ?? false)) @click.stop @endif
        class="bg-zinc-950/60 border border-zinc-800/50 rounded-[2.5rem] shadow-[0_50px_100px_rgba(0,0,0,0.5)] flex flex-col overflow-hidden backdrop-blur-3xl @if(!($inline ?? false)) pointer-events-auto w-full max-w-md max-h-[min(80vh,calc(100vh-11rem))] relative @endif"
        @if(!($inline ?? false))
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
            x-transition:enter-start="opacity-0 translate-y-[110%]"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition cubic-bezier(0.34, 1, 0.56, 1) duration-350"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-[110%]"
        @endif
    >
        @if(!($inline ?? false))
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/30 to-transparent"></div>
        @endif

        <div class="px-8 py-8 flex items-center justify-between bg-zinc-950/40 border-b border-zinc-800/30">
            <h2 class="text-[10px] font-black text-white uppercase tracking-[0.4em] flex items-center gap-3">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ __('Chats') }}
            </h2>
            @if(array_sum($unreadCounts) > 0)
                <span class="px-3 py-1 rounded-lg bg-emerald-500 text-[10px] font-black text-black shadow-[0_0_20px_rgba(16,185,129,0.3)]">
                    +{{ array_sum($unreadCounts) }}
                </span>
            @endif
        </div>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto max-h-[600px] custom-scrollbar py-4 bg-zinc-900/50">
            
            <!-- CHATS ONLY -->
            <div>
                 <!-- Pending Requests Section -->
                 @if(count($requests) > 0)
                    <div class="px-6 mb-8">
                        <div class="p-6 rounded-3xl bg-zinc-950/60 border border-zinc-800/50 backdrop-blur-xl">
                            <h3 class="text-[9px] font-black text-emerald-500/70 uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                {{ __('Chat Requests') }}
                            </h3>
                            <div class="space-y-6">
                                @foreach($requests as $request)
                                    @php $fromUser = $request->fromUser ?? null; @endphp
                                    @if($fromUser)
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl overflow-hidden grayscale ring-1 ring-zinc-800">
                                                <img src="{{ $fromUser->profile_photo_url }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[10px] font-black text-white uppercase tracking-widest truncate">{{ $fromUser->name }}</p>
                                                <div class="flex gap-2 mt-3">
                                                    <button wire:click="acceptRequest({{ $request->id }})" class="flex-1 py-2 bg-emerald-500 text-[9px] font-black rounded-xl text-black uppercase tracking-[0.2em] hover:bg-emerald-400 transition-all shadow-[0_0_15px_rgba(16,185,129,0.1)]">{{ __('Accept') }}</button>
                                                    <button wire:click="rejectRequest({{ $request->id }})" class="flex-1 py-2 bg-zinc-800/50 text-[9px] font-black rounded-xl text-zinc-500 uppercase tracking-[0.2em] hover:bg-zinc-800 transition-all border border-zinc-700/30">{{ __('Decline') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="px-8 pb-3">
                    <p class="text-[8px] font-black text-zinc-600 uppercase tracking-[0.3em]">{{ __('Chats') }}</p>
                </div>
                @if(count($chats) > 0)
                    <div class="space-y-1">
                        @foreach($chats as $chat)
                            @php
                                $otherUser = $chat->other_user ?? null;
                                if (!$otherUser) continue;
                                $lastMessage = $chat->last_message ?? null;
                                $unreadCount = $unreadCounts[$otherUser->id] ?? 0;
                            @endphp
                            <button
                                wire:click="openChat({{ $otherUser->id }})"
                                class="w-full flex items-center gap-5 px-8 py-5 hover:bg-emerald-500/5 transition-all duration-500 text-left group relative border-b border-zinc-800/20 last:border-0"
                            >
                                @if($unreadCount > 0)
                                    <div class="absolute left-0 inset-y-4 w-1 bg-emerald-500 rounded-r-full shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
                                @endif

                                <div class="relative flex-shrink-0">
                                    <div class="w-16 h-16 rounded-[1.25rem] overflow-hidden border-2 border-transparent group-hover:border-emerald-500/30 transition-all duration-500 bg-zinc-950/40 p-0.5">
                                        <div class="w-full h-full rounded-[1rem] overflow-hidden bg-zinc-950">
                                            <img src="{{ $otherUser->profile_photo_url }}" class="w-full h-full object-cover grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700">
                                        </div>
                                    </div>
                                    @if($otherUser->isActive())
                                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-4 border-zinc-900 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.4)]"></span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-3 mb-1.5">
                                        <p class="text-[11px] font-black text-white group-hover:text-emerald-400 transition-colors uppercase tracking-[0.1em] italic">
                                            {{ $otherUser->name }}
                                        </p>
                                        @if($lastMessage)
                                            <span class="text-[8px] font-black text-zinc-600 uppercase tracking-widest">{{ \App\Support\SoraniTime::human($lastMessage->created_at) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-[10px] font-bold text-zinc-500 truncate italic tracking-tight group-hover:text-zinc-400">
                                            {{ $lastMessage ? ($lastMessage->sender_id === auth()->id() ? __('You: ') : '') . $lastMessage->message : __('No messages yet') }}
                                        </p>
                                        @if($unreadCount > 0)
                                            <span class="px-2 py-0.5 rounded-lg bg-emerald-500 text-[8px] font-black text-black">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="px-8 pb-6">
                        <p class="text-[9px] font-black text-zinc-700 uppercase tracking-[0.2em]">{{ __('No active chats yet.') }}</p>
                    </div>
                @endif

                <div class="px-8 pt-6 pb-3">
                    <p class="text-[8px] font-black text-zinc-600 uppercase tracking-[0.3em]">{{ __('Friends') }}</p>
                </div>
                @if(count($friends) > 0)
                    <div class="space-y-1 mb-4">
                        @foreach($friends as $friend)
                            <button
                                wire:click="openChat({{ $friend->id }})"
                                class="w-full flex items-center gap-5 px-8 py-5 hover:bg-emerald-500/5 transition-all duration-500 text-left group relative border-b border-zinc-800/20 last:border-0"
                            >
                                <div class="relative flex-shrink-0">
                                    <div class="w-16 h-16 rounded-[1.25rem] overflow-hidden border-2 border-transparent group-hover:border-emerald-500/30 transition-all duration-500 bg-zinc-950/40 p-0.5">
                                        <div class="w-full h-full rounded-[1rem] overflow-hidden bg-zinc-950">
                                            <img src="{{ $friend->profile_photo_url }}" class="w-full h-full object-cover grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700">
                                        </div>
                                    </div>
                                    @if($friend->isActive())
                                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-4 border-zinc-900 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.4)]"></span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-black text-white group-hover:text-emerald-400 transition-colors uppercase tracking-[0.1em] italic">
                                        {{ $friend->name }}
                                    </p>
                                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.2em] mt-1">
                                        {{ __('Friend - no chat yet') }}
                                    </p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="px-8 pb-6">
                        <p class="text-[9px] font-black text-zinc-700 uppercase tracking-[0.2em]">{{ __('No friends without chats.') }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
    @if(!($inline ?? false))
        </div>
    @endif
</div>
