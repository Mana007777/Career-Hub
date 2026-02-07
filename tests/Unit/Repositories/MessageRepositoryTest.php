<?php

use App\Repositories\MessageRepository;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('message repository can create message', function () {
    $chat = Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    
    $repository = new MessageRepository();
    $message = $repository->create([
        'chat_id' => $chat->id,
        'sender_id' => $user->id,
        'message' => 'Test message',
        'status' => 'sent',
    ]);
    
    expect($message)->toBeInstanceOf(Message::class)
        ->and($message->message)->toBe('Test message');
});

test('message repository can get messages for chat', function () {
    $chat = Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    Message::factory()->count(3)->create(['chat_id' => $chat->id, 'sender_id' => $user->id]);
    
    $repository = new MessageRepository();
    $messages = $repository->getMessagesForChat($chat, 10);
    
    expect($messages)->toHaveCount(3);
});

test('message repository can load sender on message', function () {
    $chat = Chat::factory()->create();
    $user = User::factory()->create();
    $chat->users()->attach($user->id);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id]);
    
    $repository = new MessageRepository();
    $loaded = $repository->loadSender($message);
    
    expect($loaded->relationLoaded('sender'))->toBeTrue();
});
