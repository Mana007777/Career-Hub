<x-app-layout title="{{ __('Profile') }}">
    <div
        class="profile-node-shell min-h-screen bg-transparent pb-24 font-sans text-zinc-900 dark:text-zinc-100"
        style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%);"
        x-data="{ loaded: false }"
        x-init="setTimeout(() => loaded = true, 50)"
    >
        <div class="mx-auto max-w-4xl px-6 py-12" x-show="loaded" x-cloak>
            <div
                class="mb-12"
                x-show="loaded"
                x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
                x-transition:enter-start="opacity-0 -translate-x-10"
                x-transition:enter-end="opacity-100 translate-x-0"
            >
                <a
                    href="{{ route('dashboard') }}"
                    class="group inline-flex items-center gap-4 text-emerald-600 transition-all duration-500 hover:text-emerald-500 dark:text-emerald-500/80 dark:hover:text-emerald-400"
                >
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-zinc-200 bg-zinc-100 shadow-sm transition-all duration-500 group-hover:border-emerald-500/40 group-hover:bg-emerald-500 group-hover:text-black dark:border-zinc-800 dark:bg-zinc-900 dark:group-hover:text-black">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] italic text-zinc-600 dark:text-zinc-400">{{ __('Back to Home') }}</span>
                </a>
            </div>

            @if (session()->has('success'))
                <div class="mb-10 flex items-center gap-4 rounded-3xl border border-emerald-500/25 bg-emerald-500/10 p-6 text-emerald-800 backdrop-blur-xl dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-emerald-500/30 bg-emerald-500/15 dark:border-emerald-500/20 dark:bg-emerald-500/10">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-10 flex items-center gap-4 rounded-3xl border border-rose-500/25 bg-rose-500/10 p-6 text-rose-800 backdrop-blur-xl dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-rose-500/30 bg-rose-500/15 dark:border-rose-500/20 dark:bg-rose-500/10">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <div
                class="mb-16 text-center"
                x-show="loaded"
                x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-1000"
                x-transition:enter-start="opacity-0 translate-y-10"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                <div class="mb-6 flex items-center gap-6">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-emerald-500/25 to-transparent dark:via-emerald-500/20"></div>
                    <h2 class="text-[10px] font-black uppercase tracking-[0.6em] text-emerald-600 dark:text-emerald-500 italic">{{ __('Personnel Registry') }}</h2>
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-emerald-500/25 to-transparent dark:via-emerald-500/20"></div>
                </div>
                <h1 class="text-4xl font-black uppercase tracking-tighter text-zinc-900 sm:text-5xl dark:text-white italic">
                    {{ __('User') }} <span class="text-emerald-600 dark:text-emerald-500">{{ __('Profile') }}</span>
                </h1>
                <p class="mt-6 text-sm font-medium uppercase tracking-[0.2em] text-zinc-600 italic dark:text-zinc-500">{{ __('Account details and security protocols.') }}</p>
            </div>

            <div class="group relative mb-10 rounded-[3rem] border border-zinc-200 bg-white/95 p-8 shadow-xl shadow-zinc-900/5 backdrop-blur-sm dark:border-zinc-800/50 dark:bg-zinc-950/60 dark:shadow-[0_30px_60px_rgba(0,0,0,0.3)] sm:p-10">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-emerald-500/15 to-transparent dark:via-emerald-500/10"></div>
                <h3 class="mb-8 text-[10px] font-black uppercase tracking-[0.4em] text-zinc-500 italic dark:text-zinc-500">{{ __('Current snapshot') }}</h3>

                <div class="grid grid-cols-1 gap-10 md:grid-cols-2">
                    <div class="flex items-start gap-6">
                        <div class="h-24 w-24 shrink-0 overflow-hidden rounded-[1.5rem] border-2 border-zinc-200 bg-zinc-100 dark:border-zinc-800 dark:bg-zinc-900">
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover grayscale opacity-90 transition-all duration-700 hover:grayscale-0 hover:opacity-100 dark:opacity-80">
                        </div>
                        <div class="min-w-0">
                            <h4 class="truncate text-xl font-black uppercase tracking-tight text-zinc-900 italic dark:text-white">{{ auth()->user()->name }}</h4>
                            <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-zinc-500 dark:text-zinc-500">{{ '@' . auth()->user()->username }}</p>
                            @if(auth()->user()->role)
                                <span class="mt-3 inline-flex rounded-xl border px-3 py-1 text-[9px] font-black uppercase tracking-[0.25em]
                                    {{ auth()->user()->role === 'seeker'
                                        ? 'border-emerald-500/25 bg-emerald-500/10 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400'
                                        : 'border-purple-500/25 bg-purple-500/10 text-purple-800 dark:border-purple-500/20 dark:bg-purple-500/10 dark:text-purple-300' }}">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-5 text-sm">
                        <div>
                            <span class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 dark:text-zinc-500">{{ __('Email') }}</span>
                            <p class="mt-1 break-all font-medium text-zinc-800 dark:text-zinc-200">{{ auth()->user()->email }}</p>
                            @if(auth()->user()->email_verified_at)
                                <span class="mt-2 inline-flex items-center text-[9px] font-black uppercase tracking-widest text-emerald-700 dark:text-emerald-400/90">
                                    <svg class="mr-1.5 h-3.5 w-3.5 text-emerald-600 dark:text-emerald-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
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
                            <span class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 dark:text-zinc-500">{{ __('Deployed') }}</span>
                            <p class="mt-1 text-zinc-800 dark:text-zinc-300">{{ auth()->user()->created_at->format('F d, Y') }}</p>
                            <p class="mt-0.5 text-[10px] text-zinc-500 dark:text-zinc-600">{{ \App\Support\SoraniTime::human(auth()->user()->created_at) }}</p>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->profile)
                    <div class="mt-10 border-t border-zinc-200 pt-10 dark:border-zinc-800/50">
                        <h4 class="mb-6 text-[10px] font-black uppercase tracking-[0.4em] text-zinc-500 italic dark:text-zinc-500">{{ __('Additional telemetry') }}</h4>
                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            @if(auth()->user()->profile->bio)
                                <div>
                                    <span class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 dark:text-zinc-500">{{ __('Bio') }}</span>
                                    <p class="mt-2 border-l-2 border-emerald-500/30 pl-4 text-sm leading-relaxed text-zinc-700 dark:border-emerald-500/20 dark:text-zinc-300">{{ auth()->user()->profile->bio }}</p>
                                </div>
                            @endif
                            @if(auth()->user()->profile->location)
                                <div>
                                    <span class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 dark:text-zinc-500">{{ __('Location') }}</span>
                                    <p class="mt-2 flex items-center gap-2 text-zinc-700 dark:text-zinc-300">
                                        <svg class="h-4 w-4 shrink-0 text-emerald-600/60 dark:text-emerald-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
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
                                    <span class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 dark:text-zinc-500">{{ __('Website') }}</span>
                                    <p class="mt-2">
                                        <a href="{{ $wUrl }}" target="_blank" rel="noopener noreferrer" class="break-all text-sm font-medium text-emerald-700 underline decoration-emerald-500/30 underline-offset-4 transition hover:text-emerald-600 dark:text-emerald-500/90 dark:hover:text-emerald-400">
                                            {{ $w }}
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if(auth()->user()->isAdmin())
                    <div class="mt-8 border-t border-zinc-200 pt-8 dark:border-zinc-800/50">
                        <span class="inline-flex items-center gap-2 rounded-xl border border-rose-500/25 bg-rose-500/10 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-rose-700 dark:text-rose-400">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('Administrator') }}
                        </span>
                    </div>
                @endif
            </div>

            @if (session('logged_in_via_github') || auth()->user()->skipsCurrentPasswordForUpdate())
                <div class="mb-10 rounded-[2rem] border border-amber-500/30 bg-amber-50/90 p-6 backdrop-blur-xl dark:border-amber-500/20 dark:bg-amber-500/10">
                    <div class="mb-2 text-[10px] font-black uppercase tracking-[0.25em] text-amber-800 dark:text-amber-400">{{ __('You signed in with GitHub') }}</div>
                    <p class="text-sm leading-relaxed text-amber-950/90 dark:text-amber-200/90">
                        {{ __('Your account did not get a password you chose yet. In “Update Password” below, enter only a new password and confirmation (no current password). After you save once, the usual current + new + confirm fields will appear for future changes.') }}
                    </p>
                </div>
            @endif

            <div class="profile-forms mx-auto w-full max-w-2xl space-y-10 lg:max-w-3xl">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    @livewire('profile.update-profile-information-form', ['key' => 'update-profile-information-form'])
                    <div class="h-px bg-zinc-200 dark:bg-zinc-800/50" aria-hidden="true"></div>
                @endif

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    @livewire('profile.update-password-form')
                    <div class="h-px bg-zinc-200 dark:bg-zinc-800/50" aria-hidden="true"></div>
                @endif

                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    @livewire('profile.two-factor-authentication-form')
                    <div class="h-px bg-zinc-200 dark:bg-zinc-800/50" aria-hidden="true"></div>
                @endif

                @livewire('profile.logout-other-browser-sessions-form')

                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div class="h-px bg-zinc-200 dark:bg-zinc-800/50" aria-hidden="true"></div>
                    @livewire('profile.delete-user-form')
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
