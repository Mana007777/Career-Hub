<?php

namespace App\Livewire\Listeners;

use Livewire\Component;

class RefreshPostsListener
{
    public function handle(Component $component): void
    {
        $component->dispatch('$refresh');
    }
}
