<x-guest-layout title="{{ __('Verify your email') }}">
    <div class="min-h-screen flex flex-col justify-center bg-zinc-50 py-12 sm:px-6 lg:px-8 relative overflow-hidden dark:bg-zinc-950">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-emerald-500/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-cyan-500/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>

        <div class="sm:mx-auto sm:w-full sm:max-w-xl relative z-10 px-4">
            <div class="rounded-[3rem] border border-zinc-200 bg-white/90 p-10 shadow-xl shadow-zinc-900/5 backdrop-blur-3xl sm:p-14 dark:border-zinc-800 dark:bg-zinc-900/40 dark:shadow-[0_50px_100px_rgba(0,0,0,0.5)]">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-black uppercase tracking-tighter italic text-zinc-900 dark:text-white">
                        {{ __('Verify Your Email') }}
                    </h1>
                    <p class="mt-4 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500 dark:text-zinc-600 leading-relaxed">
                        {{ __('We sent a verification link to your Gmail. Click it to activate your account, then you can continue using Career Hub.') }}
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-5 bg-emerald-500/10 border border-emerald-500/25 rounded-2xl text-emerald-700 dark:text-emerald-300 text-sm font-semibold">
                        {{ __('A new verification link has been sent successfully.') }}
                    </div>
                @endif

                @if (session('verification_code_sent'))
                    <div class="mb-6 p-5 bg-cyan-500/10 border border-cyan-500/25 rounded-2xl text-cyan-700 dark:text-cyan-300 text-sm font-semibold">
                        {{ session('verification_code_sent') }}
                    </div>
                @endif

                <div class="space-y-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full py-5 bg-emerald-500 text-black text-[10px] font-black uppercase tracking-[0.4em] rounded-2xl hover:bg-emerald-400 transition-all">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('verification.code.send') }}">
                        @csrf
                        <button type="submit" class="w-full py-4 border border-cyan-500/35 text-cyan-700 dark:text-cyan-300 text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-cyan-500/10 transition-all">
                            {{ __('Send 6-Digit Code Instead') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('verification.code.confirm') }}" class="space-y-3">
                        @csrf
                        <input
                            type="text"
                            name="verification_code"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            inputmode="numeric"
                            placeholder="Enter 6-digit code"
                            class="w-full text-center rounded-2xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-4 py-4 text-zinc-900 dark:text-zinc-100 text-[12px] font-black tracking-[0.35em] uppercase focus:outline-none focus:ring-2 focus:ring-emerald-500/30"
                            required
                        />
                        @error('verification_code')
                            <p class="text-rose-500 text-xs font-semibold">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="w-full py-4 bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:opacity-90 transition-all">
                            {{ __('Verify Code and Continue') }}
                        </button>
                    </form>

                    <a
                        href="{{ route('dashboard') }}"
                        id="verification-continue-btn"
                        class="block w-full text-center py-4 border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:border-emerald-500/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all"
                    >
                        {{ __('I Have Verified, Continue') }}
                    </a>

                    <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-[0.25em]">
                        <a href="{{ route('profile.show') }}" class="text-zinc-500 hover:text-emerald-600 dark:text-zinc-600 dark:hover:text-emerald-400 transition-colors">
                            {{ __('Edit Profile') }}
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-zinc-500 hover:text-rose-500 dark:text-zinc-600 dark:hover:text-rose-400 transition-colors">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const statusUrl = "{{ route('verification.status') }}";
            const defaultRedirect = "{{ route('dashboard') }}";
            const check = async () => {
                try {
                    const response = await fetch(statusUrl, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) return;
                    const payload = await response.json();
                    if (payload.verified) {
                        window.location.href = payload.redirect || defaultRedirect;
                    }
                } catch (_error) {
                    // Silent retry loop; user can still click "continue" manually.
                }
            };

            // Immediate check + periodic checks for users who verify in another tab.
            check();
            setInterval(check, 3000);

            const continueBtn = document.getElementById('verification-continue-btn');
            if (continueBtn) {
                continueBtn.addEventListener('click', async (event) => {
                    event.preventDefault();
                    try {
                        const response = await fetch(statusUrl, {
                            method: 'GET',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });
                        const payload = response.ok ? await response.json() : { verified: false };
                        if (payload.verified) {
                            window.location.href = payload.redirect || defaultRedirect;
                            return;
                        }
                        alert('Email is not verified yet. Open the verification link from the same network and try again.');
                    } catch (_error) {
                        alert('Could not check verification status right now. Please try again.');
                    }
                });
            }
        })();
    </script>
</x-guest-layout>
