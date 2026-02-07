<?php

namespace App\Repositories;

use App\Models\Message;
use App\Models\MessageRead;

class MessageReadRepository
{
    /**
     * Mark a message as read for a user.
     * Simple operation - kept in repository.
     *
     * @param  Message  $message
     * @param  int  $userId
     * @return MessageRead
     */
    public function markAsRead(Message $message, int $userId): MessageRead
    {
        return MessageRead::updateOrCreate(
            [
                'message_id' => $message->id,
                'user_id' => $userId,
            ],
            [
                'read_at' => now(),
            ]
        );
    }
}
