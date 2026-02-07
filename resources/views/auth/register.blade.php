<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold dark:text-white text-gray-900 mb-2">{{ __('Create Account') }}</h2>
            <p class="dark:text-gray-300 text-gray-700 text-sm">{{ __('Join us and start your journey') }}</p>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Full Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                    autofocus autocomplete="name" placeholder="{{ __('Enter your full name') }}" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email Address') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                    autocomplete="email" placeholder="{{ __('Enter your email') }}" />
            </div>

            <div class="mt-4">
                <x-label for="username" value="{{ __('Username') }}" />
                <x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')"
                    autocomplete="username" placeholder="{{ __('Choose a username (optional)') }}" />
                <p class="mt-1 text-xs dark:text-gray-400 text-gray-600">{{ __('Leave blank to auto-generate from your email') }}</p>
            </div>

            <div class="mt-4">
                <x-label for="role" value="{{ __('I am a') }}" />
                <select id="role" name="role"
                    class="block mt-1 w-full dark:bg-gray-800/70 bg-gray-100 backdrop-blur-sm border dark:border-gray-700 border-gray-300 dark:text-white text-gray-900 dark:placeholder-gray-400 placeholder-gray-500 focus:border-blue-500 dark:focus:ring-gray-700 focus:ring-blue-500 focus:ring-2 rounded-xl shadow-lg px-4 py-3 transition-all duration-300"
                    required>
                    <option value="" class="dark:bg-gray-900 bg-white dark:text-white text-gray-900">{{ __('Select your role') }}</option>
                    <option value="seeker" {{ old('role') == 'seeker' ? 'selected' : '' }} class="dark:bg-gray-900 bg-white dark:text-white text-gray-900">{{ __('Seeker') }} - Looking for jobs</option>
                    <option value="company" {{ old('role') == 'company' ? 'selected' : '' }} class="dark:bg-gray-900 bg-white dark:text-white text-gray-900">{{ __('Company') }} - Posting jobs</option>
                </select>
                <p class="mt-1 text-xs dark:text-gray-400 text-gray-600">{{ __('Are you looking for jobs or posting jobs?') }}</p>
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" placeholder="{{ __('Create a password') }}" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm your password') }}" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-start">
                            <x-checkbox name="terms" id="terms" required class="mt-1" />

                            <div class="ms-2 text-sm dark:text-gray-300 text-gray-700">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target=\"_blank\" href=\"'.route('terms.show').'\" class=\"dark:text-gray-300 text-gray-700 dark:hover:text-white hover:text-blue-600 underline\">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a target=\"_blank\" href=\"'.route('policy.show').'\" class=\"dark:text-gray-300 text-gray-700 dark:hover:text-white hover:text-blue-600 underline\">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="mt-6">
                <x-button class="w-full justify-center">
                    {{ __('Create Account') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </x-button>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm dark:text-gray-300 text-gray-700">
                    {{ __('Already have an account?') }}
                    <a href="{{ route('login') }}" class="dark:text-gray-300 text-gray-700 dark:hover:text-white hover:text-blue-600 font-semibold underline transition-colors">
                        {{ __('Sign in') }}
                    </a>
                </p>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
