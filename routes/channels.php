<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    $chat = \App\Models\Chat::find($chatId);
    
    if (!$chat) {
        return false;
    }
    
    // Check if user is a participant in this chat
    return $chat->users->contains($user->id) ? [
        'id' => $user->id,
        'name' => $user->name,
    ] : false;
});
