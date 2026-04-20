<?php

namespace App\Livewire\Listeners;

use App\Livewire\Post;
use Illuminate\Support\Facades\Auth;

class OpenCreatePostListener
{
    public function handle(Post $component): void
    {
        if (Auth::check() && Auth::user()->isSeeker()) {
            $component->redirect(route('my-reposts'));

            return;
        }

        $component->toggleCreateForm();
    }
}
