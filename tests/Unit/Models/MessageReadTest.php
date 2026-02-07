<?php

use App\Models\MessageRead;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('message read can be created', function () {
    $chat = \App\Models\Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id]);
    $read = MessageRead::factory()->create([
        'message_id' => $message->id,
        'user_id' => $user->id,
        'read_at' => now(),
    ]);

    expect($read->message_id)->toBe($message->id)
        ->and($read->user_id)->toBe($user->id);
});

test('message read belongs to message', function () {
    $chat = \App\Models\Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id]);
    $read = MessageRead::factory()->create(['message_id' => $message->id, 'user_id' => $user->id]);

    expect($read->message)->toBeInstanceOf(Message::class)
        ->and($read->message->id)->toBe($message->id);
});

test('message read belongs to user', function () {
    $chat = \App\Models\Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id]);
    $read = MessageRead::factory()->create(['message_id' => $message->id, 'user_id' => $user->id]);

    expect($read->user)->toBeInstanceOf(User::class)
        ->and($read->user->id)->toBe($user->id);
});

test('message read read_at is cast to datetime', function () {
    $chat = \App\Models\Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id]);
    $read = MessageRead::factory()->create([
        'message_id' => $message->id,
        'user_id' => $user->id,
        'read_at' => now(),
    ]);

    expect($read->read_at)->toBeInstanceOf(\DateTimeInterface::class);
});
