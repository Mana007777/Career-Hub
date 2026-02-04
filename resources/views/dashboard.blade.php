<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 text-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-gray-900 border border-gray-800 shadow-xl sm:rounded-lg">
                @if(isset($postSlug))
                    <livewire:post-detail :slug="$postSlug" />
                @else
                    <livewire:post />
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
