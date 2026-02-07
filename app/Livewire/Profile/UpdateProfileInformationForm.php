<?php

namespace App\Livewire\Profile;

use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm as JetstreamUpdateProfileInformationForm;

class UpdateProfileInformationForm extends JetstreamUpdateProfileInformationForm
{
    /**
     * Prepare the component.
     *
     * @return void
     */
    public function mount()
    {
        $profile = $this->user->profile;
        $this->state = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'username' => $this->user->username ?? '',
            'bio' => $profile->bio ?? '',
            'location' => $profile->location ?? '',
            'website' => $profile->website ?? '',
        ];
    }
}
