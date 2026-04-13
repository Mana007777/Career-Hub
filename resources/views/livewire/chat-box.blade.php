<div 
    x-data="{
        chatId: @entangle('chatId'),
        init() {
            window.addEventListener('open-chat', (e) => {
                @this.call('openChat', e.detail.userId);
            });
            
            let lastChatId = null;
            this.$watch('chatId', (newChatId) => {
                if (newChatId && newChatId !== lastChatId) {
                    lastChatId = newChatId;
                    window.dispatchEvent(new CustomEvent('chat-opened', {
                        detail: { chatId: newChatId }
                    }));
                }
            });
            
            const messageHandler = (e) => {
                const currentChatId = @this.get('chatId');
                if (e.detail && e.detail.chatId == currentChatId) {
                    if (e.detail.message) {
                        @this.call('addMessage', e.detail.message).catch(console.error);
                    } else {
                        @this.call('refreshMessages');
                    }
                }
            };
            
            const statusHandler = (e) => {
                if (e.detail && e.detail.messageId && e.detail.status) {
                    @this.call('handleStatusUpdate', e.detail.messageId, e.detail.status).catch(console.error);
                }
            };
            
            window.addEventListener('new-message', messageHandler);
            window.addEventListener('message-status-updated', statusHandler);
            
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

        <!-- Absolute Dark Chat Interface -->
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
            x-cloak
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
            x-transition:enter-start="opacity-0 translate-y-20 blur-xl"
            x-transition:enter-end="opacity-100 translate-y-0 blur-0"
            x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-400"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-20 blur-xl"
            class="fixed bottom-32 right-8 z-[110] w-[400px] bg-zinc-950/60 border border-zinc-800/50 rounded-[2.5rem] shadow-[0_50px_100px_rgba(0,0,0,1)] flex flex-col overflow-hidden backdrop-blur-3xl"
            x-bind:style="isMinimized ? 'height: 80px;' : 'height: 650px;'"
        >
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/30 to-transparent"></div>

            <!-- Chat Header -->
            <div class="flex items-center justify-between px-8 py-6 bg-zinc-950/40 border-b border-zinc-800/30">
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 rounded-2xl overflow-hidden bg-zinc-950 border border-zinc-800/50 p-0.5">
                            <div class="w-full h-full rounded-xl overflow-hidden bg-zinc-950">
                                @if($otherUser->profile_photo_path)
                                    <img src="{{ $otherUser->profile_photo_url }}" alt="{{ $otherUser->name }}" class="w-full h-full object-cover grayscale opacity-80 group-hover:grayscale-0 transition-all">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-[10px] font-black text-emerald-500 uppercase">
                                        {{ substr($otherUser->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-4 border-zinc-900 {{ $otherUser->isActive() ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]' : 'bg-zinc-700' }}"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-[11px] font-black text-white truncate uppercase tracking-widest italic">{{ $otherUser->name }}</h3>
                        <p class="text-[8px] font-black uppercase tracking-[0.2em] mt-1 {{ $otherUser->isActive() ? 'text-emerald-500' : 'text-zinc-600' }}">
                            {{ $otherUser->isActive() ? 'Link Established' : 'Signal Lost' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="isMinimized = !isMinimized" class="p-2 text-zinc-600 hover:text-white hover:bg-zinc-800 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M20 12H4"></path></svg>
                    </button>
                    <button wire:click="closeChat" class="p-2 text-zinc-600 hover:text-rose-500 hover:bg-rose-500/10 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Messages Stream -->
            @if(!$isFollowing)
                <div class="flex-1 flex flex-col items-center justify-center p-12 text-center bg-zinc-950/20" x-show="!isMinimized">
                    <div class="w-20 h-20 bg-zinc-950/40 border border-zinc-800 rounded-3xl flex items-center justify-center mb-8 shadow-inner">
                        <svg class="w-10 h-10 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h4 class="text-sm font-black text-white uppercase tracking-widest italic mb-4">Encryption Locked</h4>
                    <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-[0.2em] leading-relaxed mb-8">Follow {{ $otherUser->name }} to initiate private data uplink.</p>
                    <a href="{{ route('user.profile', $otherUser->username ?? 'unknown') }}" class="px-10 py-4 bg-emerald-500 text-black text-[10px] font-black rounded-2xl uppercase tracking-[0.3em] shadow-[0_0_30px_rgba(16,185,129,0.2)] hover:scale-105 transition-all">View Identity</a>
                </div>
            @else
                <div 
                    x-show="!isMinimized"
                    class="flex-1 overflow-y-auto p-8 space-y-8 custom-scrollbar bg-zinc-950/20"
                    id="chat-messages-{{ $chatId }}"
                    x-init="
                        $watch('$wire.messages', () => {
                            setTimeout(() => { $el.scrollTop = $el.scrollHeight; }, 50);
                        });
                        setTimeout(() => { $el.scrollTop = $el.scrollHeight; }, 100);
                    "
                >
                    <!-- Interaction Requests Hub -->
                    @if($pendingRequest && !$isRequest)
                        <div class="p-6 rounded-3xl bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-3xl animate-pulse">
                            <p class="text-[9px] font-black text-emerald-500 uppercase tracking-[0.3em] mb-4">Inbound Access Request</p>
                            <div class="flex gap-3">
                                <button wire:click="acceptRequest" class="flex-1 py-3 bg-emerald-500 text-black text-[9px] font-black rounded-xl uppercase tracking-widest">Authorize</button>
                                <button wire:click="rejectRequest" class="flex-1 py-3 bg-zinc-800 text-white text-[9px] font-black rounded-xl uppercase tracking-widest">Nullify</button>
                            </div>
                        </div>
                    @endif

                    @forelse($messages as $message)
                        @php
                            $isMe = (is_object($message) ? $message->sender_id : ($message['sender_id'] ?? null)) === auth()->id();
                            $msgText = is_object($message) ? $message->message : ($message['message'] ?? '');
                            $msgStatus = is_object($message) ? $message->status : ($message['status'] ?? 'sent');
                            $msgTime = is_object($message) ? $message->created_at : ($message['created_at'] ?? null);
                        @endphp
                        
                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} group/msg">
                            <div class="max-w-[85%] space-y-2">
                                <div class="relative px-6 py-4 rounded-[2rem] {{ $isMe ? 'bg-emerald-500 text-black rounded-br-none shadow-[0_10px_30px_rgba(16,185,129,0.1)]' : 'bg-zinc-800 text-white border border-zinc-700/30 rounded-bl-none shadow-[0_10px_30px_rgba(0,0,0,0.2)]' }}">
                                    <p class="text-[11px] font-bold leading-relaxed selection:bg-black/20">{{ $msgText }}</p>
                                    
                                    <!-- Status Matrix -->
                                    <div class="absolute -bottom-6 {{ $isMe ? 'right-2' : 'left-2' }} opacity-0 group-hover/msg:opacity-100 transition-opacity duration-500 flex items-center gap-3">
                                        <span class="text-[8px] font-black text-zinc-600 uppercase tracking-widest">
                                            {{ $msgTime ? \Carbon\Carbon::parse($msgTime)->format('H:i') : '' }}
                                        </span>
                                        @if($isMe)
                                            <span class="text-[8px] font-black uppercase tracking-widest {{ $msgStatus === 'seen' ? 'text-emerald-500' : 'text-zinc-600' }}">
                                                {{ strtoupper($msgStatus) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-20 text-center">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping mb-6"></div>
                            <p class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em]">Establishing secure uplink...</p>
                        </div>
                    @endforelse
                </div>

                <!-- Uplink Terminal -->
                <div class="px-8 py-8 bg-zinc-950/40 border-t border-zinc-800/30" x-show="!isMinimized">
                    @if($pendingRequest && !$isRequest)
                        <div class="text-center py-4 bg-zinc-950/40 border border-dashed border-zinc-800 rounded-2xl">
                            <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Awaiting access authorization</p>
                        </div>
                    @else
                        <form wire:submit.prevent="sendMessage" class="relative group/input">
                            <textarea
                                wire:model="newMessage"
                                wire:keydown.enter.prevent="sendMessage"
                                rows="1"
                                placeholder="INITIALIZE BROADCAST..."
                                class="w-full px-8 py-5 bg-zinc-950/40 border border-zinc-800 rounded-3xl text-white placeholder-zinc-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition-all text-xs font-black uppercase resize-none custom-scrollbar"
                                style="min-height: 64px; max-height: 160px;"
                                x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 160) + 'px';"
                            ></textarea>
                            <div class="absolute right-4 bottom-4 flex items-center gap-4">
                                <label class="p-2 text-zinc-600 hover:text-emerald-500 cursor-pointer transition-all">
                                    <input type="file" wire:model="attachment" class="hidden">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.415-6.586a4 4 0 00-5.657-5.657l-6.586 6.586a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                </label>
                                <button type="submit" class="w-10 h-10 bg-emerald-500 text-black rounded-2xl flex items-center justify-center hover:scale-105 active:scale-95 transition-all shadow-[0_0_20px_rgba(16,185,129,0.3)]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
