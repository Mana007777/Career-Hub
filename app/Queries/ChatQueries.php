<?php

namespace App\Queries;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ChatQueries
{
    /**
     * Find a chat between two users.
     * Complex query with multiple whereHas - kept in queries.
     *
     * @param  User  $currentUser
     * @param  User  $otherUser
     * @return Chat|null
     */
    public function findChatBetweenUsers(User $currentUser, User $otherUser): ?Chat
    {
        return Chat::whereHas('users', function ($query) use ($currentUser) {
            $query->where('users.id', $currentUser->id);
        })
        ->whereHas('users', function ($query) use ($otherUser) {
            $query->where('users.id', $otherUser->id);
        })
        ->where('is_group', false)
        ->with('users')
        ->first();
    }

    /**
     * Get all chats for a user with relationships.
     * Complex query with whereHas and nested relationships - kept in queries.
     * Cached for 5 minutes per user as chat list changes when new chats are created.
     *
     * @param  User  $user
     * @return Collection
     */
    public function getChatsForUser(User $user): Collection
    {
        return Cache::remember(
            "user:{$user->id}:chats:with_relationships",
            now()->addMinutes(5),
            function () use ($user) {
                return Chat::whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->where('is_group', false)
                ->with(['users', 'messages' => function ($query) {
                    $query->latest()->limit(1)->with('sender');
                }])
                ->get();
            }
        );
    }

    /**
     * Get chats for user with messages for unread count calculation.
     * Complex query with whereHas - kept in queries.
     * Cached for 2 minutes as chat list changes when new chats are created.
     *
     * @param  int  $userId
     * @return Collection
     */
    public function getChatsForUnreadCount(int $userId): Collection
    {
        return Cache::remember(
            "user:{$userId}:chats:for_unread_count",
            now()->addMinutes(2),
            function () use ($userId) {
                return Chat::whereHas('users', function ($query) use ($userId) {
                    $query->where('users.id', $userId);
                })
                ->where('is_group', false)
                ->get();
            }
        );
    }

    /**
     * Clear cache for a user's chats.
     * Call this when a new chat is created or chat relationships change.
     *
     * @param  int  $userId
     * @return void
     */
    public function clearUserChatCache(int $userId): void
    {
        Cache::forget("user:{$userId}:chats:with_relationships");
        Cache::forget("user:{$userId}:chats:for_unread_count");
    }
}
