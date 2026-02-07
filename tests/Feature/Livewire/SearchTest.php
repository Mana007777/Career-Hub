<?php

use App\Livewire\Search;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('search component can be rendered', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Search::class)
        ->assertSuccessful();
});

test('search component can toggle search', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Search::class)
        ->assertSet('showSearch', false)
        ->call('toggleSearch')
        ->assertSet('showSearch', true);
});

test('search component can close search', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Search::class)
        ->set('showSearch', true)
        ->call('closeSearch')
        ->assertSet('showSearch', false);
});

test('search component resets query when closing', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Search::class)
        ->set('query', 'test')
        ->call('closeSearch')
        ->assertSet('query', '');
});
