<div
    class="min-h-screen bg-black text-gray-100 pb-24"
    x-data="{ loaded: false }"
    x-init="
        loaded = false;

        const setLoaded = () => { loaded = true };
        const setLoading = () => { loaded = false };

        document.addEventListener('livewire:load', setLoaded);
        document.addEventListener('livewire:navigated', setLoaded);
        document.addEventListener('livewire:navigating', setLoading);
    "
>
    <!-- Skeleton while CVs are loading -->
    <div x-show="!loaded">
        <x-skeleton.page-cards />
    </div>

    <!-- Actual content -->
    <div class="max-w-4xl mx-auto px-4 py-12" x-show="loaded" x-cloak>
        <!-- Back Button -->
        <div 
            class="mb-8"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 -translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
        >
            <button 
                onclick="window.history.back()"
                class="inline-flex items-center gap-2 text-gray-500 hover:text-white transition-all duration-300 transform hover:translate-x-1 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="text-[10px] font-black uppercase tracking-widest">Abort Session</span>
            </button>
        </div>

        <!-- Header -->
        <div 
            class="mb-12"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <h1 class="text-4xl font-black text-white uppercase tracking-tighter">Mission <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-purple to-brand-violet">Database</span></h1>
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mt-2">Inventory of Received Personnel Documents</p>
        </div>

        <!-- CVs List -->
        <div class="space-y-6">
            @forelse($cvs as $index => $cv)
                <div 
                    class="bg-brand-deep/10 border border-white/5 rounded-3xl p-8 shadow-3xl backdrop-blur-xl hover:border-white/10 transition-all duration-500 transform hover:scale-[1.01] hover:-translate-y-1"
                    x-data="{ show: false }"
                    x-init="
                        setTimeout(() => {
                            show = true;
                        }, {{ $index * 100 }});
                    "
                    x-show="show"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                >
                    <div class="flex flex-col md:flex-row items-start justify-between gap-8">
                        <div class="flex-1 w-full">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-14 h-14 rounded-2xl bg-brand-deep flex items-center justify-center border border-white/5 shadow-inner">
                                    <span class="text-[10px] font-black text-brand-violet uppercase">
                                        {{ strtoupper(substr($cv->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-[10px] font-black text-white uppercase tracking-widest">{{ $cv->user->name ?? 'Unknown User' }}</h3>
                                    <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mt-1">{{ $cv->created_at->format('F j, Y') }}</p>
                                </div>
                            </div>

                            <!-- Post Info -->
                            <div class="mt-8 p-5 bg-white/5 rounded-2xl border border-white/5 relative overflow-hidden group/card shadow-inner">
                                <div class="absolute inset-0 bg-gradient-to-br from-brand-purple/5 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-500"></div>
                                <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-3 relative z-10">Mission Objective:</p>
                                <div class="flex items-center justify-between relative z-10">
                                    <a 
                                        href="{{ route('posts.show', $cv->post->slug) }}"
                                        class="text-sm font-black text-brand-violet hover:text-brand-purple transition-all uppercase tracking-tight">
                                        {{ $cv->post->title ?: 'Untitled Post' }}
                                    </a>
                                    @if($cv->post->job_type)
                                        <span class="px-3 py-1 bg-brand-violet/10 text-brand-violet text-[8px] font-black uppercase tracking-widest rounded-lg border border-brand-violet/20">
                                            {{ str_replace('-', ' ', $cv->post->job_type) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Message -->
                            @if($cv->message)
                                <div class="mt-6 p-5 bg-black/40 rounded-2xl border border-white/5 shadow-inner">
                                    <p class="text-sm text-gray-400 leading-relaxed font-medium italic italic">"{{ $cv->message }}"</p>
                                </div>
                            @endif

                            <!-- CV File Info -->
                            <div class="mt-6 flex items-center gap-3">
                                <div class="p-2 bg-brand-purple/10 rounded-lg border border-brand-purple/20">
                                    <svg class="w-4 h-4 text-brand-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">{{ $cv->original_filename }}</span>
                            </div>
                        </div>

                        <!-- Download Button -->
                        <div class="w-full md:w-auto mt-4 md:mt-0">
                            <button
                                wire:click="downloadCv({{ $cv->id }})"
                                wire:loading.attr="disabled"
                                wire:target="downloadCv"
                                class="w-full md:w-auto px-8 py-4 bg-brand-purple hover:bg-brand-violet text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-brand-purple/20 disabled:opacity-50 flex items-center justify-center gap-3 group/btn hover:scale-[1.05] active:scale-[0.98]">
                                <span wire:loading.remove wire:target="downloadCv">Retrieve Document</span>
                                <span wire:loading wire:target="downloadCv" class="flex items-center gap-2">
                                    <svg class="animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                                <svg class="w-4 h-4 group-hover/btn:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-brand-deep/10 border border-white/5 rounded-3xl p-20 text-center backdrop-blur-xl shadow-3xl">
                    <div class="inline-flex p-8 rounded-3xl bg-white/5 mb-8 border border-white/5 shadow-inner">
                        <svg class="w-16 h-16 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-3">Database Empty</h3>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest max-w-xs mx-auto">No personnel documents have been retrieved from the field yet.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $cvs->links() }}
        </div>
    </div>
</div>
