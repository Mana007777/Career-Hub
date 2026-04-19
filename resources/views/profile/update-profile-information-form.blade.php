<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="w-full">
                <input type="file" id="photo" class="hidden"
                            accept=".jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="size-20 rounded-full border-2 border-zinc-200 object-cover dark:border-zinc-700">
                </div>

                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block size-20 rounded-full border-2 border-zinc-200 bg-cover bg-center bg-no-repeat dark:border-zinc-700"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-3 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-3" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <div class="w-full">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <div class="w-full">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1" wire:model="state.email" required autocomplete="email" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="font-semibold text-emerald-600 underline decoration-emerald-500/30 underline-offset-2 transition hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 text-sm font-medium text-emerald-700 dark:text-emerald-400">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <div class="w-full">
            <x-label for="username" value="{{ __('Username') }}" />
            <x-input id="username" type="text" class="mt-1" wire:model="state.username" autocomplete="username" />
            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-500">{{ __('Your unique username. Only letters, numbers, and underscores allowed.') }}</p>
            <x-input-error for="username" class="mt-2" />
        </div>

        <div class="w-full">
            <x-label for="bio" value="{{ __('Bio') }}" />
            <textarea
                id="bio"
                rows="4"
                wire:model="state.bio"
                placeholder="{{ __('Tell us about yourself...') }}"
                class="mt-1 block w-full resize-y rounded-2xl border border-zinc-200 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm placeholder:text-zinc-400 transition-colors focus:border-emerald-500/50 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-zinc-800 dark:bg-zinc-950 dark:text-zinc-100 dark:placeholder:text-zinc-600 dark:focus:border-emerald-500/40 dark:focus:ring-emerald-500/25"
            ></textarea>
            <x-input-error for="bio" class="mt-2" />
        </div>

        <div class="w-full">
            <x-label for="location" value="{{ __('Location') }}" />
            <x-input id="location" type="text" class="mt-1" wire:model="state.location" placeholder="{{ __('City, Country') }}" />
            <x-input-error for="location" class="mt-2" />
        </div>

        <div class="w-full">
            <x-label for="website" value="{{ __('Website') }}" />
            <x-input id="website" type="url" class="mt-1" wire:model="state.website" placeholder="https://example.com" />
            <x-input-error for="website" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
