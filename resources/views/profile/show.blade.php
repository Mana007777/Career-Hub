<x-app-layout title="{{ __('Profile') }}">
    <div
        class="profile-node-shell min-h-screen bg-transparent text-white pb-24 font-sans"
        style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%);"
        x-data="{ loaded: false }"
        x-init="setTimeout(() => loaded = true, 50)"
    >
        <div class="max-w-4xl mx-auto px-6 py-12" x-show="loaded" x-cloak>
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
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] italic">{{ __('Return to Command Center') }}</span>
                </a>
            </div>

            @if (session()->has('success'))
                <div class="mb-10 p-6 bg-emerald-500/10 border border-emerald-500/20 rounded-3xl text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] backdrop-blur-3xl flex items-center gap-4">
                    <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center border border-emerald-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-10 p-6 bg-rose-500/10 border border-rose-500/20 rounded-3xl text-rose-400 text-[10px] font-black uppercase tracking-[0.3em] backdrop-blur-3xl flex items-center gap-4">
                    <div class="w-10 h-10 bg-rose-500/10 rounded-xl flex items-center justify-center border border-rose-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div
                class="mb-16 text-center"
                x-show="loaded"
                x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-1000"
                x-transition:enter-start="opacity-0 translate-y-10"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                <div class="flex items-center gap-6 mb-6">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent"></div>
                    <h2 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.6em] italic">{{ __('Personnel Registry') }}</h2>
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent"></div>
                </div>
                <h1 class="text-4xl sm:text-5xl font-black text-white uppercase tracking-tighter italic">
                    {{ __('Node') }} <span class="text-emerald-500">{{ __('Profile') }}</span>
                </h1>
                <p class="text-zinc-500 text-sm mt-6 uppercase tracking-[0.2em] italic font-medium">{{ __('Account details and security protocols.') }}</p>
            </div>

            <!-- Snapshot card -->
            <div class="group relative bg-zinc-950/60 border border-zinc-800/50 rounded-[3rem] p-8 sm:p-10 mb-10 backdrop-blur-3xl shadow-[0_30px_60px_rgba(0,0,0,0.3)]">
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/10 to-transparent"></div>
                <h3 class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.4em] italic mb-8">{{ __('Current snapshot') }}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="flex items-start gap-6">
                        <div class="w-24 h-24 rounded-[1.5rem] overflow-hidden bg-zinc-900 border-2 border-zinc-800 shrink-0">
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover grayscale opacity-80 hover:grayscale-0 hover:opacity-100 transition-all duration-700">
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xl font-black text-white uppercase tracking-tight italic truncate">{{ auth()->user()->name }}</h4>
                            <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mt-2">{{ '@' . auth()->user()->username }}</p>
                            @if(auth()->user()->role)
                                <span class="inline-flex mt-3 px-3 py-1 text-[9px] font-black uppercase tracking-[0.25em] rounded-xl border
                                    {{ auth()->user()->role === 'seeker'
                                        ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400'
                                        : 'bg-purple-500/10 border-purple-500/20 text-purple-300' }}">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-5 text-sm">
                        <div>
                            <span class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.3em]">{{ __('Email') }}</span>
                            <p class="mt-1 text-zinc-200 font-medium break-all">{{ auth()->user()->email }}</p>
                            @if(auth()->user()->email_verified_at)
                                <span class="inline-flex items-center mt-2 text-[9px] font-black uppercase tracking-widest text-emerald-500/80">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('Verified') }}
                                </span>
                            @else
                                <div class="mt-3">
                                    @livewire('profile.send-email-verification')
                                </div>
                            @endif
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.3em]">{{ __('Deployed') }}</span>
                            <p class="mt-1 text-zinc-300">{{ auth()->user()->created_at->format('F d, Y') }}</p>
                            <p class="text-[10px] text-zinc-600 mt-0.5">{{ auth()->user()->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->profile)
                    <div class="mt-10 pt-10 border-t border-zinc-800/50">
                        <h4 class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.4em] italic mb-6">{{ __('Additional telemetry') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @if(auth()->user()->profile->bio)
                                <div>
                                    <span class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.3em]">{{ __('Bio') }}</span>
                                    <p class="mt-2 text-sm text-zinc-300 leading-relaxed border-l-2 border-emerald-500/20 pl-4">{{ auth()->user()->profile->bio }}</p>
                                </div>
                            @endif
                            @if(auth()->user()->profile->location)
                                <div>
                                    <span class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.3em]">{{ __('Location') }}</span>
                                    <p class="mt-2 text-zinc-300 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500/50 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ auth()->user()->profile->location }}
                                    </p>
                                </div>
                            @endif
                            @if(auth()->user()->profile->website)
                                @php
                                    $w = auth()->user()->profile->website;
                                    $wUrl = preg_match('~^[a-zA-Z]+://~', $w) ? $w : 'https://' . $w;
                                @endphp
                                <div class="md:col-span-2">
                                    <span class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.3em]">{{ __('Website') }}</span>
                                    <p class="mt-2">
                                        <a href="{{ $wUrl }}" target="_blank" rel="noopener noreferrer" class="text-emerald-500/80 hover:text-emerald-400 text-sm font-medium break-all underline decoration-emerald-500/20 underline-offset-4">
                                            {{ $w }}
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if(auth()->user()->isAdmin())
                    <div class="mt-8 pt-8 border-t border-zinc-800/50">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-rose-500/10 border border-rose-500/25 text-rose-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('Administrator') }}
                        </span>
                    </div>
                @endif
            </div>

            @if (session('logged_in_via_github'))
                <div class="mb-10 p-6 rounded-[2rem] border border-amber-500/20 bg-amber-500/5 backdrop-blur-xl">
                    <div class="text-[10px] font-black text-amber-400 uppercase tracking-[0.25em] mb-2">{{ __('You signed in with GitHub') }}</div>
                    <p class="text-sm text-amber-200/90 leading-relaxed">
                        {{ __('Because you used GitHub to log in, you might not know your account password yet. To use features that ask for your password (like enabling two-factor authentication, logging out other browser sessions, or deleting your account), first go to the login page, click “Forgot password?”, enter this account’s email address, and complete the reset flow to create a password.') }}
                    </p>
                </div>
            @endif

            <div class="profile-forms space-y-10">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    @livewire('profile.update-profile-information-form', ['key' => 'update-profile-information-form'])
                    <div class="h-px bg-zinc-800/50" aria-hidden="true"></div>
                @endif

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    @livewire('profile.update-password-form')
                    <div class="h-px bg-zinc-800/50" aria-hidden="true"></div>
                @endif

                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    @livewire('profile.two-factor-authentication-form')
                    <div class="h-px bg-zinc-800/50" aria-hidden="true"></div>
                @endif

                @livewire('profile.logout-other-browser-sessions-form')

                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div class="h-px bg-zinc-800/50" aria-hidden="true"></div>
                    @livewire('profile.delete-user-form')
                @endif
            </div>
        </div>

        <style>
            .profile-node-shell .profile-forms .md\:grid {
                gap: 1.5rem;
            }
            .profile-node-shell .profile-forms .shadow,
            .profile-node-shell .profile-forms .sm\:rounded-md,
            .profile-node-shell .profile-forms .sm\:rounded-tl-md,
            .profile-node-shell .profile-forms .sm\:rounded-tr-md,
            .profile-node-shell .profile-forms .sm\:rounded-bl-md,
            .profile-node-shell .profile-forms .sm\:rounded-br-md {
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45) !important;
            }
            .profile-node-shell .profile-forms .bg-white,
            .profile-node-shell .profile-forms .dark\:bg-gray-900 {
                background-color: rgb(24 24 27 / 0.65) !important;
                border-color: rgb(39 39 42 / 0.55) !important;
            }
            .profile-node-shell .profile-forms .border-gray-200,
            .profile-node-shell .profile-forms .dark\:border-gray-800 {
                border-color: rgb(39 39 42 / 0.55) !important;
            }
            .profile-node-shell .profile-forms .text-gray-900,
            .profile-node-shell .profile-forms .dark\:text-white,
            .profile-node-shell .profile-forms .text-gray-700 {
                color: rgb(244 244 245) !important;
            }
            .profile-node-shell .profile-forms .text-gray-500,
            .profile-node-shell .profile-forms .dark\:text-gray-400,
            .profile-node-shell .profile-forms .text-gray-600 {
                color: rgb(161 161 170) !important;
            }
            .profile-node-shell .profile-forms input,
            .profile-node-shell .profile-forms select,
            .profile-node-shell .profile-forms textarea {
                background-color: rgb(9 9 11 / 0.85) !important;
                border-color: rgb(63 63 70 / 0.6) !important;
                color: rgb(244 244 245) !important;
            }
            .profile-node-shell .profile-forms .border-t.border-gray-200,
            .profile-node-shell .profile-forms .dark\:border-gray-800.border-t {
                border-color: rgb(39 39 42 / 0.55) !important;
            }
        </style>
    </div>
</x-app-layout>
