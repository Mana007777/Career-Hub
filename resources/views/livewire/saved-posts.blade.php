<div
    class="min-h-screen bg-transparent text-white pb-24"
    x-data="{ loaded: false }"
    x-init="setTimeout(() => loaded = true, 50)"
>
    <div class="max-w-4xl mx-auto px-6 py-12">
        <!-- Back Button -->
        <div 
            class="mb-12"
            x-show="loaded"
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
            x-transition:enter-start="opacity-0 -translate-x-10"
            x-transition:enter-end="opacity-100 translate-x-0"
        >
            <button 
                onclick="window.history.back()"
                class="inline-flex items-center gap-4 text-emerald-500/70 hover:text-emerald-400 transition-all duration-500 group">
                <div class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800/50 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-black transition-all duration-500 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] italic">{{ __('Back to Home') }}</span>
            </button>
        </div>

        <!-- Header -->
        <div 
            class="mb-16"
            x-show="loaded"
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-1000"
            x-transition:enter-start="opacity-0 translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <div class="flex items-center gap-4 mb-4">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <h1 class="text-5xl font-black text-white uppercase tracking-tighter italic">{{ __('Saved') }} <span class="text-emerald-500">{{ __('Posts') }}</span></h1>
            </div>
            <p class="text-[11px] font-black text-zinc-500 uppercase tracking-[0.5em] italic">{{ __('Your saved posts · :count total', ['count' => $posts->total()]) }}</p>
        </div>

        <!-- Saved Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $index => $post)
                <article
                    onclick="window.location.href='{{ route('posts.show', $post->slug) }}'"
                    class="group relative h-full flex flex-col bg-zinc-950/60 border border-zinc-800/50 rounded-[2.5rem] p-8 transition-all duration-700 hover:border-emerald-500/30 hover:bg-emerald-500/[0.02] shadow-[0_30px_60px_rgba(0,0,0,0.3)] backdrop-blur-3xl cursor-pointer overflow-hidden"
                    x-show="loaded"
                    x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
                    x-transition:enter-start="opacity-0 translate-y-20 blur-xl scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 blur-0 scale-100"
                    style="transition-delay: {{ $index * 50 }}ms"
                >
                    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

                    <div class="flex items-start justify-between mb-8 relative z-10">
                        <a href="{{ route('user.profile', $post->user->username ?? 'unknown') }}" onclick="event.stopPropagation()" class="flex items-center gap-4 group/author">
                            <div class="w-12 h-12 rounded-xl bg-zinc-950 border border-zinc-800 overflow-hidden flex items-center justify-center p-0.5 shrink-0 group-hover/author:border-emerald-500/30 transition-all duration-700">
                                @if($post->user && $post->user->profile_photo_path)
                                    <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover grayscale opacity-50 group-hover/author:grayscale-0 group-hover/author:opacity-100 transition-all duration-1000">
                                @else
                                    <span class="text-emerald-500/40 font-black text-xs">
                                        {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-[11px] font-black text-zinc-400 group-hover/author:text-white transition-colors truncate uppercase tracking-widest">{{ $post->user->name ?? 'Unknown' }}</h3>
                                    @if($post->user && $post->user->hasBlueTick())
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-500/10 border border-emerald-400/30 text-emerald-300 shadow-[0_0_14px_rgba(16,185,129,0.35)] animate-pulse" title="{{ __('Verified') }}">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M12 2l2.3 5.1L20 9l-4 4.1L17 19l-5-2.9L7 19l1-5.9L4 9l5.7-1.9L12 2z"/>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[8px] font-black text-zinc-700 uppercase tracking-[0.2em] mt-1 italic">{{ \App\Support\SoraniTime::human($post->created_at) }}</p>
                            </div>
                        </a>
                    </div>

                    <div class="mb-8 flex-1 relative z-10">
                        @if(!empty($post->title))
                            <h2 class="text-lg font-black text-white mb-3 group-hover:text-emerald-400 transition-colors uppercase tracking-tight italic">
                                {{ $post->title }}
                            </h2>
                        @endif
                        <p class="text-zinc-500 text-sm leading-relaxed line-clamp-4 italic selection:bg-emerald-500/20 font-medium font-bold">
                            {{ $post->content }}
                        </p>
                    </div>

                    @if ($post->media)
                        <div class="mb-8 rounded-2xl overflow-hidden border border-zinc-800 relative group/media">
                            @php
                                $mediaUrl = app(\App\Services\PostService::class)->getMediaUrl($post);
                                $isImage = in_array(strtolower(pathinfo($post->media, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            @if($isImage)
                                <img src="{{ $mediaUrl }}" alt="{{ __('Post media') }}" class="w-full h-40 object-cover grayscale opacity-50 group-hover/media:grayscale-0 group-hover/media:opacity-100 transition-all duration-1000">
                                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/80 to-transparent"></div>
                            @else
                                <div class="bg-zinc-950 py-6 px-8 flex items-center justify-between group-hover:bg-emerald-500/5 transition-colors duration-700">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L13.732 14M5 18H13a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg></div>
                                        <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">{{ __('Media') }}</span>
                                    </div>
                                    <svg class="w-4 h-4 text-emerald-500/30 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7" /></svg>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="flex items-center justify-between pt-6 border-t border-zinc-800/50 relative z-10">
                        <div class="flex items-center gap-2 text-[9px] font-black text-zinc-600 uppercase tracking-widest">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500/40"></div>
                            <span>{{ __(':count Reactions', ['count' => $post->stars->count()]) }}</span>
                        </div>

                        <button
                            type="button"
                            wire:click.stop="togglePostSave({{ $post->id }})"
                            class="flex items-center gap-3 px-6 py-2 bg-zinc-950 border border-zinc-800 rounded-xl text-[9px] font-black uppercase tracking-widest text-zinc-500 hover:text-rose-500 hover:border-rose-500/30 transition-all duration-500 italic">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M5 5a2 2 0 012-2h10a1 1 0 011 1v15.382a1 1 0 01-1.555.832L12 17.5l-4.445 2.714A1 1 0 016 19.382V4a1 1 0 011-1z" /></svg>
                            <span>{{ __('Unsave') }}</span>
                        </button>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-40 bg-zinc-900/20 border border-dashed border-zinc-800/50 rounded-[3.5rem] text-center group">
                    <div class="w-24 h-24 bg-zinc-950 border border-zinc-800/50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 shadow-inner group-hover:scale-110 transition-all duration-1000">
                        <svg class="h-10 w-10 text-zinc-800 group-hover:text-emerald-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M5 5a2 2 0 012-2h10a1 1 0 011 1v15.382a1 1 0 01-1.555.832L12 17.5l-4.445 2.714A1 1 0 016 19.382V4a1 1 0 011-1z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white italic uppercase tracking-tighter">{{ __('No Saved Posts') }}</h3>
                    <p class="mt-4 text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em]">{{ __('You have no saved posts yet.') }}</p>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="mt-20">
                <x-pagination :paginator="$posts" />
            </div>
        @endif
    </div>
</div>
