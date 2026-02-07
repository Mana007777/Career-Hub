<?php

use App\Models\Message;
use App\Models\Chat;
use App\Models\User;
use App\Models\MessageAttachment;
use App\Models\MessageRead;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('message can be created', function () {
    $chat = Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    
    $message = Message::factory()->create([
        'chat_id' => $chat->id,
        'sender_id' => $user->id,
        'message' => 'Test message',
        'status' => 'sent',
    ]);

    expect($message->message)->toBe('Test message')
        ->and($message->status)->toBe('sent')
        ->and($message->chat_id)->toBe($chat->id)
        ->and($message->sender_id)->toBe($user->id);
});

test('message belongs to chat', function () {
    $chat = Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id]);

    expect($message->chat)->toBeInstanceOf(Chat::class)
        ->and($message->chat->id)->toBe($chat->id);
});

test('message belongs to sender', function () {
    $chat = Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id]);

    expect($message->sender)->toBeInstanceOf(User::class)
        ->and($message->sender->id)->toBe($user->id);
});

test('message has many attachments', function () {
    $chat = Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id]);
    MessageAttachment::factory()->count(2)->create(['message_id' => $message->id]);

    expect($message->attachments)->toHaveCount(2);
});

test('message has many reads', function () {
    $chat = Chat::factory()->create();
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $chat->users()->attach([$user1->id, $user2->id]);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user1->id]);
    MessageRead::factory()->create(['message_id' => $message->id, 'user_id' => $user2->id]);

    expect($message->reads)->toHaveCount(1);
});

test('message status is cast to string', function () {
    $chat = Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id, 'status' => 'delivered']);

    expect($message->status)->toBeString()
        ->and($message->status)->toBe('delivered');
});
