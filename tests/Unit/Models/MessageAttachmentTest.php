<?php

use App\Models\MessageAttachment;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('message attachment can be created', function () {
    $chat = \App\Models\Chat::factory()->create();
    $user = \App\Models\User::factory()->create();
    $chat->users()->attach($user->id);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id]);
    $attachment = MessageAttachment::factory()->create([
        'message_id' => $message->id,
        'file_url' => 'https://example.com/file.pdf',
        'file_type' => 'pdf',
    ]);

    expect($attachment->file_url)->toBe('https://example.com/file.pdf')
        ->and($attachment->file_type)->toBe('pdf')
        ->and($attachment->message_id)->toBe($message->id);
});

test('message attachment belongs to message', function () {
    $chat = \App\Models\Chat::factory()->create();
    $user = \App\Models\User::factory()->create();
    $chat->users()->attach($user->id);
    $message = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $user->id]);
    $attachment = MessageAttachment::factory()->create(['message_id' => $message->id]);

    expect($attachment->message)->toBeInstanceOf(Message::class)
        ->and($attachment->message->id)->toBe($message->id);
});
