<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        @php
            $suspendedUntil = session('suspended_until');
            $suspensionReason = session('suspension_reason');
        @endphp

        <div class="space-y-6">
            {{-- Header --}}
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-50 dark:bg-red-900/70 mb-4">
                    <svg class="w-9 h-9 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.72 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v4m0 4h.01" />
                    </svg>
                </div>

                <h2 class="text-3xl font-extrabold tracking-tight text-red-600 dark:text-red-300">
                    {{ __('Account Suspended') }}
                </h2>
                <div class="mt-2 h-1 w-16 mx-auto rounded-full bg-red-500/80 dark:bg-red-400/80"></div>
                <p class="mt-4 text-sm text-gray-700 dark:text-gray-200">
                    {{ __('Your access has been temporarily restricted to keep the community safe.') }}
                </p>
            </div>

            {{-- Suspension details --}}
            <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-red-600 dark:text-red-300">
                            {{ __('Suspension status') }}
                        </p>
                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $suspendedUntil ? __('Temporary suspension') : __('Indefinite suspension') }}
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/60 dark:text-red-200 border border-red-200 dark:border-red-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                        {{ __('Active') }}
                    </span>
                </div>

                <div class="space-y-2 text-sm text-gray-700 dark:text-gray-200">
                    @if($suspendedUntil)
                        <p>
                            {{ __('Your account will be reviewed again on') }}
                            <span class="font-semibold">
                                {{ \Illuminate\Support\Carbon::parse($suspendedUntil)->toDayDateTimeString() }}
                            </span>.
                        </p>
                    @else
                        <p>
                            {{ __('This suspension does not have an end date and will remain in place until an administrator lifts it.') }}
                        </p>
                    @endif

                    @if(!empty($suspensionReason))
                        <div class="mt-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-3 py-2">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200 mb-1">
                                {{ __('Reason provided by the administrator') }}
                            </p>
                            <p class="text-xs text-gray-700 dark:text-gray-200 leading-relaxed">
                                {{ $suspensionReason }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- What you can do --}}
            <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-5 space-y-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">
                    {{ __('What can you do next?') }}
                </p>
                <ul class="space-y-2 text-xs text-gray-600 dark:text-gray-300">
                    <li class="flex gap-2">
                        <span class="mt-0.5 text-gray-400 dark:text-gray-500">•</span>
                        <span>{{ __('If you believe this is a mistake, you can contact support and include any relevant details.') }}</span>
                    </li>
                    @if($suspendedUntil)
                        <li class="flex gap-2">
                            <span class="mt-0.5 text-gray-400 dark:text-gray-500">•</span>
                            <span>{{ __('You may try logging in again after the suspension review date shown above.') }}</span>
                        </li>
                    @endif
                    <li class="flex gap-2">
                        <span class="mt-0.5 text-gray-400 dark:text-gray-500">•</span>
                        <span>{{ __('In the meantime, you can still browse public content without logging in.') }}</span>
                    </li>
                </ul>
            </div>

            {{-- Actions --}}
            <div class="space-y-3">
                <a href="{{ route('login') }}">
                    <x-button class="w-full justify-center">
                        {{ __('Back to login') }}
                    </x-button>
                </a>

                <p class="text-xs text-center text-gray-500 dark:text-gray-400">
                    {{ __('For urgent issues, please reach out to our support team with your account email.') }}
                </p>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>

