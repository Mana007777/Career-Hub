<div
    class="min-h-screen bg-zinc-950 text-white pb-24"
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
                <span class="text-[10px] font-black uppercase tracking-[0.4em] italic">Abort Database Session</span>
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
                <h1 class="text-5xl font-black text-white uppercase tracking-tighter italic">Mission <span class="text-emerald-500 selection:bg-emerald-500/30">Intelligence</span></h1>
            </div>
            <p class="text-[11px] font-black text-zinc-500 uppercase tracking-[0.5em] italic">Archive of Received Personnel Data Streams</p>
        </div>

        <!-- CVs List -->
        <div class="space-y-10">
            @forelse($cvs as $index => $cv)
                <div 
                    class="bg-zinc-900/40 border border-zinc-800/50 rounded-[3rem] p-10 backdrop-blur-3xl relative overflow-hidden group shadow-[0_30px_60px_rgba(0,0,0,0.4)]"
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
                                <div class="w-16 h-16 rounded-2xl bg-zinc-950 border-2 border-zinc-800 flex items-center justify-center shrink-0 p-0.5 group-hover:border-emerald-500/30 transition-all duration-700">
                                    <div class="w-full h-full rounded-xl bg-zinc-900 flex items-center justify-center">
                                        @if($cv->user->profile_photo_path)
                                            <img src="{{ $cv->user->profile_photo_url }}" class="w-full h-full object-cover grayscale opacity-50 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-1000">
                                        @else
                                            <span class="text-xl font-black text-emerald-500/40 group-hover:text-emerald-500 transition-colors">
                                                {{ strtoupper(substr($cv->user->name ?? 'U', 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-white uppercase tracking-tight italic">{{ $cv->user->name ?? 'Unknown Signal' }}</h3>
                                    <p class="text-[9px] font-black text-emerald-500/50 uppercase tracking-[0.4em] mt-1">{{ $cv->created_at->format('Y.m.d // H:i') }}</p>
                                </div>
                            </div>

                            <!-- Context Block -->
                            <div class="bg-zinc-950/50 border border-zinc-800/50 rounded-[2rem] p-8 mb-8 group/card transition-all duration-700 hover:border-emerald-500/20 shadow-inner">
                                <div class="flex items-center gap-4 mb-4">
                                    <span class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em]">Target Objective</span>
                                    <div class="flex-1 h-px bg-zinc-800/50"></div>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <a href="{{ route('posts.show', $cv->post->slug) }}" class="text-xl font-black text-white hover:text-emerald-400 transition-colors uppercase italic tracking-tighter">
                                        {{ $cv->post->title ?: 'Log Analysis' }}
                                    </a>
                                    @if($cv->post->job_type)
                                        <span class="px-4 py-1.5 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-500 text-[8px] font-black uppercase tracking-widest whitespace-nowrap">
                                            {{ strtoupper(str_replace('-', ' ', $cv->post->job_type)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Intelligence Payload -->
                            @if($cv->message)
                                <div class="bg-zinc-950/30 border-l-4 border-emerald-500/30 p-8 rounded-2xl mb-8">
                                    <p class="text-zinc-400 text-sm italic leading-relaxed selection:bg-emerald-500/20 font-medium">"{{ $cv->message }}"</p>
                                </div>
                            @endif

                            <!-- Resource Metadata -->
                            <div class="flex items-center gap-4 px-6 py-3 bg-zinc-900/30 border border-zinc-800/50 rounded-2xl w-fit">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </div>
                                <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest truncate max-w-[200px] italic">{{ $cv->original_filename }}</span>
                            </div>
                        </div>

                        <!-- Retrieval Node -->
                        <div class="w-full md:w-auto self-end">
                            <button
                                wire:click="downloadCv({{ $cv->id }})"
                                wire:loading.attr="disabled"
                                class="w-full md:w-auto px-10 py-5 bg-emerald-500 text-black text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl transition-all duration-500 shadow-xl shadow-emerald-500/10 hover:shadow-emerald-500/20 hover:bg-emerald-400 group/btn flex items-center justify-center gap-4 disabled:opacity-30">
                                <span wire:loading.remove wire:target="downloadCv">Retrieve Intelligence</span>
                                <span wire:loading wire:target="downloadCv" class="flex items-center gap-3">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Decrypting...
                                </span>
                                <svg class="w-5 h-5 group-hover/btn:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-40 bg-zinc-900/20 border border-dashed border-zinc-800/50 rounded-[3rem] text-center group">
                    <div class="w-24 h-24 bg-zinc-950 border border-zinc-900/50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 shadow-inner group-hover:scale-110 transition-all duration-1000">
                        <svg class="w-12 h-12 text-zinc-800 group-hover:text-emerald-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white italic uppercase tracking-tighter italic">Database Null</h3>
                    <p class="mt-4 text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em]">No external personnel broadcasts have been indexed.</p>
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
