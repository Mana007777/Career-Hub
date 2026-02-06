<?php

namespace App\Livewire\Listeners;

use App\Livewire\Search;

class OpenSearchListener
{
    public function handle(Search $component): void
    {
        $component->toggleSearch();
    }
}
