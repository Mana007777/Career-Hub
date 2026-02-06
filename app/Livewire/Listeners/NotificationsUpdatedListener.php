<?php

namespace App\Livewire\Listeners;

use Livewire\Component;

class NotificationsUpdatedListener
{
    public function handle(Component $component): void
    {
        $component->dispatch('$refresh');
    }
}
