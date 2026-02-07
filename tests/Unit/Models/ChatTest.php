<?php

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('chat can be created', function () {
    $chat = Chat::factory()->create(['is_group' => false]);

    expect($chat->is_group)->toBeFalse();
});

test('chat belongs to many users', function () {
    $chat = Chat::factory()->create();
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $chat->users()->attach([$user1->id, $user2->id]);

    expect($chat->users)->toHaveCount(2);
});

test('chat has many messages', function () {
    $chat = Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    Message::factory()->count(3)->create(['chat_id' => $chat->id, 'sender_id' => $user->id]);

    expect($chat->messages)->toHaveCount(3);

});

test('chat messages are ordered by created_at ascending', function () {
    $chat = Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    
    $message1 = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id, 'created_at' => now()->subHour()]);
    $message2 = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id, 'created_at' => now()]);

    $messages = $chat->messages;
    
    expect($messages->first()->id)->toBe($message1->id)
        ->and($messages->last()->id)->toBe($message2->id);
});

test('chat get other user returns null for group chat', function () {
    $chat = Chat::factory()->create(['is_group' => true]);
    $user = User::factory()->create();
    $chat->users()->attach($user->id);

    expect($chat->getOtherUser($user->id))->toBeNull();
});

test('chat get other user returns correct user for one on one chat', function () {
    $chat = Chat::factory()->create(['is_group' => false]);
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $chat->users()->attach([$user1->id, $user2->id]);

    $otherUser = $chat->getOtherUser($user1->id);
    
    expect($otherUser)->toBeInstanceOf(User::class)
        ->and($otherUser->id)->toBe($user2->id);
});

test('chat is_group is cast to boolean', function () {
    $chat = Chat::factory()->create(['is_group' => 1]);

    expect($chat->is_group)->toBeBool()
        ->and($chat->is_group)->toBeTrue();
});
