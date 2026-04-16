<x-guest-layout title="{{ __('Account Suspended') }}">
    <div class="min-h-screen bg-zinc-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden font-mono">
        <!-- Background Glows -->
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-rose-500/10 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[50%] h-[50%] bg-rose-500/5 rounded-full blur-[150px] animate-pulse" style="animation-delay: 1s"></div>

        <div class="sm:mx-auto sm:w-full sm:max-w-2xl relative z-10 px-6 text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-zinc-900 border-2 border-rose-500/30 mb-8 shadow-[0_0_50px_rgba(244,63,94,0.2)] animate-pulse">
                <svg class="w-12 h-12 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.72 0z" />
                </svg>
            </div>

            <h2 class="text-5xl font-black text-white uppercase tracking-tighter italic">{{ __('Account Suspended') }}</h2>
            <div class="flex justify-center mt-6">
                <div class="h-1 w-32 bg-gradient-to-r from-transparent via-rose-500 to-transparent"></div>
            </div>
            
            <p class="mt-8 text-[11px] font-black text-rose-500/70 uppercase tracking-[0.5em] italic leading-relaxed">
                {{ __('Your account has been temporarily suspended.') }}
            </p>
        </div>

        @php
            $suspendedUntil = session('suspended_until');
            $suspensionReason = session('suspension_reason');
        @endphp

        <div class="sm:mx-auto sm:w-full sm:max-w-2xl mt-12 relative z-10 px-4">
            <div class="bg-zinc-900/40 border-2 border-rose-500/20 rounded-[3rem] p-10 sm:p-14 shadow-[0_50px_100px_rgba(0,0,0,0.8)] backdrop-blur-3xl space-y-12">
                
                {{-- Suspension details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-zinc-950 border border-zinc-800 rounded-3xl p-8 space-y-4">
                        <p class="text-[9px] font-black uppercase tracking-[0.4em] text-zinc-600 italic">{{ __('Status') }}</p>
                        <div class="flex items-center gap-4">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                            <span class="text-white text-xl font-black uppercase italic tracking-tighter">{{ __('Suspension Active') }}</span>
                        </div>
                    </div>
                    <div class="bg-zinc-950 border border-zinc-800 rounded-3xl p-8 space-y-4">
                        <p class="text-[9px] font-black uppercase tracking-[0.4em] text-zinc-600 italic">{{ __('Suspension Type') }}</p>
                        <span class="text-white text-xl font-black uppercase italic tracking-tighter block truncate">
                             {{ $suspendedUntil ? __('Temporary') : __('Permanent') }}
                        </span>
                    </div>
                </div>

                <div class="bg-zinc-950 border border-rose-500/10 rounded-[2.5rem] p-10 space-y-6">
                    <h4 class="text-[10px] font-black text-rose-500 uppercase tracking-[0.4em] italic leading-none">{{ __('Suspension Details') }}</h4>
                    
                    <div class="space-y-6">
                        @if($suspendedUntil)
                            <p class="text-zinc-400 text-sm italic font-bold leading-relaxed">
                                {{ __('Access review scheduled for:') }} <br>
                                <span class="text-white text-lg font-black mt-2 block uppercase tracking-tighter">
                                    {{ \Illuminate\Support\Carbon::parse($suspendedUntil)->toDayDateTimeString() }}
                                </span>
                            </p>
                        @else
                            <p class="text-zinc-400 text-sm italic font-bold leading-relaxed">
                                {{ __('This suspension has no expiration date. Your account will remain suspended until an administrator reviews it.') }}
                            </p>
                        @endif

                        @if(!empty($suspensionReason))
                            <div class="pt-6 border-t border-zinc-800">
                                <p class="text-[9px] font-black text-zinc-700 uppercase tracking-[0.4em] italic mb-4">{{ __('Admin Note:') }}</p>
                                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 italic text-sm text-rose-500/70 font-bold leading-loose">
                                    "{{ $suspensionReason }}"
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Action Protocol --}}
                <div class="space-y-6">
                    <div class="bg-zinc-950/40 p-10 rounded-[2.5rem] border border-zinc-800">
                        <h4 class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em] italic mb-6">{{ __('What you can do') }}</h4>
                        <ul class="space-y-4">
                             <li class="flex items-start gap-4 text-[11px] text-zinc-500 italic font-bold">
                                <span class="text-rose-500 font-black tracking-widest">[ERROR]</span>
                                <span>{{ __('Contact support if you believe this suspension was made by mistake.') }}</span>
                             </li>
                             <li class="flex items-start gap-4 text-[11px] text-zinc-500 italic font-bold">
                                <span class="text-rose-500 font-black tracking-widest">[RESTORE]</span>
                                <span>{{ __('Try signing in again after the review date, or wait for administrator approval.') }}</span>
                             </li>
                        </ul>
                    </div>

                    <div class="flex flex-col gap-4">
                        <a href="{{ route('login') }}" class="w-full py-6 bg-rose-500 text-black text-[11px] font-black uppercase tracking-[0.5em] rounded-2xl hover:bg-rose-400 shadow-xl shadow-rose-500/10 transition-all active:scale-95 text-center italic font-bold">
                             {{ __('Back to Login') }}
                        </a>
                        <p class="text-[8px] font-black text-zinc-800 uppercase tracking-[0.5em] text-center italic mt-2">
                             {{ __('Access denied') }} #{{ rand(1000, 9999) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
