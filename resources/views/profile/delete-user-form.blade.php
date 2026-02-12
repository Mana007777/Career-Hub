<x-action-section>
    <x-slot name="title">
        {{ __('Delete Account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Permanently delete your account.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </div>

        <div class="mt-5 space-y-3 max-w-xl">
            <div>
                <x-input
                    type="password"
                    class="mt-1 block w-3/4"
                    autocomplete="current-password"
                    placeholder="{{ __('Password') }}"
                    wire:model.defer="password"
                    wire:keydown.enter="deleteUser"
                />

                <x-input-error for="password" class="mt-2" />
            </div>

            <x-danger-button wire:click="deleteUser" wire:loading.attr="disabled">
                {{ __('Delete Account') }}
            </x-danger-button>
        </div>
    </x-slot>
</x-action-section>
