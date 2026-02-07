<?php

use App\Repositories\ChatRepository;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('chat repository can create chat', function () {
    $repository = new ChatRepository();
    $chat = $repository->create(['is_group' => false]);
    
    expect($chat)->toBeInstanceOf(Chat::class)
        ->and($chat->is_group)->toBeFalse();
});

test('chat repository can attach users to chat', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $chat = Chat::factory()->create();
    
    $repository = new ChatRepository();
    $repository->attachUsers($chat, [$user1->id, $user2->id]);
    
    expect($chat->users)->toHaveCount(2);
});

test('chat repository can load users on chat', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->create();
    $chat->users()->attach($user->id);
    
    $repository = new ChatRepository();
    $loaded = $repository->loadUsers($chat);
    
    expect($loaded->relationLoaded('users'))->toBeTrue();
});
