<?php

namespace App\Livewire\Listeners;

use App\Livewire\Post;

class OpenCreatePostListener
{
    public function handle(Post $component): void
    {
        $component->toggleCreateForm();
    }
}
