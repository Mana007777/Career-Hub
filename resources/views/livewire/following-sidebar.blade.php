<aside class="overflow-hidden rounded-[2.5rem] bg-zinc-900/40 border border-zinc-800/50 shadow-[0_30px_60px_rgba(0,0,0,0.3)] group/sidebar">
    <div class="px-8 pt-8 pb-6 border-b border-zinc-800/50 bg-zinc-950/40 relative">
        <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent"></div>
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <h2 class="text-[10px] font-black text-white uppercase tracking-[0.4em] italic">{{ __('Following') }}</h2>
                </div>
                <p class="text-[8px] font-black text-zinc-600 uppercase tracking-[0.2em] mt-2 italic">{{ __(':count active connections', ['count' => $followingCount]) }}</p>
            </div>
        </div>
    </div>
        
    <div class="max-h-[calc(100vh-250px)] overflow-y-auto custom-scrollbar p-4 space-y-4">
        @forelse($followingUsers as $index => $followingUser)
            @php $isActive = $followingUser->isActive(); @endphp
            <div 
                class="flex items-center gap-4 px-6 py-5 rounded-[1.8rem] bg-zinc-950/50 border border-zinc-800/50 hover:border-emerald-500/30 hover:bg-emerald-500/[0.02] transition-all duration-700 group/node"
                style="transition-delay: {{ $index * 50 }}ms"
            >
                <a href="{{ route('user.profile', $followingUser->username ?? 'unknown') }}" class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="relative shrink-0">
                        <div class="w-12 h-12 rounded-2xl bg-zinc-950 border-2 border-zinc-800 overflow-hidden flex items-center justify-center p-0.5 group-hover/node:border-emerald-500/40 transition-all duration-500">
                             <div class="w-full h-full rounded-xl bg-zinc-900 flex items-center justify-center">
                                @if($followingUser->profile_photo_path)
                                    <img src="{{ $followingUser->profile_photo_url }}" alt="{{ $followingUser->name }}" class="w-full h-full object-cover grayscale opacity-50 group-hover/node:grayscale-0 group-hover/node:opacity-100 transition-all">
                                @else
                                    <span class="text-[10px] font-black text-emerald-500/30 group-hover/node:text-emerald-500 transition-colors uppercase italic">{{ substr($followingUser->name, 0, 1) }}</span>
                                @endif
                             </div>
                        </div>
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 border-4 border-zinc-900 rounded-full {{ $isActive ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] animate-pulse' : 'bg-zinc-800' }}"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-[11px] font-black text-white uppercase tracking-tight italic group-hover/node:text-emerald-400 transition-colors truncate">{{ $followingUser->name }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[8px] font-black uppercase tracking-widest {{ $isActive ? 'text-emerald-500/70' : 'text-zinc-700' }}">{{ $isActive ? __('Online') : __('Offline') }}</span>
                        </div>
                    </div>
                </a>
                <button
                    onclick="window.dispatchEvent(new CustomEvent('open-chat', { detail: { userId: {{ $followingUser->id }} } }))"
                    class="w-10 h-10 rounded-xl bg-zinc-950 border border-zinc-800 flex items-center justify-center text-zinc-800 hover:text-emerald-500 hover:border-emerald-500/30 transition-all shadow-inner"
                    title="{{ __('Start chat') }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                </button>
            </div>
        @empty
            <div class="py-12 text-center">
                <p class="text-[9px] font-black text-zinc-700 uppercase tracking-[0.4em] italic">{{ __('No connections yet.') }}</p>
            </div>
        @endforelse
    </div>
</aside>
