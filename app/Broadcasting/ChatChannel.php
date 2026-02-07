<?php

namespace App\Broadcasting;

use App\Models\Chat;
use App\Models\User;

class ChatChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, $chatId): array|bool
    {
        $chat = Chat::find($chatId);
        
        if (!$chat) {
            return false;
        }
        
        // Check if user is a participant in this chat
        return $chat->users->contains($user->id);
    }
}
