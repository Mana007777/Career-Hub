<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatService
{
    /**
     * Get or create a chat between two users
     */
    public function getOrCreateChat(User $otherUser): Chat
    {
        $currentUser = Auth::user();

        // Check if a chat already exists between these two users
        $chat = Chat::whereHas('users', function ($query) use ($currentUser) {
            $query->where('users.id', $currentUser->id);
        })
        ->whereHas('users', function ($query) use ($otherUser) {
            $query->where('users.id', $otherUser->id);
        })
        ->where('is_group', false)
        ->with('users')
        ->first();

        if (!$chat) {
            // Create a new chat
            DB::transaction(function () use ($currentUser, $otherUser, &$chat) {
                $chat = Chat::create(['is_group' => false]);
                $chat->users()->attach([$currentUser->id, $otherUser->id]);
            });
        }

        return $chat->load('users');
    }

    /**
     * Send a message in a chat
     */
    public function sendMessage(Chat $chat, string $message): Message
    {
        $currentUser = Auth::user();
        $otherUser = $chat->users->where('id', '!=', $currentUser->id)->first();
        
        // Check if this is a request scenario (one-way follow)
        $isFollowing = $currentUser->following()->where('following_id', $otherUser->id)->exists();
        $isFollowedBack = $currentUser->followers()->where('follower_id', $otherUser->id)->exists();
        
        $messageModel = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => Auth::id(),
            'message' => $message,
        ]);

        // If user is following but not followed back, create a chat request
        if ($isFollowing && !$isFollowedBack) {
            \App\Models\ChatRequest::updateOrCreate(
                [
                    'from_user_id' => $currentUser->id,
                    'to_user_id' => $otherUser->id,
                ],
                [
                    'message_id' => $messageModel->id,
                    'status' => 'pending',
                ]
            );
        }

        return $messageModel->load('sender');
    }

    /**
     * Get messages for a chat
     */
    public function getMessages(Chat $chat, int $limit = 100)
    {
        return Message::where('chat_id', $chat->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark messages as read for a user in a chat
     */
    public function markMessagesAsRead(Chat $chat, int $userId): void
    {
        $unreadMessages = Message::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $userId)
            ->whereDoesntHave('reads', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        foreach ($unreadMessages as $message) {
            \App\Models\MessageRead::updateOrCreate(
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

    /**
     * Get unread message count per user for the current user
     */
    public function getUnreadCountsPerUser(int $userId): array
    {
        $chats = Chat::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })
        ->where('is_group', false)
        ->with(['users', 'messages' => function ($query) {
            $query->latest()->limit(1);
        }])
        ->get();

        $unreadCounts = [];

        foreach ($chats as $chat) {
            $otherUser = $chat->users->where('id', '!=', $userId)->first();
            if (!$otherUser) {
                continue;
            }

            $unreadCount = Message::where('chat_id', $chat->id)
                ->where('sender_id', '!=', $userId)
                ->whereDoesntHave('reads', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->count();

            if ($unreadCount > 0) {
                $unreadCounts[$otherUser->id] = $unreadCount;
            }
        }

        return $unreadCounts;
    }

    /**
     * Get total unread message count
     */
    public function getTotalUnreadCount(int $userId): int
    {
        $chats = Chat::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })
        ->where('is_group', false)
        ->get();

        $totalUnread = 0;

        foreach ($chats as $chat) {
            $unreadCount = Message::where('chat_id', $chat->id)
                ->where('sender_id', '!=', $userId)
                ->whereDoesntHave('reads', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->count();

            $totalUnread += $unreadCount;
        }

        return $totalUnread;
    }

    /**
     * Get all chats for the current user (only with users they follow)
     */
    public function getUserChats()
    {
        $user = Auth::user();
        
        // Get IDs of users that the current user follows
        $followingIds = $user->following()->pluck('following_id')->toArray();

        return Chat::whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
        ->where('is_group', false)
        ->with(['users', 'messages' => function ($query) {
            $query->latest()->limit(1)->with('sender');
        }])
        ->get()
        ->map(function ($chat) use ($user) {
            $otherUser = $chat->users->where('id', '!=', $user->id)->first();
            $chat->other_user = $otherUser;
            return $chat;
        })
        ->filter(function ($chat) use ($user, $followingIds) {
            // Only show chats with users that the current user follows
            return $chat->other_user !== null && in_array($chat->other_user->id, $followingIds);
        })
        ->sortByDesc(function ($chat) {
            $lastMessage = $chat->messages->first();
            return $lastMessage ? $lastMessage->created_at : $chat->created_at;
        })
        ->values();
    }

    /**
     * Get pending chat requests for the current user
     */
    public function getPendingRequests()
    {
        $user = Auth::user();
        
        return \App\Models\ChatRequest::where('to_user_id', $user->id)
            ->where('status', 'pending')
            ->with(['fromUser', 'message.sender'])
            ->latest()
            ->get();
    }
}
