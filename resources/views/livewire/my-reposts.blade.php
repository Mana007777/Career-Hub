<div
    class="min-h-screen bg-transparent text-zinc-900 pb-32 dark:text-zinc-100"
    x-data="{ loaded: false }"
    x-init="setTimeout(() => loaded = true, 50)"
>
    <div class="max-w-4xl mx-auto px-6 py-12">
        <div
            class="mb-12"
            x-show="loaded"
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
            x-transition:enter-start="opacity-0 -translate-x-10"
            x-transition:enter-end="opacity-100 translate-x-0"
        >
            <a
                href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-4 text-emerald-600/80 hover:text-emerald-500 transition-all duration-500 group dark:text-emerald-500/70 dark:hover:text-emerald-400"
            >
                <div class="w-12 h-12 rounded-2xl bg-white border border-zinc-200 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-black transition-all duration-500 shadow-lg dark:bg-zinc-900 dark:border-zinc-800/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] italic">{{ __('Back to Home') }}</span>
            </a>
        </div>

        <div
            class="mb-16"
            x-show="loaded"
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-1000"
            x-transition:enter-start="opacity-0 translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <div class="flex items-center gap-4 mb-4">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <h1 class="text-4xl font-black uppercase tracking-tighter italic text-zinc-900 sm:text-5xl dark:text-white">
                    {{ __('Your') }} <span class="text-emerald-600 dark:text-emerald-500">{{ __('Reposts') }}</span>
                </h1>
            </div>
            <p class="text-[11px] font-black uppercase tracking-[0.5em] text-zinc-500 italic dark:text-zinc-500">
                {{ __('Company posts you have reposted · :count total', ['count' => $posts->total()]) }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse($posts as $index => $post)
                <article
                    class="group relative flex h-full flex-col cursor-pointer overflow-hidden rounded-[2.5rem] border border-zinc-200/80 bg-white/90 p-8 shadow-[0_30px_60px_rgba(0,0,0,0.08)] backdrop-blur-3xl transition-all duration-700 hover:border-emerald-500/30 hover:bg-emerald-500/[0.03] dark:border-zinc-800/50 dark:bg-zinc-950/60 dark:shadow-[0_30px_60px_rgba(0,0,0,0.3)] dark:hover:bg-emerald-500/[0.02]"
                    onclick="window.location.href='{{ route('posts.show', $post->slug) }}'"
                    x-show="loaded"
                    x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
                    x-transition:enter-start="opacity-0 translate-y-20 blur-xl scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 blur-0 scale-100"
                    style="transition-delay: {{ $index * 50 }}ms"
                >
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-emerald-500/10 to-transparent opacity-0 transition-opacity duration-700 group-hover:opacity-100"></div>

                    <div class="relative z-10 mb-8 flex items-start justify-between">
                        <a href="{{ route('user.profile', $post->user->username ?? 'unknown') }}" onclick="event.stopPropagation()" class="group/author flex items-center gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-zinc-200 bg-zinc-50 p-0.5 transition-all duration-700 group-hover/author:border-emerald-500/30 dark:border-zinc-800 dark:bg-zinc-950">
                                @if($post->user && $post->user->profile_photo_path)
                                    <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}" class="h-full w-full object-cover grayscale opacity-50 transition-all duration-1000 group-hover/author:grayscale-0 group-hover/author:opacity-100">
                                @else
                                    <span class="text-xs font-black text-emerald-600/40 dark:text-emerald-500/40">
                                        {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="truncate text-[11px] font-black uppercase tracking-widest text-zinc-600 transition-colors group-hover/author:text-zinc-900 dark:text-zinc-400 dark:group-hover/author:text-white">{{ $post->user->name ?? __('Unknown') }}</h3>
                                <p class="mt-1 text-[8px] font-black uppercase tracking-[0.2em] text-zinc-400 italic dark:text-zinc-700">{{ \App\Support\SoraniTime::human($post->created_at) }}</p>
                            </div>
                        </a>
                    </div>

                    <div class="relative z-10 mb-8 flex-1 space-y-4">
                        @if(!empty($post->title))
                            <h2 class="text-lg font-black uppercase italic leading-tight tracking-tight text-zinc-900 transition-colors group-hover:text-emerald-600 dark:text-white dark:group-hover:text-emerald-400">
                                {{ $post->title }}
                            </h2>
                        @endif
                        <p class="line-clamp-4 text-sm font-medium leading-relaxed text-zinc-600 dark:text-zinc-400">
                            {{ $post->content }}
                        </p>
                    </div>

                    <div class="relative z-10 mt-auto flex items-center justify-between border-t border-zinc-200/80 pt-6 dark:border-zinc-800/50">
                        <span class="text-[9px] font-black uppercase tracking-widest text-zinc-400 dark:text-zinc-600">{{ __('Company post') }}</span>
                        <button
                            type="button"
                            wire:click.stop="removeRepost({{ $post->id }})"
                            class="rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-2 text-[9px] font-black uppercase tracking-widest text-zinc-600 transition-all duration-500 hover:border-rose-500/40 hover:text-rose-600 dark:border-zinc-800 dark:bg-zinc-950 dark:text-zinc-500 dark:hover:text-rose-500"
                        >
                            {{ __('Remove') }}
                        </button>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-[3.5rem] border border-dashed border-zinc-300/80 bg-zinc-50/50 py-40 text-center dark:border-zinc-800/50 dark:bg-zinc-900/20">
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-zinc-500 dark:text-zinc-600">{{ __('No reposts yet. Repost company posts from the feed.') }}</p>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="mt-20">
                <x-pagination :paginator="$posts" />
            </div>
        @endif
    </div>

    <div
        class="fixed bottom-0 left-1/2 z-50 mb-8 w-full max-w-xl -translate-x-1/2 rounded-[2.5rem] border border-zinc-200/80 bg-white/90 px-6 py-4 shadow-[0_50px_100px_rgba(0,0,0,0.12)] backdrop-blur-3xl dark:border-zinc-800/50 dark:bg-zinc-950/40 dark:shadow-[0_50px_100px_rgba(0,0,0,0.5)]"
    >
        <livewire:bottom-navigation />
    </div>
</div>
