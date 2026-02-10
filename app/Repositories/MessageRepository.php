<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class MessageRepository
{
    /**
     * Create a new message.
     * Simple operation - kept in repository.
     *
     * @param  array<string, mixed>  $data
     * @return Message
     */
    public function create(array $data): Message
    {
        return Message::create($data);
    }

    /**
     * Get messages for a chat with sender.
     * Simple query - kept in repository.
     *
     * @param  Chat  $chat
     * @param  int  $limit
     * @return Collection
     */
    public function getMessagesForChat(Chat $chat, int $limit = 100): Collection
    {
        return Message::where('chat_id', $chat->id)
            ->with(['sender', 'attachments'])
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Load sender relationship on message.
     * Simple operation - kept in repository.
     *
     * @param  Message  $message
     * @return Message
     */
    public function loadSender(Message $message): Message
    {
        return $message->load('sender');
    }
}
