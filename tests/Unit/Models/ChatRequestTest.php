<?php

use App\Models\ChatRequest;
use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('chat request can be created', function () {
    $fromUser = User::factory()->create();
    $toUser = User::factory()->create();
    $chat = \App\Models\Chat::factory()->create();
    $chat->users()->attach([$fromUser->id, $toUser->id]);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $fromUser->id]);
    $request = ChatRequest::factory()->create([
        'from_user_id' => $fromUser->id,
        'to_user_id' => $toUser->id,
        'message_id' => $message->id,
        'status' => 'pending',
    ]);

    expect($request->from_user_id)->toBe($fromUser->id)
        ->and($request->to_user_id)->toBe($toUser->id)
        ->and($request->status)->toBe('pending');
});

test('chat request belongs to from user', function () {
    $fromUser = User::factory()->create();
    $toUser = User::factory()->create();
    $request = ChatRequest::factory()->create([
        'from_user_id' => $fromUser->id,
        'to_user_id' => $toUser->id,
    ]);

    expect($request->fromUser)->toBeInstanceOf(User::class)
        ->and($request->fromUser->id)->toBe($fromUser->id);
});

test('chat request belongs to to user', function () {
    $fromUser = User::factory()->create();
    $toUser = User::factory()->create();
    $request = ChatRequest::factory()->create([
        'from_user_id' => $fromUser->id,
        'to_user_id' => $toUser->id,
    ]);

    expect($request->toUser)->toBeInstanceOf(User::class)
        ->and($request->toUser->id)->toBe($toUser->id);
});

test('chat request belongs to message', function () {
    $fromUser = User::factory()->create();
    $toUser = User::factory()->create();
    $chat = \App\Models\Chat::factory()->create();
    $chat->users()->attach([$fromUser->id, $toUser->id]);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $fromUser->id]);
    $request = ChatRequest::factory()->create([
        'from_user_id' => $fromUser->id,
        'to_user_id' => $toUser->id,
        'message_id' => $message->id,
    ]);

    expect($request->message)->toBeInstanceOf(Message::class)
        ->and($request->message->id)->toBe($message->id);
});

test('chat request responded_at is cast to datetime', function () {
    $fromUser = User::factory()->create();
    $toUser = User::factory()->create();
    $request = ChatRequest::factory()->create([
        'from_user_id' => $fromUser->id,
        'to_user_id' => $toUser->id,
        'responded_at' => now(),
    ]);

    expect($request->responded_at)->toBeInstanceOf(\DateTimeInterface::class);
});
