<?php

namespace App\Queries;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class MessageQueries
{
    /**
     * Get unread messages for a user in a chat.
     * Complex query with whereDoesntHave - kept in queries.
     *
     * @param  Chat  $chat
     * @param  int  $userId
     * @return Collection
     */
    public function getUnreadMessages(Chat $chat, int $userId): Collection
    {
        return Message::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $userId)
            ->whereDoesntHave('reads', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
    }

    /**
     * Get unread message count for a user in a chat.
     * Complex query with whereDoesntHave - kept in queries.
     * Cached for 1 minute as unread counts change frequently when messages are sent/read.
     *
     * @param  Chat  $chat
     * @param  int  $userId
     * @return int
     */
    public function getUnreadCount(Chat $chat, int $userId): int
    {
        return Cache::remember(
            "chat:{$chat->id}:user:{$userId}:unread_count",
            now()->addMinute(),
            function () use ($chat, $userId) {
                return Message::where('chat_id', $chat->id)
                    ->where('sender_id', '!=', $userId)
                    ->whereDoesntHave('reads', function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })
                    ->count();
            }
        );
    }

    /**
     * Clear unread count cache for a chat and user.
     * Call this when messages are sent or marked as read.
     *
     * @param  int  $chatId
     * @param  int  $userId
     * @return void
     */
    public function clearUnreadCountCache(int $chatId, int $userId): void
    {
        Cache::forget("chat:{$chatId}:user:{$userId}:unread_count");
    }
}
