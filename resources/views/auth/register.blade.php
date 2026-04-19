<x-guest-layout title="{{ __('Create Account') }}">
    <div class="min-h-screen flex flex-col justify-center bg-zinc-50 py-12 sm:px-6 lg:px-8 relative overflow-hidden dark:bg-zinc-950">
        <!-- Background Glows -->
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-emerald-500/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-cyan-500/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>

        <div class="sm:mx-auto sm:w-full sm:max-w-xl relative z-10 px-6 text-center mb-12">
            <div class="flex justify-center mb-10">
                <div class="w-16 h-16 rounded-2xl border border-zinc-200 bg-zinc-100 flex items-center justify-center p-3 shadow-2xl group transition-all duration-700 hover:border-emerald-500/40 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-emerald-500/30">
                    <x-authentication-card-logo class="w-full h-full text-emerald-500 group-hover:scale-110 transition-transform" />
                </div>
            </div>
            <h2 class="text-4xl font-black uppercase tracking-tighter italic text-zinc-900 dark:text-white">{{ __('Create Account') }}</h2>
            <p class="mt-4 text-[10px] font-black uppercase tracking-[0.4em] italic leading-relaxed text-zinc-500 dark:text-zinc-600">{{ __('Join Career Hub and start building your profile') }}</p>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-xl relative z-10 px-4">
            <div class="rounded-[3rem] border border-zinc-200 bg-white/90 p-10 shadow-xl shadow-zinc-900/5 backdrop-blur-3xl sm:p-14 dark:border-zinc-800 dark:bg-zinc-900/40 dark:shadow-[0_50px_100px_rgba(0,0,0,0.5)]">
                <x-validation-errors class="mb-10 px-6 py-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-xs font-bold" />

                <form method="POST" action="{{ route('register') }}" class="space-y-10">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label for="name" class="block text-[9px] font-black uppercase tracking-[0.4em] italic ml-4 text-zinc-500 dark:text-zinc-600">{{ __('Full Name') }}</label>
                            <input id="name" class="w-full rounded-2xl border border-zinc-200 bg-white px-8 py-5 font-bold italic text-zinc-900 placeholder-zinc-400 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white dark:placeholder-zinc-800 dark:focus:ring-emerald-500/20" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="{{ __('Your full name') }}" />
                        </div>

                        <div class="space-y-3">
                            <label for="email" class="block text-[9px] font-black uppercase tracking-[0.4em] italic ml-4 text-zinc-500 dark:text-zinc-600">{{ __('Email Address') }}</label>
                            <input id="email" class="w-full rounded-2xl border border-zinc-200 bg-white px-8 py-5 font-bold italic text-zinc-900 placeholder-zinc-400 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white dark:placeholder-zinc-800 dark:focus:ring-emerald-500/20" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="{{ __('name@example.com') }}" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label for="username" class="block text-[9px] font-black uppercase tracking-[0.4em] italic ml-4 text-zinc-500 dark:text-zinc-600">{{ __('Username (Optional)') }}</label>
                            <input id="username" class="w-full rounded-2xl border border-zinc-200 bg-white px-8 py-5 font-bold italic text-zinc-900 placeholder-zinc-400 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white dark:placeholder-zinc-800 dark:focus:ring-emerald-500/20" type="text" name="username" :value="old('username')" autocomplete="username" placeholder="{{ __('username') }}" />
                        </div>

                        <div class="space-y-3">
                            <label for="role" class="block text-[9px] font-black uppercase tracking-[0.4em] italic ml-4 text-zinc-500 dark:text-zinc-600">{{ __('Role') }}</label>
                            <select id="role" name="role" class="w-full appearance-none rounded-2xl border border-zinc-200 bg-white px-8 py-5 font-bold italic text-zinc-900 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white dark:focus:ring-emerald-500/20" required>
                                <option value="" class="italic text-zinc-500 dark:bg-zinc-950 dark:text-zinc-700">{{ __('Select Role') }}</option>
                                <option value="seeker" {{ old('role') == 'seeker' ? 'selected' : '' }} class="italic dark:bg-zinc-950 dark:text-white">{{ __('Seeker') }}</option>
                                <option value="company" {{ old('role') == 'company' ? 'selected' : '' }} class="italic dark:bg-zinc-950 dark:text-white">{{ __('Company') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label for="password" class="block text-[9px] font-black uppercase tracking-[0.4em] italic ml-4 text-zinc-500 dark:text-zinc-600">{{ __('Password') }}</label>
                            <input id="password" class="w-full rounded-2xl border border-zinc-200 bg-white px-8 py-5 font-bold italic text-zinc-900 placeholder-zinc-400 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white dark:placeholder-zinc-800 dark:focus:ring-emerald-500/20" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                        </div>

                        <div class="space-y-3">
                            <label for="password_confirmation" class="block text-[9px] font-black uppercase tracking-[0.4em] italic ml-4 text-zinc-500 dark:text-zinc-600">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" class="w-full rounded-2xl border border-zinc-200 bg-white px-8 py-5 font-bold italic text-zinc-900 placeholder-zinc-400 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white dark:placeholder-zinc-800 dark:focus:ring-emerald-500/20" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                        </div>
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="px-4">
                            <label for="terms" class="flex items-start group cursor-pointer">
                                <input id="terms" name="terms" type="checkbox" required class="mt-1 h-4 w-4 cursor-pointer rounded border-zinc-300 bg-white text-emerald-500 focus:ring-emerald-500 dark:border-zinc-800 dark:bg-zinc-950">
                                <span class="ms-4 text-[9px] font-black uppercase tracking-widest italic leading-relaxed transition-colors text-zinc-600 group-hover:text-zinc-800 dark:text-zinc-600 dark:group-hover:text-zinc-400">
                                    {!! __('I agree to the :terms and :privacy', [
                                        'terms' => '<a target="_blank" href="' . route('terms.show') . '" class="underline decoration-zinc-800 hover:decoration-emerald-500/30">' . __('Terms of Service') . '</a>',
                                        'privacy' => '<a target="_blank" href="' . route('policy.show') . '" class="underline decoration-zinc-800 hover:decoration-emerald-500/30">' . __('Privacy Policy') . '</a>'
                                    ]) !!}
                                </span>
                            </label>
                        </div>
                    @endif

                    <div class="space-y-6 pt-6">
                        <button class="w-full py-7 bg-emerald-500 text-black text-[11px] font-black uppercase tracking-[0.5em] rounded-2xl hover:bg-emerald-400 shadow-xl shadow-emerald-500/10 transition-all active:scale-95 italic font-bold">
                            {{ __('Create Account') }}
                        </button>

                        @if (config('services.github.client_id') && config('services.github.client_secret'))
                            <div class="relative py-4">
                                <div class="absolute inset-x-0 top-1/2 h-px bg-zinc-200 dark:bg-zinc-800"></div>
                                <div class="relative flex justify-center"><span class="bg-white px-6 text-[9px] font-black uppercase tracking-[0.4em] italic text-zinc-500 dark:bg-zinc-900 dark:text-zinc-700">{{ __('Other Sign Up Options') }}</span></div>
                            </div>
                            <livewire:auth.github-login />
                        @endif
                    </div>

                    <div class="mt-10 text-center">
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] italic text-zinc-600 dark:text-zinc-700">
                            {{ __('Already Have an Account?') }}
                            <a href="{{ route('login') }}" class="underline decoration-zinc-300 underline-offset-8 transition-all hover:text-emerald-600 hover:decoration-emerald-500/40 dark:text-zinc-400 dark:decoration-zinc-800 dark:hover:text-emerald-400 dark:hover:decoration-emerald-500/30">
                                {{ __('Sign In') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
