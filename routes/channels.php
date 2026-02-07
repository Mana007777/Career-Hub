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

// Presence channel for user online/offline status
Broadcast::channel('presence.users', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
        'username' => $user->username,
    ];
});
