<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserSuspension;
use App\Repositories\UserRepository;

class UserSuspensionObserver
{
    public function saved(UserSuspension $suspension): void
    {
        $this->clearProfileCache($suspension);
    }

    public function deleted(UserSuspension $suspension): void
    {
        $this->clearProfileCache($suspension);
    }

    private function clearProfileCache(UserSuspension $suspension): void
    {
        $user = User::query()->find($suspension->user_id);
        if ($user) {
            app(UserRepository::class)->clearUserCache($user);
        }
    }
}
