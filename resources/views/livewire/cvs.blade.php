<div
    class="min-h-screen bg-transparent pb-24 text-zinc-900 dark:text-zinc-100"
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
                class="inline-flex items-center gap-4 text-emerald-600/80 transition-all duration-500 group hover:text-emerald-500 dark:text-emerald-500/70 dark:hover:text-emerald-400">
                <div class="w-12 h-12 rounded-2xl border border-zinc-200 bg-white flex items-center justify-center shadow-lg transition-all duration-500 group-hover:bg-emerald-500 group-hover:text-black dark:border-zinc-800/50 dark:bg-zinc-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] italic text-zinc-600 dark:text-inherit">{{ __('Back to Home') }}</span>
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
                @if($isSeekerCvView ?? false)
                    <h1 class="text-4xl font-black uppercase tracking-tighter italic text-zinc-900 sm:text-5xl dark:text-white">{{ __('My') }} <span class="text-emerald-600 selection:bg-emerald-500/20 dark:text-emerald-500 dark:selection:bg-emerald-500/30">{{ __('CV uploads') }}</span></h1>
                @else
                    <h1 class="text-4xl font-black uppercase tracking-tighter italic text-zinc-900 sm:text-5xl dark:text-white">{{ __('Received') }} <span class="text-emerald-600 selection:bg-emerald-500/20 dark:text-emerald-500 dark:selection:bg-emerald-500/30">{{ __('CVs') }}</span></h1>
                @endif
            </div>
            <p class="text-[11px] font-black uppercase tracking-[0.5em] italic text-zinc-500 dark:text-zinc-500">
                {{ ($isSeekerCvView ?? false) ? __('CVs you have submitted to job posts') : __('All CV submissions from users') }}
            </p>
        </div>

        <!-- CVs List -->
        <div class="space-y-10">
            @forelse($cvs as $index => $cv)
                <div
                    class="group relative rounded-[3rem] border border-zinc-200/80 bg-white/90 p-10 shadow-[0_30px_60px_rgba(0,0,0,0.08)] backdrop-blur-3xl dark:border-zinc-800/50 dark:bg-zinc-950/60 dark:shadow-[0_30px_60px_rgba(0,0,0,0.4)]"
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, {{ $index * 100 }})"
                    x-show="show"
                    x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-1000"
                    x-transition:enter-start="opacity-0 translate-y-20 blur-xl scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 blur-0 scale-100"
                >
                    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent"></div>
                    
                    <div class="flex flex-col md:flex-row items-start justify-between gap-12 relative z-10">
                        <div class="flex-1 w-full">
                            <div class="flex items-center gap-6 mb-10">
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border-2 border-zinc-200 bg-zinc-50 p-0.5 transition-all duration-700 group-hover:border-emerald-500/30 dark:border-zinc-800 dark:bg-zinc-950">
                                    <div class="flex h-full w-full items-center justify-center rounded-xl bg-white dark:bg-zinc-900">
                                        @if($isSeekerCvView ?? false)
                                            @php $publisher = $cv->post->user ?? null; @endphp
                                            @if($publisher && $publisher->profile_photo_path)
                                                <img src="{{ $publisher->profile_photo_url }}" alt="" class="h-full w-full object-cover grayscale opacity-50 transition-all duration-1000 group-hover:grayscale-0 group-hover:opacity-100">
                                            @else
                                                <span class="text-xl font-black text-emerald-600/40 transition-colors group-hover:text-emerald-600 dark:text-emerald-500/40 dark:group-hover:text-emerald-500">
                                                    {{ strtoupper(substr($publisher->name ?? 'C', 0, 1)) }}
                                                </span>
                                            @endif
                                        @else
                                            @if($cv->user->profile_photo_path)
                                                <img src="{{ $cv->user->profile_photo_url }}" class="h-full w-full object-cover grayscale opacity-50 transition-all duration-1000 group-hover:grayscale-0 group-hover:opacity-100">
                                            @else
                                                <span class="text-xl font-black text-emerald-600/40 transition-colors group-hover:text-emerald-600 dark:text-emerald-500/40 dark:group-hover:text-emerald-500">
                                                    {{ strtoupper(substr($cv->user->name ?? 'U', 0, 1)) }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    @if($isSeekerCvView ?? false)
                                        @php $publisher = $cv->post->user ?? null; @endphp
                                        <p class="text-[9px] font-black uppercase tracking-[0.35em] text-zinc-500 dark:text-zinc-500">{{ __('Company') }}</p>
                                        <h3 class="text-lg font-black uppercase italic tracking-tight text-zinc-900 dark:text-white">{{ $publisher->name ?? __('Unknown company') }}</h3>
                                    @else
                                        <h3 class="text-lg font-black uppercase italic tracking-tight text-zinc-900 dark:text-white">{{ $cv->user->name ?? __('Unknown User') }}</h3>
                                    @endif
                                    <p class="mt-1 text-[9px] font-black uppercase tracking-[0.4em] text-emerald-700/70 dark:text-emerald-500/50">{{ $cv->created_at->format('Y.m.d // H:i') }}</p>
                                </div>
                            </div>

                            <!-- Context Block -->
                            <div class="mb-8 rounded-[2rem] border border-zinc-200/80 bg-zinc-50/80 p-8 shadow-inner transition-all duration-700 group/card hover:border-emerald-500/25 dark:border-zinc-800/50 dark:bg-zinc-950/50 dark:hover:border-emerald-500/20">
                                <div class="mb-4 flex items-center gap-4">
                                    <span class="text-[9px] font-black uppercase tracking-[0.4em] text-zinc-500 dark:text-zinc-600">{{ ($isSeekerCvView ?? false) ? __('Job listing') : __('Target Post') }}</span>
                                    <div class="h-px flex-1 bg-zinc-200 dark:bg-zinc-800/50"></div>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <a href="{{ route('posts.show', $cv->post->slug) }}" class="text-xl font-black uppercase italic tracking-tighter text-zinc-900 transition-colors hover:text-emerald-600 dark:text-white dark:hover:text-emerald-400">
                                        {{ $cv->post->title ?: __('Post') }}
                                    </a>
                                    @if($cv->post->job_type)
                                        <span class="whitespace-nowrap rounded-xl border border-emerald-500/25 bg-emerald-500/10 px-4 py-1.5 text-[8px] font-black uppercase tracking-widest text-emerald-700 dark:border-emerald-500/20 dark:text-emerald-500">
                                            {{ strtoupper(str_replace('-', ' ', $cv->post->job_type)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- CV Details -->
                            @if($cv->message)
                                <div class="mb-8 rounded-2xl border-l-4 border-emerald-500/40 bg-zinc-100/80 p-8 dark:border-emerald-500/30 dark:bg-zinc-950/30">
                                    <p class="text-sm font-medium italic leading-relaxed text-zinc-600 selection:bg-emerald-500/15 dark:text-zinc-400 dark:selection:bg-emerald-500/20">"{{ $cv->message }}"</p>
                                </div>
                            @endif

                            <!-- Resource Metadata -->
                            <div class="flex w-fit items-center gap-4 rounded-2xl border border-zinc-200/80 bg-zinc-50/90 px-6 py-3 dark:border-zinc-800/50 dark:bg-zinc-900/30">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </div>
                                <span class="max-w-[200px] truncate text-[9px] font-black uppercase italic tracking-widest text-zinc-600 dark:text-zinc-500">{{ $cv->original_filename }}</span>
                            </div>
                        </div>

                        <!-- Download -->
                        <div class="w-full md:w-auto self-end">
                            <button
                                wire:click="downloadCv({{ $cv->id }})"
                                wire:loading.attr="disabled"
                                class="w-full md:w-auto px-10 py-5 bg-emerald-500 text-black text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl transition-all duration-500 shadow-xl shadow-emerald-500/10 hover:shadow-emerald-500/20 hover:bg-emerald-400 group/btn flex items-center justify-center gap-4 disabled:opacity-30">
                                <span wire:loading.remove wire:target="downloadCv">{{ __('Download CV') }}</span>
                                <span wire:loading wire:target="downloadCv" class="flex items-center gap-3">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    {{ __('Downloading...') }}
                                </span>
                                <svg class="w-5 h-5 group-hover/btn:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="group rounded-[3rem] border border-dashed border-zinc-300/90 bg-zinc-50/60 py-40 text-center dark:border-zinc-800/50 dark:bg-zinc-900/20">
                    <div class="mx-auto mb-8 flex h-24 w-24 items-center justify-center rounded-[2.5rem] border border-zinc-200 bg-white shadow-inner transition-all duration-1000 group-hover:scale-110 dark:border-zinc-900/50 dark:bg-zinc-950">
                        <svg class="h-12 w-12 text-zinc-400 transition-colors group-hover:text-emerald-600/40 dark:text-zinc-800 dark:group-hover:text-emerald-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black uppercase italic tracking-tighter text-zinc-900 dark:text-white">{{ __('No CVs Found') }}</h3>
                    <p class="mt-4 text-[10px] font-black uppercase tracking-[0.4em] text-zinc-500 dark:text-zinc-600">
                        {{ ($isSeekerCvView ?? false) ? __('You have not uploaded a CV to any job post yet.') : __('No CV submissions have been received yet.') }}
                    </p>
                </div>
            @endforelse
        </div>

        @if($cvs && $cvs->hasPages())
            <div class="mt-20 px-4">
                {{ $cvs->links() }}
            </div>
        @endif
    </div>
</div>
