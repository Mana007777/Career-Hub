<?php

use App\Repositories\MessageReadRepository;
use App\Models\Chat;
use App\Models\Message;
use App\Models\MessageRead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('message read repository can mark message as read', function () {
    $chat = Chat::factory()->create();
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $chat->users()->attach([$user1->id, $user2->id]);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user1->id]);
    
    $repository = new MessageReadRepository();
    $read = $repository->markAsRead($message, $user2->id);
    
    expect($read)->toBeInstanceOf(MessageRead::class)
        ->and($read->message_id)->toBe($message->id)
        ->and($read->user_id)->toBe($user2->id);
});
