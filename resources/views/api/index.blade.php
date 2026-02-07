<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl dark:text-white text-gray-900 leading-tight">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>

    <div class="dark:bg-gray-900 bg-white dark:text-white text-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('api.api-token-manager')
        </div>
    </div>
</x-app-layout>
