<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Http\Livewire\UpdatePasswordForm as JetstreamUpdatePasswordForm;

class UpdatePasswordForm extends JetstreamUpdatePasswordForm
{
    /**
     * GitHub (and similar) users never chose a password; only new + confirm are shown.
     */
    public function skipsCurrentPassword(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user !== null && $user->skipsCurrentPasswordForUpdate();
    }
}
