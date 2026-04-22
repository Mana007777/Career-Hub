<x-guest-layout title="{{ __('Sign In') }}">
    <div class="min-h-screen flex flex-col justify-center bg-zinc-50 py-12 sm:px-6 lg:px-8 relative overflow-hidden dark:bg-zinc-950">
        <!-- Background Glows -->
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-emerald-500/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-cyan-500/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
            <div class="flex justify-center mb-8">
                <div class="w-16 h-16 rounded-2xl border border-zinc-200 bg-zinc-100 flex items-center justify-center p-3 shadow-2xl group transition-all duration-700 hover:border-emerald-500/40 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-emerald-500/30">
                    <x-authentication-card-logo class="w-full h-full text-emerald-500 group-hover:scale-110 transition-transform" />
                </div>
            </div>
            
            <div class="text-center mb-10 px-6">
                <h2 class="text-4xl font-black uppercase tracking-tighter italic text-zinc-900 dark:text-white">{{ __('Sign In') }}</h2>
                <p class="mt-3 text-[10px] font-black uppercase tracking-[0.4em] italic leading-relaxed text-zinc-500 dark:text-zinc-600">{{ __('Welcome back to Career Hub') }}</p>
            </div>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4">
            <div class="rounded-[3rem] border border-zinc-200 bg-white/90 p-10 shadow-xl shadow-zinc-900/5 backdrop-blur-3xl sm:p-12 dark:border-zinc-800 dark:bg-zinc-900/40 dark:shadow-[0_50px_100px_rgba(0,0,0,0.5)]">
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
                        <label for="email" class="block text-[9px] font-black uppercase tracking-[0.4em] italic ml-4 text-zinc-500 dark:text-zinc-600">{{ __('Email Address') }}</label>
                        <input id="email" class="w-full rounded-2xl border border-zinc-200 bg-white px-8 py-5 font-bold italic text-zinc-900 placeholder-zinc-400 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white dark:placeholder-zinc-800 dark:focus:ring-emerald-500/20" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="{{ __('name@example.com') }}" />
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between px-4">
                             <label for="password" class="block text-[9px] font-black uppercase tracking-[0.4em] italic text-zinc-500 dark:text-zinc-600">{{ __('Password') }}</label>
                             @if (Route::has('password.request'))
                                <a class="text-[8px] font-black uppercase tracking-widest italic transition-colors text-zinc-500 hover:text-emerald-600 dark:text-zinc-700 dark:hover:text-emerald-500" href="{{ route('password.request') }}">
                                    {{ __('Forgot Password?') }}
                                </a>
                            @endif
                        </div>
                        <input id="password" class="w-full rounded-2xl border border-zinc-200 bg-white px-8 py-5 font-bold italic text-zinc-900 placeholder-zinc-400 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white dark:placeholder-zinc-800 dark:focus:ring-emerald-500/20" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                    </div>

                    <div class="flex items-center justify-between px-4">
                        <label for="remember_me" class="flex items-center group cursor-pointer">
                            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 cursor-pointer rounded border-zinc-300 bg-white text-emerald-500 focus:ring-emerald-500 dark:border-zinc-800 dark:bg-zinc-950">
                            <span class="ms-3 text-[9px] font-black uppercase tracking-widest italic transition-colors text-zinc-500 group-hover:text-zinc-700 dark:text-zinc-600 dark:group-hover:text-zinc-400">{{ __('Remember Me') }}</span>
                        </label>
                    </div>

                    <div class="space-y-4 pt-4">
                        <button class="w-full py-6 bg-emerald-500 text-black text-[11px] font-black uppercase tracking-[0.5em] rounded-2xl hover:bg-emerald-400 shadow-xl shadow-emerald-500/10 transition-all active:scale-95 italic">
                            {{ __('Sign In') }}
                        </button>

                        @if ((config('services.github.client_id') && config('services.github.client_secret')) || (config('services.google.client_id') && config('services.google.client_secret')))
                            <div class="relative py-4">
                                <div class="absolute inset-x-0 top-1/2 h-px bg-zinc-200 dark:bg-zinc-800"></div>
                                <div class="relative flex justify-center"><span class="bg-white px-4 text-[9px] font-black uppercase tracking-[0.4em] italic text-zinc-500 dark:bg-zinc-900 dark:text-zinc-700">{{ __('Other Sign In Options') }}</span></div>
                            </div>
                            @if (config('services.github.client_id') && config('services.github.client_secret'))
                                <livewire:auth.github-login />
                            @endif
                            @if (config('services.google.client_id') && config('services.google.client_secret'))
                                <livewire:auth.google-login />
                            @endif
                        @endif
                    </div>

                    <div class="mt-10 text-center">
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] italic text-zinc-600 dark:text-zinc-700">
                            {{ __('Don\'t Have an Account?') }}
                            <a href="{{ route('register') }}" class="underline decoration-zinc-300 underline-offset-8 transition-all hover:text-emerald-600 hover:decoration-emerald-500/40 dark:text-zinc-400 dark:decoration-zinc-800 dark:hover:text-emerald-400 dark:hover:decoration-emerald-500/30">
                                {{ __('Create Account') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
