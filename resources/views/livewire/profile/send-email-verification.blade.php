<div>
    @if ($user && !$user->email_verified_at)
        <button 
            type="button" 
            wire:click="sendEmailVerification"
            wire:loading.attr="disabled"
            class="inline-flex items-center px-3 py-1.5 border dark:border-gray-600 border-gray-300 text-xs font-medium rounded-md dark:text-white text-gray-900 dark:bg-gray-800 bg-gray-200 dark:hover:bg-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
        >
            <svg wire:loading.remove wire:target="sendEmailVerification" class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <svg wire:loading wire:target="sendEmailVerification" class="animate-spin w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span wire:loading.remove wire:target="sendEmailVerification">{{ __('Send Verification Email') }}</span>
            <span wire:loading wire:target="sendEmailVerification">{{ __('Sending...') }}</span>
        </button>

        @if ($verificationLinkSent)
            <p class="mt-2 text-sm font-medium text-green-600">
                {{ __('A new verification link has been sent to your email address.') }}
            </p>
        @endif

        @if (session('verification-error'))
            <p class="mt-2 text-sm font-medium text-red-600">
                {{ session('verification-error') }}
            </p>
        @endif
    @endif
</div>
