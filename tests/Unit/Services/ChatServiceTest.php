<?php

use App\Services\ChatService;
use App\Repositories\ChatRepository;
use App\Repositories\MessageRepository;
use App\Repositories\MessageReadRepository;
use App\Repositories\ChatRequestRepository;
use App\Repositories\UserRepository;
use App\Queries\ChatQueries;
use App\Queries\MessageQueries;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

test('chat service can get or create chat', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->following()->attach($user2->id);
    $user2->following()->attach($user1->id);
    
    Auth::login($user1);
    
    $service = new ChatService(
        new ChatRepository(),
        new ChatQueries(),
        new MessageRepository(),
        new MessageQueries(),
        new MessageReadRepository(),
        new ChatRequestRepository(),
        new UserRepository()
    );
    
    $chat = $service->getOrCreateChat($user2);
    
    expect($chat)->toBeInstanceOf(Chat::class);
});

test('chat service can send message', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->following()->attach($user2->id);
    $user2->following()->attach($user1->id);
    $chat = Chat::factory()->create(['is_group' => false]);
    $chat->users()->attach([$user1->id, $user2->id]);
    
    Auth::login($user1);
    
    $service = new ChatService(
        new ChatRepository(),
        new ChatQueries(),
        new MessageRepository(),
        new MessageQueries(),
        new MessageReadRepository(),
        new ChatRequestRepository(),
        new UserRepository()
    );
    
    $message = $service->sendMessage($chat, 'Test message');
    
    expect($message)->toBeInstanceOf(\App\Models\Message::class)
        ->and($message->message)->toBe('Test message');
});

test('chat service can get messages', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $chat = Chat::factory()->create(['is_group' => false]);
    $chat->users()->attach([$user1->id, $user2->id]);
    \App\Models\Message::factory()->count(3)->create(['chat_id' => $chat->id, 'sender_id' => $user1->id]);
    
    $service = new ChatService(
        new ChatRepository(),
        new ChatQueries(),
        new MessageRepository(),
        new MessageQueries(),
        new MessageReadRepository(),
        new ChatRequestRepository(),
        new UserRepository()
    );
    
    $messages = $service->getMessages($chat);
    
    expect($messages)->toHaveCount(3);
});

test('chat service can check if following', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->following()->attach($user2->id);
    
    $service = new ChatService(
        new ChatRepository(),
        new ChatQueries(),
        new MessageRepository(),
        new MessageQueries(),
        new MessageReadRepository(),
        new ChatRequestRepository(),
        new UserRepository()
    );
    
    $isFollowing = $service->isFollowing($user1, $user2);
    
    expect($isFollowing)->toBeTrue();
});

test('chat service can check if followed back', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->followers()->attach($user2->id);
    
    $service = new ChatService(
        new ChatRepository(),
        new ChatQueries(),
        new MessageRepository(),
        new MessageQueries(),
        new MessageReadRepository(),
        new ChatRequestRepository(),
        new UserRepository()
    );
    
    $isFollowedBack = $service->isFollowedBack($user1, $user2);
    
    expect($isFollowedBack)->toBeTrue();
});
