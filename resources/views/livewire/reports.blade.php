<div class="min-h-screen bg-transparent text-white pb-24" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">
    <div class="max-w-7xl mx-auto px-6 py-12" x-show="loaded" x-cloak>
        <!-- Back (same pattern as CVs / explore) -->
        <div
            class="mb-12"
            x-show="loaded"
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
            x-transition:enter-start="opacity-0 -translate-x-10"
            x-transition:enter-end="opacity-100 translate-x-0"
        >
            <a
                href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-4 text-emerald-500/70 hover:text-emerald-400 transition-all duration-500 group"
            >
                <div class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800/50 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-black transition-all duration-500 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] italic">Return to Command Center</span>
            </a>
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
                <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                <h1 class="text-5xl font-black text-white uppercase tracking-tighter italic">Discrepancy <span class="text-rose-500">Scan</span></h1>
            </div>
            <p class="text-[11px] font-black text-zinc-500 uppercase tracking-[0.5em] italic">System-wide surveillance and report management portal</p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-10 p-6 bg-emerald-500/10 border border-emerald-500/20 rounded-[2rem] text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] backdrop-blur-3xl flex items-center gap-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                <span>Action Committed Successfully</span>
            </div>
        @endif

        <!-- Reports List -->
        <div class="space-y-6">
            @forelse ($reports as $index => $report)
                <div 
                    class="group relative bg-zinc-900/40 border border-zinc-800/50 rounded-[3rem] p-10 backdrop-blur-3xl transition-all duration-700 hover:border-rose-500/30 shadow-[0_30px_60px_rgba(0,0,0,0.3)]"
                    x-show="loaded"
                    x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
                    x-transition:enter-start="opacity-0 translate-y-10 blur-xl px-20 font-bold"
                    x-transition:enter-end="opacity-100 translate-y-0 blur-0"
                    style="transition-delay: {{ $index * 50 }}ms"
                >
                    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-rose-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

                    <div class="flex flex-col lg:flex-row items-start justify-between gap-10">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-4 mb-8">
                                <span class="px-6 py-2 bg-zinc-950 border {{ $report->status === 'pending' ? 'border-amber-500/30 text-amber-500' : 'border-emerald-500/30 text-emerald-400' }} rounded-xl text-[8px] font-black uppercase tracking-[0.3em] backdrop-blur-3xl italic">
                                    {{ $report->status }}
                                </span>
                                <span class="px-6 py-2 bg-zinc-950 border border-zinc-800 text-zinc-500 rounded-xl text-[8px] font-black uppercase tracking-[0.3em] italic">
                                    Target: {{ $report->target_type }}
                                </span>
                            </div>

                            <div class="space-y-6">
                                <div class="flex items-center gap-4 text-sm font-black italic">
                                    <span class="text-rose-500/50 uppercase tracking-widest text-[9px]">Signal Source:</span>
                                    <a href="{{ route('user.profile', $report->reporter->username ?? 'unknown') }}" class="text-white hover:text-rose-400 transition-colors uppercase tracking-tight">
                                        {{ $report->reporter->name }} <span class="text-zinc-600 block sm:inline text-[10px] sm:ml-2">@ {{ $report->reporter->username }}</span>
                                    </a>
                                </div>
                                <div class="bg-zinc-950 border border-zinc-800 rounded-[2rem] p-8 shadow-inner">
                                    <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.4em] mb-4 italic">Observation Logic:</p>
                                    <p class="text-lg font-black text-white italic tracking-tight uppercase">{{ $report->reason }}</p>
                                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mt-6">Logged {{ $report->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <!-- Payload Preview -->
                            <div class="mt-8 p-10 bg-zinc-900/60 rounded-[2.5rem] border border-zinc-800/80 group/payload hover:border-zinc-700 transition-all">
                                <h5 class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.5em] mb-8 italic">Transmission Payload Buffer</h5>
                                
                                @if($report->target_type === 'post' && $report->target)
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-4 mb-4">
                                            <div class="w-10 h-10 rounded-xl bg-zinc-950 border border-zinc-800 flex items-center justify-center"><svg class="w-5 h-5 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" /></svg></div>
                                            <p class="text-[11px] font-black text-zinc-400 uppercase tracking-widest italic">Identity: @ {{ $report->target->user->username }}</p>
                                        </div>
                                        @if($report->target->title)
                                            <h3 class="text-xl font-black text-white uppercase italic tracking-tighter">{{ $report->target->title }}</h3>
                                        @endif
                                        <p class="text-sm text-zinc-500 font-bold italic line-clamp-3 leading-relaxed selection:bg-rose-500/20">"{{ $report->target->content }}"</p>
                                        <a href="{{ route('posts.show', $report->target->slug) }}" target="_blank" class="inline-flex items-center gap-2 mt-4 text-[9px] font-black text-rose-500 uppercase tracking-widest hover:text-rose-400 transition-colors italic">Deep Inspect Log →</a>
                                    </div>
                                @elseif($report->target_type === 'user' && $report->target)
                                    <div class="flex items-center gap-8">
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-zinc-950 border-2 border-zinc-800 flex items-center justify-center text-rose-500/30 font-black text-2xl uppercase italic">{{ substr($report->target->name, 0, 1) }}</div>
                                        <div>
                                            <h3 class="text-xl font-black text-white uppercase italic tracking-tighter italic font-bold italic">{{ $report->target->name }}</h3>
                                            <p class="text-[10px] font-black text-zinc-600 uppercase tracking-widest mt-1 italic">@ {{ $report->target->username }}</p>
                                            <p class="text-[9px] font-black text-zinc-800 uppercase tracking-[0.2em] mt-3 italic italic font-bold">Node ID: {{ $report->target->id }}</p>
                                        </div>
                                    </div>
                                @elseif($report->target_type === 'comment' && $report->target)
                                    <div class="space-y-4">
                                        <p class="text-[11px] font-black text-zinc-400 uppercase tracking-widest italic font-bold italic">Source: @ {{ $report->target->user->username }}</p>
                                        <p class="text-sm text-zinc-500 italic line-clamp-3 italic font-bold">"{{ $report->target->content }}"</p>
                                    </div>
                                @else
                                    <p class="text-[10px] font-black text-rose-500/40 uppercase tracking-[0.4em] italic font-bold">Target unit has been purged or is inaccessible.</p>
                                @endif
                            </div>
                        </div>

                        @if($report->status === 'pending')
                            <div class="flex flex-col gap-4 w-full lg:w-auto shrink-0">
                                <button 
                                    wire:click="openActionModal({{ $report->id }}, 'delete')"
                                    class="w-full lg:px-12 py-5 bg-rose-500 text-black text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl shadow-xl shadow-rose-500/10 hover:bg-rose-400 transition-all italic italic font-bold">
                                    Execute Purge
                                </button>
                                <button 
                                    wire:click="openActionModal({{ $report->id }}, 'dismiss')"
                                    class="w-full lg:px-12 py-5 bg-zinc-950 border border-zinc-800 text-zinc-500 text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-zinc-800 hover:text-white transition-all italic">
                                    Dismiss Flag
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-60 bg-zinc-900/20 border border-dashed border-zinc-800/50 rounded-[4rem] text-center group">
                    <div class="w-32 h-32 bg-zinc-950 border border-zinc-800 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 shadow-inner group-hover:scale-110 transition-all duration-1000">
                        <svg class="w-16 h-16 text-zinc-800 group-hover:text-emerald-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-3xl font-black text-white italic uppercase tracking-tighter">Zero Discrepancies</h3>
                    <p class="mt-4 text-[10px] font-black text-zinc-600 uppercase tracking-[0.5em] italic">All systemic signals are within nominal parameters.</p>
                </div>
            @endforelse
        </div>

        @if($reports->hasPages())
            <div class="mt-20">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

    <!-- Action Confirmation Matrix -->
    @if($showActionModal && $selectedReport)
        <div class="fixed inset-0 z-[300] flex items-center justify-center p-6 bg-zinc-950/98 backdrop-blur-3xl" wire:click="closeActionModal">
            <div class="bg-zinc-900 border border-zinc-800 rounded-[3rem] max-w-lg w-full overflow-hidden shadow-[0_50px_100px_rgba(0,0,0,1)] relative" wire:click.stop>
                 <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-rose-500/30 to-transparent"></div>
                 <div class="p-10 border-b border-zinc-800/50 bg-zinc-950/40">
                    <h3 class="text-[10px] font-black text-white uppercase tracking-[0.5em] italic">
                        {{ $actionType === 'delete' ? 'Authorization Req: Purge ' . $selectedReport->target_type : 'Authorization Req: Dismiss Signal' }}
                    </h3>
                 </div>
                 <div class="p-10">
                    <p class="text-zinc-500 text-sm font-bold italic leading-relaxed mb-10 uppercase tracking-tight">
                        @if($actionType === 'delete')
                            Are you certain you wish to terminate this {{ $selectedReport->target_type }} unit? This command is irreversible and will purge all related data strings from the matrix.
                        @else
                            Mark this transmission as acknowledged and dismiss systemic flags? The signal will be archived as resolved.
                        @endif
                    </p>
                    <div class="flex items-center gap-6">
                        <button wire:click="closeActionModal" class="flex-1 py-5 text-[10px] font-black text-zinc-600 uppercase tracking-widest hover:text-white transition-colors italic italic font-bold italic font-bold">Abort Cmd</button>
                        <button 
                            wire:click="executeAction"
                            class="flex-1 py-5 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] font-bold italic shadow-xl {{ $actionType === 'delete' ? 'bg-rose-500 text-black shadow-rose-500/10 hover:bg-rose-400' : 'bg-zinc-950 border border-zinc-800 text-white hover:bg-zinc-800' }}">
                            Authorize Commit
                        </button>
                    </div>
                 </div>
            </div>
        </div>
    @endif
</div>
