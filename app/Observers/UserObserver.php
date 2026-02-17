<?php

namespace App\Observers;

use App\Models\User;
use App\Repositories\UserRepository;

class UserObserver
{
    /**
     * Clear user cache when profile_photo_path changes so profile page shows updated photo.
     */
    public function saved(User $user): void
    {
        if ($user->wasChanged('profile_photo_path')) {
            app(UserRepository::class)->clearUserCache($user);
        }
    }
}
