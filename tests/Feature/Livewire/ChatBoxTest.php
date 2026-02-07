<?php

use App\Livewire\ChatBox;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('chat box component can be rendered', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(ChatBox::class)
        ->assertSuccessful();
});

test('chat box component can open chat', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    Livewire::actingAs($user1)
        ->test(ChatBox::class)
        ->call('openChat', $user2->id)
        ->assertSet('isOpen', true)
        ->assertSet('otherUserId', $user2->id);
});

test('chat box component can close chat', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(ChatBox::class)
        ->set('isOpen', true)
        ->call('closeChat')
        ->assertSet('isOpen', false)
        ->assertSet('chatId', null);
});

test('chat box component can send message', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->following()->attach($user2->id);
    $user2->following()->attach($user1->id);
    
    Livewire::actingAs($user1)
        ->test(ChatBox::class)
        ->call('openChat', $user2->id)
        ->set('newMessage', 'Hello')
        ->call('sendMessage')
        ->assertSet('newMessage', '');
});
