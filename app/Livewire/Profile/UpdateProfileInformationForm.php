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
        $this->state = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'username' => $this->user->username ?? '',
            'bio' => $this->user->profile->bio ?? '',
            'location' => $this->user->profile->location ?? '',
            'website' => $this->user->profile->website ?? '',
        ];
    }
}
