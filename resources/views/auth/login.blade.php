<x-guest-layout title="{{ __('Sign In') }}">
    <div class="min-h-screen bg-zinc-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Background Glows -->
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-emerald-500/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-cyan-500/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
            <div class="flex justify-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-zinc-900 border border-zinc-800 flex items-center justify-center p-3 shadow-2xl group transition-all duration-700 hover:border-emerald-500/30">
                    <x-authentication-card-logo class="w-full h-full text-emerald-500 group-hover:scale-110 transition-transform" />
                </div>
            </div>
            
            <div class="text-center mb-10 px-6">
                <h2 class="text-4xl font-black text-white uppercase tracking-tighter italic">Initialize <span class="text-emerald-500">Uplink</span></h2>
                <p class="mt-3 text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em] italic leading-relaxed">Establish secure terminal connection to central intelligence</p>
            </div>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4">
            <div class="bg-zinc-900/40 border border-zinc-800 rounded-[3rem] p-10 sm:p-12 shadow-[0_50px_100px_rgba(0,0,0,0.5)] backdrop-blur-3xl">
                <x-validation-errors class="mb-6 px-4 py-3 bg-rose-500/10 border border-rose-500/20 rounded-xl text-xs" />

                @session('status')
                    <div class="mb-8 p-6 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl">
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest italic leading-relaxed">
                            {{ $value }}
                        </p>
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}" class="space-y-8">
                    @csrf

                    <div class="space-y-3">
                        <label for="email" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic ml-4">Identifier (Email)</label>
                        <input id="email" class="w-full px-8 py-5 bg-zinc-950 border border-zinc-800 rounded-2xl text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all font-bold italic" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="NAME@DOMAIN.COM" />
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between px-4">
                             <label for="password" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic">Access Key</label>
                             @if (Route::has('password.request'))
                                <a class="text-[8px] font-black text-zinc-700 uppercase tracking-widest hover:text-emerald-500 transition-colors italic" href="{{ route('password.request') }}">
                                    Recover Key
                                </a>
                            @endif
                        </div>
                        <input id="password" class="w-full px-8 py-5 bg-zinc-950 border border-zinc-800 rounded-2xl text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all font-bold italic" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                    </div>

                    <div class="flex items-center justify-between px-4">
                        <label for="remember_me" class="flex items-center group cursor-pointer">
                            <input id="remember_me" name="remember" type="checkbox" class="w-4 h-4 bg-zinc-950 border-zinc-800 rounded focus:ring-emerald-500 text-emerald-500 cursor-pointer">
                            <span class="ms-3 text-[9px] font-black text-zinc-600 group-hover:text-zinc-400 uppercase tracking-widest transition-colors italic">Persistent Session</span>
                        </label>
                    </div>

                    <div class="space-y-4 pt-4">
                        <button class="w-full py-6 bg-emerald-500 text-black text-[11px] font-black uppercase tracking-[0.5em] rounded-2xl hover:bg-emerald-400 shadow-xl shadow-emerald-500/10 transition-all active:scale-95 italic">
                            Commit Connection
                        </button>

                        @if (config('services.github.client_id') && config('services.github.client_secret'))
                            <div class="relative py-4">
                                <div class="absolute inset-x-0 top-1/2 h-px bg-zinc-800"></div>
                                <div class="relative flex justify-center"><span class="bg-zinc-900 px-4 text-[9px] font-black text-zinc-700 uppercase tracking-[0.4em] italic">Other sign in options</span></div>
                            </div>
                            <livewire:auth.github-login />
                        @endif
                    </div>

                    <div class="mt-10 text-center">
                        <p class="text-[10px] font-black text-zinc-700 uppercase tracking-[0.4em] italic">
                            {{ __('No Operational Profile?') }}
                            <a href="{{ route('register') }}" class="text-zinc-400 hover:text-emerald-400 underline decoration-zinc-800 underline-offset-8 transition-all hover:decoration-emerald-500/30">
                                {{ __('Initialize Registration') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
