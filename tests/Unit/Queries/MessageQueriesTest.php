<?php

use App\Queries\MessageQueries;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Models\MessageRead;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('message queries can get unread messages', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $chat = Chat::factory()->create(['is_group' => false]);
    $chat->users()->attach([$user1->id, $user2->id]);
    Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user1->id]);
    
    $queries = new MessageQueries();
    $unread = $queries->getUnreadMessages($chat, $user2->id);
    
    expect($unread)->toHaveCount(1);
});

test('message queries can get unread count', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $chat = Chat::factory()->create(['is_group' => false]);
    $chat->users()->attach([$user1->id, $user2->id]);
    Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user1->id]);
    
    $queries = new MessageQueries();
    $count = $queries->getUnreadCount($chat, $user2->id);
    
    expect($count)->toBe(1);
});

test('message queries can clear unread count cache', function () {
    $queries = new MessageQueries();
    
    expect(fn() => $queries->clearUnreadCountCache(1, 1))->not->toThrow(\Exception::class);
});
