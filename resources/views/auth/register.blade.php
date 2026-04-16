<x-guest-layout title="{{ __('Create Account') }}">
    <div class="min-h-screen bg-zinc-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Background Glows -->
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-emerald-500/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-cyan-500/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>

        <div class="sm:mx-auto sm:w-full sm:max-w-xl relative z-10 px-6 text-center mb-12">
            <div class="flex justify-center mb-10">
                <div class="w-16 h-16 rounded-2xl bg-zinc-900 border border-zinc-800 flex items-center justify-center p-3 shadow-2xl group transition-all duration-700 hover:border-emerald-500/30">
                    <x-authentication-card-logo class="w-full h-full text-emerald-500 group-hover:scale-110 transition-transform" />
                </div>
            </div>
            <h2 class="text-4xl font-black text-white uppercase tracking-tighter italic">{{ __('Create Account') }}</h2>
            <p class="mt-4 text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em] italic leading-relaxed">{{ __('Join Career Hub and start building your profile') }}</p>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-xl relative z-10 px-4">
            <div class="bg-zinc-900/40 border border-zinc-800 rounded-[3rem] p-10 sm:p-14 shadow-[0_50px_100px_rgba(0,0,0,0.5)] backdrop-blur-3xl">
                <x-validation-errors class="mb-10 px-6 py-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-xs font-bold" />

                <form method="POST" action="{{ route('register') }}" class="space-y-10">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label for="name" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic ml-4">{{ __('Full Name') }}</label>
                            <input id="name" class="w-full px-8 py-5 bg-zinc-950 border border-zinc-800 rounded-2xl text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all font-bold italic" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="JOHN DOE" />
                        </div>

                        <div class="space-y-3">
                            <label for="email" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic ml-4">{{ __('Email Address') }}</label>
                            <input id="email" class="w-full px-8 py-5 bg-zinc-950 border border-zinc-800 rounded-2xl text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all font-bold italic" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="NAME@DOMAIN.COM" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label for="username" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic ml-4">{{ __('Username (Optional)') }}</label>
                            <input id="username" class="w-full px-8 py-5 bg-zinc-950 border border-zinc-800 rounded-2xl text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all font-bold italic" type="text" name="username" :value="old('username')" autocomplete="username" placeholder="OPERATIVE_X" />
                        </div>

                        <div class="space-y-3">
                            <label for="role" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic ml-4">{{ __('Role') }}</label>
                            <select id="role" name="role" class="w-full px-8 py-5 bg-zinc-950 border border-zinc-800 rounded-2xl text-white appearance-none focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all font-bold italic" required>
                                <option value="" class="bg-zinc-950 text-zinc-700 italic">{{ __('Select Role') }}</option>
                                <option value="seeker" {{ old('role') == 'seeker' ? 'selected' : '' }} class="bg-zinc-950 text-white italic">{{ __('Seeker') }}</option>
                                <option value="company" {{ old('role') == 'company' ? 'selected' : '' }} class="bg-zinc-950 text-white italic">{{ __('Company') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label for="password" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic ml-4">{{ __('Password') }}</label>
                            <input id="password" class="w-full px-8 py-5 bg-zinc-950 border border-zinc-800 rounded-2xl text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all font-bold italic" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                        </div>

                        <div class="space-y-3">
                            <label for="password_confirmation" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic ml-4">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" class="w-full px-8 py-5 bg-zinc-950 border border-zinc-800 rounded-2xl text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all font-bold italic" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                        </div>
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="px-4">
                            <label for="terms" class="flex items-start group cursor-pointer">
                                <input id="terms" name="terms" type="checkbox" required class="mt-1 w-4 h-4 bg-zinc-950 border-zinc-800 rounded focus:ring-emerald-500 text-emerald-500 cursor-pointer">
                                <span class="ms-4 text-[9px] font-black text-zinc-600 group-hover:text-zinc-400 uppercase tracking-widest transition-colors italic leading-relaxed">
                                    I acknowledge the <a target="_blank" href="{{ route('terms.show') }}" class="underline decoration-zinc-800 hover:decoration-emerald-500/30">Terms of Service</a> and <a target="_blank" href="{{ route('policy.show') }}" class="underline decoration-zinc-800 hover:decoration-emerald-500/30">Privacy Policy</a>
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
                                <div class="absolute inset-x-0 top-1/2 h-px bg-zinc-800"></div>
                                <div class="relative flex justify-center"><span class="bg-zinc-900 px-6 text-[9px] font-black text-zinc-700 uppercase tracking-[0.4em] italic">{{ __('Other Sign Up Options') }}</span></div>
                            </div>
                            <livewire:auth.github-login />
                        @endif
                    </div>

                    <div class="mt-10 text-center">
                        <p class="text-[10px] font-black text-zinc-700 uppercase tracking-[0.4em] italic">
                            {{ __('Already Have an Account?') }}
                            <a href="{{ route('login') }}" class="text-zinc-400 hover:text-emerald-400 underline decoration-zinc-800 underline-offset-8 transition-all hover:decoration-emerald-500/30">
                                {{ __('Sign In') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
