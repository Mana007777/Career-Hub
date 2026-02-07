<?php

use App\Queries\ChatQueries;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('chat queries can find chat between users', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $chat = Chat::factory()->create(['is_group' => false]);
    $chat->users()->attach([$user1->id, $user2->id]);
    
    $queries = new ChatQueries();
    $found = $queries->findChatBetweenUsers($user1, $user2);
    
    expect($found)->toBeInstanceOf(Chat::class)
        ->and($found->id)->toBe($chat->id);
});

test('chat queries can get chats for user', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->create(['is_group' => false]);
    $chat->users()->attach($user->id);
    
    $queries = new ChatQueries();
    $chats = $queries->getChatsForUser($user);
    
    expect($chats)->toHaveCount(1);
});

test('chat queries can get chats for unread count', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->create(['is_group' => false]);
    $chat->users()->attach($user->id);
    
    $queries = new ChatQueries();
    $chats = $queries->getChatsForUnreadCount($user->id);
    
    expect($chats)->toHaveCount(1);
});

test('chat queries can clear user chat cache', function () {
    $queries = new ChatQueries();
    
    expect(fn() => $queries->clearUserChatCache(1))->not->toThrow(\Exception::class);
});
