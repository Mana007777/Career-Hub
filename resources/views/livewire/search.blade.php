<div>
    @if($showSearch)
    <!-- Deep Scan Overlay -->
    <div 
        x-data="{ 
            init() {
                document.body.style.overflow = 'hidden';
                this.$el.addEventListener('livewire:destroy', () => {
                    document.body.style.overflow = '';
                });
            }
        }"
        x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
        x-transition:enter-start="opacity-0 scale-105 blur-2xl"
        x-transition:enter-end="opacity-100 scale-100 blur-0"
        x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-105 blur-2xl"
        @transitionend="if(!@js($showSearch)) document.body.style.overflow = ''"
        class="fixed inset-0 z-[150] bg-zinc-950/60 backdrop-blur-3xl"
        @click.self="$wire.closeSearch()"
        @keydown.escape.window="$wire.closeSearch()"
        wire:key="search-modal-{{ $showSearch }}"
    >
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-emerald-500/30 to-transparent"></div>

        <!-- Search Container -->
        <div class="fixed inset-0 flex items-start justify-center pt-32 px-6" wire:click.stop>
            <div class="w-full max-w-3xl">
                <!-- Search Module -->
                <div class="bg-zinc-950/60 border border-zinc-800 rounded-[3.5rem] overflow-hidden shadow-[0_50px_100px_rgba(0,0,0,0.8)] flex flex-col backdrop-blur-3xl">
                    <div class="p-10 bg-zinc-950/40 border-b border-zinc-800/50">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-[10px] font-black text-white uppercase tracking-[0.6em] flex items-center gap-4 italic font-bold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Search
                            </h2>
                            <button 
                                wire:click="closeSearch"
                                class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800 text-zinc-500 hover:text-rose-500 hover:bg-rose-500/10 transition-all flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <!-- Filters -->
                        @if($query && strlen(trim($query)) > 0)
                            <div class="flex gap-4 mb-8">
                                @foreach(['all' => 'All', 'users' => 'Users', 'posts' => 'Posts'] as $type => $label)
                                    <button 
                                        wire:click="setResultType('{{ $type }}')"
                                        class="px-8 py-3 rounded-2xl text-[9px] font-black uppercase tracking-[0.3em] italic transition-all duration-500 {{ ($resultType ?? 'all') === $type ? 'bg-emerald-500 text-black shadow-lg shadow-emerald-500/20' : 'bg-zinc-950 text-zinc-600 hover:text-zinc-400 border border-zinc-800' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        <!-- Input Terminal -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-8 flex items-center pointer-events-none">
                                <svg class="w-6 h-6 text-zinc-700 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text"
                                wire:model.live.debounce.300ms="query"
                                placeholder="INITIALIZE KEYWORD SEARCH..."
                                class="w-full pl-20 pr-8 py-6 bg-zinc-950 border border-zinc-800/50 rounded-[2rem] text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/30 transition-all text-xs font-black uppercase tracking-widest shadow-inner italic"
                                autofocus>
                        </div>
                    </div>

                    <!-- Scan Results Area -->
                    <div class="overflow-y-auto max-h-[50vh] custom-scrollbar bg-zinc-900/50">
                        @if($query && strlen(trim($query)) > 0)
                            <!-- Identity Results -->
                            @if(in_array($resultType ?? 'all', ['all', 'users']) && $users->count() > 0)
                                <div class="px-8 py-10 border-b border-zinc-800/30">
                                    <h3 class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.5em] mb-8 ml-2 italic">Users</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @php $displayUsers = ($this->resultType === 'all') ? $users->take(4) : $users; @endphp
                                        @foreach($displayUsers as $index => $user)
                                            <a 
                                                href="{{ route('user.profile', $user->username ?? 'unknown') }}"
                                                wire:click="closeSearch"
                                                class="flex items-center gap-5 p-5 bg-zinc-950/40 border border-zinc-800/50 rounded-3xl hover:bg-emerald-500/5 hover:border-emerald-500/30 transition-all duration-500 group"
                                            >
                                                <div class="w-16 h-16 rounded-2xl bg-zinc-900 border-2 border-zinc-800 overflow-hidden flex items-center justify-center p-0.5 shrink-0 group-hover:scale-105 transition-all">
                                                    <div class="w-full h-full rounded-xl overflow-hidden bg-zinc-800">
                                                        @if($user->profile_photo_path)
                                                            <img src="{{ $user->profile_photo_url }}" class="w-full h-full object-cover grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-[10px] font-black text-emerald-500">{{ substr($user->name, 0, 1) }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-[11px] font-black text-white group-hover:text-emerald-400 transition-colors uppercase tracking-widest italic truncate font-bold">
                                                        {!! str_ireplace(e($query), '<mark class="bg-emerald-500/20 text-emerald-300">'.e($query).'</mark>', e($user->name)) !!}
                                                    </h4>
                                                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mt-1 opacity-80">
                                                       {{ '@' . $user->username }}
                                                    </p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                    
                                    @if($this->resultType === 'all' && $users->total() > 4)
                                        <div class="mt-8 flex justify-center">
                                            <button wire:click="setResultType('users')" class="px-8 py-3 bg-zinc-950 border border-zinc-800 rounded-2xl text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 hover:text-white hover:bg-zinc-800 transition-all italic font-bold">View all users ({{ $users->total() }})</button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Log Results -->
                            @if(in_array($resultType ?? 'all', ['all', 'posts']) && $posts->count() > 0)
                                <div class="px-8 py-10 transition-all">
                                    <h3 class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.5em] mb-8 ml-2 italic">Posts</h3>
                                    <div class="space-y-4">
                                        @php $displayPosts = ($this->resultType === 'all') ? $posts->take(4) : $posts; @endphp
                                        @foreach($displayPosts as $index => $post)
                                            <a 
                                                href="{{ route('posts.show', $post->slug) }}"
                                                wire:click="closeSearch"
                                                class="block p-8 bg-zinc-950/40 border border-zinc-800/50 rounded-[2.5rem] hover:bg-emerald-500/5 hover:border-emerald-500/30 transition-all duration-500 group"
                                            >
                                                <div class="flex items-center justify-between mb-4">
                                                    <p class="text-[8px] font-black text-zinc-600 uppercase tracking-[0.4em] italic">{{ $post->user->username }} · {{ $post->created_at->diffForHumans() }}</p>
                                                    <div class="flex gap-2">
                                                        @foreach($post->tags->take(2) as $tag)
                                                            <span class="px-2 py-0.5 bg-zinc-800 rounded-lg text-[7px] font-black text-zinc-500 uppercase tracking-widest">#{{ $tag->name }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <h3 class="text-[13px] font-black text-white group-hover:text-emerald-400 transition-colors uppercase tracking-tight italic mb-3">
                                                    {!! str_ireplace(e($query), '<mark class="bg-emerald-500/20 text-emerald-300">'.e($query).'</mark>', e($post->title ?? 'Log Capture')) !!}
                                                </h3>
                                                <p class="text-[11px] font-medium text-zinc-500 line-clamp-2 leading-relaxed italic selection:bg-emerald-500/20">
                                                    {{ Str::limit($post->content, 180) }}
                                                </p>
                                            </a>
                                        @endforeach
                                    </div>

                                    @if($this->resultType === 'all' && $posts->total() > 4)
                                        <div class="mt-8 flex justify-center">
                                            <button wire:click="setResultType('posts')" class="px-8 py-3 bg-zinc-950 border border-zinc-800 rounded-2xl text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 hover:text-white hover:bg-zinc-800 transition-all italic font-bold">Expand Log Archive ({{ $posts->total() }})</button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Null Stream -->
                            @if((($resultType ?? 'all') === 'all' && $posts->count() === 0 && $users->count() === 0) || (($resultType ?? 'all') === 'users' && $users->count() === 0) || (($resultType ?? 'all') === 'posts' && $posts->count() === 0))
                                <div class="py-32 text-center group">
                                    <div class="w-20 h-20 bg-zinc-950 border border-zinc-800 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-inner group-hover:scale-110 transition-all duration-1000">
                                        <svg class="w-10 h-10 text-zinc-800 group-hover:text-rose-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-white italic uppercase tracking-tighter">Null Result</h3>
                                    <p class="mt-4 text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em]">Zero data nodes matching your scan parameters.</p>
                                </div>
                            @endif
                        @else
                            <!-- Initial State -->
                            <div class="py-32 text-center group">
                                <div class="w-20 h-20 bg-zinc-950 border border-zinc-800 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-inner group-hover:scale-110 transition-all duration-1000">
                                    <svg class="w-10 h-10 text-zinc-800 group-hover:text-emerald-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <h3 class="text-xl font-black text-white italic uppercase tracking-tighter italic">Scanner Online</h3>
                                <p class="mt-4 text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em]">Initialize keywords to scan the global intelligence stream.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
