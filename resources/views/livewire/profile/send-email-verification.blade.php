<div class="inline-block" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">
    @if ($user && !$user->email_verified_at)
        <div 
            class="flex flex-col gap-4"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <button 
                type="button" 
                wire:click="sendEmailVerification"
                wire:loading.attr="disabled"
                class="group relative inline-flex items-center gap-3 px-8 py-4 bg-zinc-950 border border-amber-500/30 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] text-amber-500 hover:bg-amber-500 hover:text-black transition-all duration-500 shadow-xl shadow-amber-500/5 active:scale-95 disabled:opacity-50 italic"
            >
                <div class="absolute inset-0 bg-amber-500 opacity-0 group-hover:opacity-10 blur-xl transition-opacity"></div>
                
                <svg wire:loading.remove wire:target="sendEmailVerification" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>

                <svg wire:loading wire:target="sendEmailVerification" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <span wire:loading.remove wire:target="sendEmailVerification" class="relative z-10">{{ __('Initialize Verification Link') }}</span>
                <span wire:loading wire:target="sendEmailVerification" class="relative z-10">{{ __('Sending...') }}</span>
            </button>

            @if ($verificationLinkSent)
                <div class="flex items-center gap-3 px-6 py-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                >
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest italic">
                        {{ __('Email sent successfully. Please check your inbox.') }}
                    </p>
                </div>
            @endif

            @if (session('verification-error'))
                <div class="flex items-center gap-3 px-6 py-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                >
                    <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                    <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest italic">
                        {{ session('verification-error') }}
                    </p>
                </div>
            @endif
        </div>
    @endif
</div>
