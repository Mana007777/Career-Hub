<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        @if ($error)
            <div class="mb-4 p-4 dark:bg-red-900/40 bg-red-50 border dark:border-red-700/60 border-red-200 rounded-xl">
                <p class="text-sm dark:text-red-200 text-red-700 font-medium">
                    {{ $error }}
                </p>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-8">
                <svg class="w-12 h-12 mb-4 text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm dark:text-gray-300 text-gray-700">
                    Completing GitHub sign-in, please wait...
                </p>
            </div>
        @endif
    </x-authentication-card>
</x-guest-layout>

