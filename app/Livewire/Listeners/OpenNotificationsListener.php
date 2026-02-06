<?php

namespace App\Livewire\Listeners;

use App\Livewire\Notifications;

class OpenNotificationsListener
{
    public function handle(Notifications $component): void
    {
        $component->toggleNotifications();
    }
}
