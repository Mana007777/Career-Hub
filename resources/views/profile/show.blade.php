<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl dark:text-white text-gray-900 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="dark:bg-gray-900 bg-white dark:text-white text-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <!-- Profile Information Display -->
            <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 overflow-hidden shadow-xl sm:rounded-lg mb-10">
                <div class="p-6 sm:p-8">
                    <h3 class="text-2xl font-bold dark:text-white text-gray-900 mb-6">{{ __('Profile Information') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Profile Photo & Basic Info -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" 
                                     class="h-24 w-24 rounded-full object-cover border-4 dark:border-gray-700 border-gray-300">
                                <div>
                                    <h4 class="text-xl font-semibold dark:text-white text-gray-900">{{ auth()->user()->name }}</h4>
                                    <p class="text-sm dark:text-gray-300 text-gray-700">{{ auth()->user()->username }}</p>
                                    @if(auth()->user()->role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ auth()->user()->role === 'seeker' ? 'dark:bg-blue-600/20 bg-blue-100 dark:text-blue-300 text-blue-700 dark:border-blue-600/50 border-blue-300 border' : 'dark:bg-purple-600/20 bg-purple-100 dark:text-purple-300 text-purple-700 dark:border-purple-600/50 border-purple-300 border' }}">
                                            {{ ucfirst(auth()->user()->role) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Account Details -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium dark:text-gray-400 text-gray-600">{{ __('Email Address') }}</label>
                                <p class="mt-1 text-sm dark:text-white text-gray-900">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->email_verified_at)
                                    <span class="inline-flex items-center mt-1 text-xs text-green-400">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('Verified') }}
                                    </span>
                                @else
                                    <div class="mt-2">
                                        @livewire('profile.send-email-verification')
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400">{{ __('Username') }}</label>
                                <p class="mt-1 text-sm text-white">{{ auth()->user()->username }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400">{{ __('Account Created') }}</label>
                                <p class="mt-1 text-sm text-white">{{ auth()->user()->created_at->format('F d, Y') }}</p>
                                <p class="text-xs text-gray-400">{{ auth()->user()->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Details (if exists) -->
                    @if(auth()->user()->profile)
                        <div class="mt-8 pt-6 border-t dark:border-gray-800 border-gray-200">
                            <h4 class="text-lg font-semibold dark:text-white text-gray-900 mb-4">{{ __('Additional Information') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if(auth()->user()->profile->bio)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">{{ __('Bio') }}</label>
                                        <p class="mt-1 text-sm dark:text-white text-gray-900">{{ auth()->user()->profile->bio }}</p>
                                    </div>
                                @endif

                                @if(auth()->user()->profile->location)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">{{ __('Location') }}</label>
                                        <p class="mt-1 text-sm text-white">
                                            <svg class="inline-block w-4 h-4 mr-1 dark:text-gray-400 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ auth()->user()->profile->location }}
                                        </p>
                                    </div>
                                @endif

                                @if(auth()->user()->profile->website)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">{{ __('Website') }}</label>
                                        <p class="mt-1 text-sm">
                                            <a href="{{ auth()->user()->profile->website }}" target="_blank" 
                                               class="dark:text-gray-200 text-gray-700 hover:text-blue-600 dark:hover:text-white underline">
                                                {{ auth()->user()->profile->website }}
                                            </a>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Admin Badge -->
                    @if(auth()->user()->is_admin ?? false)
                        <div class="mt-6 pt-6 border-t dark:border-gray-800 border-gray-200">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium dark:bg-red-900/30 bg-red-100 dark:text-red-300 text-red-700 dark:border-red-700/50 border-red-300 border">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('Administrator') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Special guidance for users who signed in via GitHub in this session --}}
            @if (session('logged_in_via_github'))
                <div class="mb-8">
                    <div class="rounded-xl border border-yellow-300 dark:border-yellow-500/60 bg-yellow-50 dark:bg-yellow-900/20 px-4 py-3 text-sm">
                        <div class="font-semibold text-yellow-800 dark:text-yellow-200 mb-1">
                            {{ __('You signed in with GitHub') }}
                        </div>
                        <p class="text-yellow-900 dark:text-yellow-100">
                            {{ __('Because you used GitHub to log in, you might not know your account password yet. To use features that ask for your password (like enabling two-factor authentication, logging out other browser sessions, or deleting your account), first go to the login page, click “Forgot password?”, enter this account’s email address, and complete the reset flow to create a password.') }}
                        </p>
                    </div>
                </div>
            @endif

            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form', ['key' => 'update-profile-information-form'])

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
