<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold dark:text-white text-gray-900 mb-2">{{ __('Welcome Back') }}</h2>
            <p class="dark:text-gray-300 text-gray-700 text-sm">{{ __('Sign in to your account') }}</p>
        </div>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 p-4 dark:bg-green-500/20 bg-green-50 dark:border-green-500/50 border-green-200 rounded-xl backdrop-blur-sm">
                <p class="font-medium text-sm dark:text-green-300 text-green-800">
                    {{ $value }}
                </p>
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="{{ __('Enter your email') }}" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="{{ __('Enter your password') }}" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm dark:text-gray-300 text-gray-700">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm dark:text-gray-300 text-gray-700 dark:hover:text-white hover:text-blue-600 underline transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <div class="mt-6">
                <x-button class="w-full justify-center">
                    {{ __('Log in') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </x-button>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-300">
                    {{ __('Don\'t have an account?') }}
                    <a href="{{ route('register') }}" class="text-gray-300 hover:text-white font-semibold underline transition-colors">
                        {{ __('Sign up') }}
                    </a>
                </p>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
