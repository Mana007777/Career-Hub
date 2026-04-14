<?php

namespace App\Observers;

use App\Events\UserNotificationCreated;
use App\Models\UserNotification;

class UserNotificationObserver
{
    public function created(UserNotification $notification): void
    {
        broadcast(new UserNotificationCreated($notification));
    }
}
