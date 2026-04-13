<div class="group relative bg-zinc-900/40 border border-zinc-800/50 rounded-[3rem] overflow-hidden backdrop-blur-3xl hover:border-cyan-500/30 transition-all duration-700 shadow-[0_50px_100px_rgba(0,0,0,0.5)]">
    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-cyan-500/20 to-transparent"></div>
    
    <div class="p-10 sm:p-14">
        <div class="flex flex-col xl:flex-row items-center justify-between gap-12">
            <div class="flex flex-col md:flex-row items-center gap-10 text-center md:text-left">
                <div class="relative shrink-0">
                    <div class="absolute inset-0 bg-cyan-500 blur-[80px] opacity-10 group-hover:opacity-20 transition-opacity"></div>
                    <div class="relative w-28 h-28 rounded-[2rem] bg-zinc-950 flex items-center justify-center border-2 border-cyan-500/30 shadow-2xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-700">
                        <svg class="w-16 h-16 text-cyan-400 drop-shadow-[0_0_15px_rgba(34,211,238,0.5)]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.5 12.5c0-1.58-.88-2.95-2.18-3.65.18-.44.28-.92.28-1.42 0-2.03-1.65-3.68-3.68-3.68-.5 0-.98.1-1.42.28C14.8 2.73 13.43 1.85 11.85 1.85c-1.58 0-2.95.88-3.65 2.18-.44-.18-.92-.28-1.42-.28-2.03 0-3.68 1.65-3.68 3.68 0 .5.1.98.28 1.42C2.08 9.55 1.2 10.92 1.2 12.5c0 1.58.88 2.95 2.18 3.65-.18.44-.28.92-.28 1.42 0 2.03 1.65 3.68 3.68 3.68.5 0 .98-.1 1.42-.28 1.1 1.63 2.95 2.18 4.65 2.18s3.55-.55 4.65-2.18c.44.18.92.28 1.42.28 2.03 0 3.68-1.65 3.68-3.68 0-.5-.1-.98-.28-1.42 1.3-1.1 2.18-2.43 2.18-3.65zM10.3 17.5l-3.3-3.3 1.4-1.4 1.9 1.9 4.9-4.9 1.4 1.4-6.3 6.3z"/>
                        </svg>
                    </div>
                </div>
                <div class="space-y-4">
                    <h2 class="text-[10px] font-black text-cyan-500 uppercase tracking-[0.5em] italic">Identity <span class="text-white">Validation Protocol</span></h2>
                    <h3 class="text-5xl font-black text-white tracking-tighter uppercase italic leading-none">Blue <span class="text-cyan-500">Label</span></h3>
                    <p class="text-zinc-500 text-lg max-w-sm leading-relaxed italic font-bold">
                        Establish institutional trust. Encrypt your profile with the <span class="text-cyan-400 underline decoration-cyan-500/30 underline-offset-8 font-black">Authorized Identity</span> matrix.
                    </p>
                </div>
            </div>

            <div class="flex flex-col items-center gap-6 shrink-0">
                <div class="w-24 h-24 rounded-full border-2 border-zinc-800 flex items-center justify-center relative bg-zinc-950 p-2">
                    <div class="absolute inset-0 rounded-full border-2 border-cyan-500 opacity-20 animate-ping"></div>
                    <div class="w-full h-full rounded-full bg-zinc-900 flex items-center justify-center text-white font-black text-xl italic tracking-tighter">FIB</div>
                </div>
                <div class="inline-flex items-center px-10 py-3 bg-zinc-950 border border-zinc-800 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] italic {{ $verification?->payment_status === \FirstIraqiBank\FIBPaymentSDK\Model\FibPayment::PAID ? 'text-emerald-500 border-emerald-500/20' : 'text-zinc-700' }}">
                    {{ $verification?->payment_status === \FirstIraqiBank\FIBPaymentSDK\Model\FibPayment::PAID ? 'Verified Identity' : 'Null Protocol' }}
                </div>
            </div>
        </div>

        @if (! $canUsePayments)
            <div class="mt-16 p-8 bg-rose-500/10 border border-rose-500/20 rounded-3xl text-rose-500 text-[10px] font-black uppercase tracking-[0.5em] text-center italic">
                System Alert: Payment Uplink Offline. Initialize Manual Contact.
            </div>
        @else
            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-zinc-950/60 border border-zinc-800/80 rounded-[2.5rem] p-10 hover:border-cyan-500/20 transition-all duration-700 shadow-inner group/card">
                    <p class="text-[9px] font-black uppercase tracking-[0.4em] text-zinc-700 group-hover/card:text-cyan-500 transition-colors italic">Authorization Cost</p>
                    <p class="mt-4 text-3xl font-black text-white italic tracking-tighter">{{ number_format($amount) }} <span class="text-zinc-800 text-sm not-italic uppercase tracking-widest font-black italic ml-2">IQD</span></p>
                </div>
                <div class="bg-zinc-950/60 border border-zinc-800/80 rounded-[2.5rem] p-10 hover:border-cyan-500/20 transition-all duration-700 shadow-inner group/card">
                    <p class="text-[9px] font-black uppercase tracking-[0.4em] text-zinc-700 group-hover/card:text-cyan-500 transition-colors italic">Validation Tier</p>
                    <p class="mt-4 text-3xl font-black text-white italic tracking-tighter uppercase">{{ $verification?->type ?? 'Prime Node' }}</p>
                </div>
                <div class="bg-zinc-950/60 border border-zinc-800/80 rounded-[2.5rem] p-10 hover:border-cyan-500/20 transition-all duration-700 shadow-inner group/card">
                    <p class="text-[9px] font-black uppercase tracking-[0.4em] text-zinc-700 group-hover/card:text-cyan-500 transition-colors italic">Handshake State</p>
                    <p class="mt-4 text-3xl font-black text-white italic tracking-tighter uppercase truncate">{{ $verification?->payment_status ?? 'Idle' }}</p>
                </div>
            </div>

            @if ($paymentLink)
                <div class="mt-12 p-12 bg-cyan-500/5 border border-cyan-500/20 rounded-[4rem] relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-500/10 rounded-full blur-[100px]"></div>
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-10">
                        <div class="space-y-4">
                            <p class="text-[11px] font-black uppercase tracking-[0.4em] text-cyan-400 italic">External Uplink Established</p>
                            @if ($readableCode)
                                <div class="flex items-center gap-4">
                                    <span class="text-[9px] text-zinc-700 uppercase font-black italic">Transmission Hash:</span>
                                    <code class="px-5 py-2 bg-zinc-950 rounded-xl text-cyan-400 font-mono text-[11px] border border-cyan-500/20 shadow-inner">{{ $readableCode }}</code>
                                </div>
                            @endif
                        </div>
                        <a href="{{ $paymentLink }}" target="_blank"
                           class="w-full lg:w-auto px-16 py-6 rounded-[2rem] bg-cyan-500 text-black text-[11px] font-black uppercase tracking-[0.4em] hover:bg-cyan-400 shadow-[0_20px_60px_rgba(6,182,212,0.3)] hover:shadow-cyan-500/40 transition-all duration-700 flex items-center justify-center gap-4 italic italic font-bold">
                            Execute FIB Gateway
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                </div>
            @endif

            <div class="mt-16 flex flex-wrap justify-center gap-8">
                <button type="button"
                        wire:click="startPayment"
                        wire:loading.attr="disabled"
                        wire:target="startPayment"
                        class="px-16 py-7 rounded-[2.5rem] bg-white text-black text-[11px] font-black uppercase tracking-[0.5em] hover:bg-zinc-200 shadow-2xl transition-all active:scale-95 disabled:opacity-50 italic italic font-bold">
                    <span wire:loading.remove wire:target="startPayment">Initiate Protocol</span>
                    <span wire:loading wire:target="startPayment" class="flex items-center gap-4"><svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Transmitting...</span>
                </button>

                <button type="button"
                        wire:click="refreshPaymentStatus"
                        wire:loading.attr="disabled"
                        wire:target="refreshPaymentStatus"
                        class="px-16 py-7 rounded-[2.5rem] border-2 border-zinc-800 bg-zinc-950/40 text-zinc-600 text-[11px] font-black uppercase tracking-[0.5em] hover:border-zinc-700 hover:text-white transition-all active:scale-95 disabled:opacity-50 italic">
                    <span wire:loading.remove wire:target="refreshPaymentStatus">Synchronize Node</span>
                    <span wire:loading wire:target="refreshPaymentStatus">Syncing...</span>
                </button>
            </div>
        @endif
    </div>
</div>
