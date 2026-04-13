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
        x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.away="isOpen = false; @this.call('close')"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-zinc-950/98 backdrop-blur-2xl px-4"
    @else
        class="mb-6"
    @endif
>
    <div 
        @click.stop
        class="bg-zinc-950/60 border border-zinc-800/50 rounded-[2.5rem] shadow-[0_50px_100px_rgba(0,0,0,0.5)] flex flex-col overflow-hidden transform transition-all duration-700 backdrop-blur-3xl @if(!($inline ?? false)) w-full max-w-md max-h-[80vh] relative @endif"
    >
        @if(!($inline ?? false))
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/30 to-transparent"></div>
        @endif

        <div class="px-8 py-8 flex items-center justify-between bg-zinc-950/40 border-b border-zinc-800/30">
            <h2 class="text-[10px] font-black text-white uppercase tracking-[0.4em] flex items-center gap-3">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Uplink Active
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
                                Auth Requests
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
                                                    <button wire:click="acceptRequest({{ $request->id }})" class="flex-1 py-2 bg-emerald-500 text-[9px] font-black rounded-xl text-black uppercase tracking-[0.2em] hover:bg-emerald-400 transition-all shadow-[0_0_15px_rgba(16,185,129,0.1)]">Authorize</button>
                                                    <button wire:click="rejectRequest({{ $request->id }})" class="flex-1 py-2 bg-zinc-800/50 text-[9px] font-black rounded-xl text-zinc-500 uppercase tracking-[0.2em] hover:bg-zinc-800 transition-all border border-zinc-700/30">Decline</button>
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
                                            <span class="text-[8px] font-black text-zinc-600 uppercase tracking-widest">{{ \Carbon\Carbon::parse($lastMessage->created_at)->shortRelativeDiffForHumans() }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-[10px] font-bold text-zinc-500 truncate italic tracking-tight group-hover:text-zinc-400">
                                            {{ $lastMessage ? ($lastMessage->sender_id === auth()->id() ? 'Uplink: ' : '') . $lastMessage->message : 'No active stream' }}
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
                    <div class="py-32 text-center px-12">
                        <div class="w-20 h-20 bg-zinc-950/40 border border-zinc-800/50 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner group">
                            <svg class="w-10 h-10 text-zinc-800 group-hover:text-emerald-500/30 transition-all duration-1000" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-white italic uppercase tracking-tighter">Quiet Sector</h3>
                        <p class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] mt-4">No active intelligence streams detected.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
