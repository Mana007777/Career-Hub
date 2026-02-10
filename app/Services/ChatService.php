<?php

namespace App\Services;

use App\Events\MessageStatusUpdated;
use App\Events\UserPresenceChanged;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Queries\ChatQueries;
use App\Queries\MessageQueries;
use App\Repositories\ChatRepository;
use App\Repositories\ChatRequestRepository;
use App\Repositories\MessageReadRepository;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class ChatService
{
    protected ChatRepository $chatRepository;
    protected ChatQueries $chatQueries;
    protected MessageRepository $messageRepository;
    protected MessageQueries $messageQueries;
    protected MessageReadRepository $messageReadRepository;
    protected ChatRequestRepository $chatRequestRepository;
    protected UserRepository $userRepository;

    public function __construct(
        ChatRepository $chatRepository,
        ChatQueries $chatQueries,
        MessageRepository $messageRepository,
        MessageQueries $messageQueries,
        MessageReadRepository $messageReadRepository,
        ChatRequestRepository $chatRequestRepository,
        UserRepository $userRepository
    ) {
        $this->chatRepository = $chatRepository;
        $this->chatQueries = $chatQueries;
        $this->messageRepository = $messageRepository;
        $this->messageQueries = $messageQueries;
        $this->messageReadRepository = $messageReadRepository;
        $this->chatRequestRepository = $chatRequestRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get or create a chat between two users
     */
    public function getOrCreateChat(User $otherUser): Chat
    {
        $currentUser = Auth::user();

        // Check if a chat already exists between these two users
        $chat = $this->chatQueries->findChatBetweenUsers($currentUser, $otherUser);

        if (!$chat) {
            // Create a new chat
            DB::transaction(function () use ($currentUser, $otherUser, &$chat) {
                $chat = $this->chatRepository->create(['is_group' => false]);
                $this->chatRepository->attachUsers($chat, [$currentUser->id, $otherUser->id]);
                
                // Clear chat cache for both users
                $this->chatQueries->clearUserChatCache($currentUser->id);
                $this->chatQueries->clearUserChatCache($otherUser->id);
            });
        }

        return $this->chatRepository->loadUsers($chat);
    }

    /**
     * Send a message in a chat (optionally with attachments).
     */
    public function sendMessage(Chat $chat, string $message = '', array $attachments = []): Message
    {
        $currentUser = Auth::user();
        $otherUser = $chat->users->where('id', '!=', $currentUser->id)->first();
        
        // Check if this is a request scenario (one-way follow)
        $isFollowing = $this->isFollowing($currentUser, $otherUser);
        $isFollowedBack = $this->isFollowedBack($currentUser, $otherUser);
        
        // Determine initial message status based on recipient's online status
        $initialStatus = $otherUser->isActive() ? 'delivered' : 'sent';
        
        $messageModel = $this->messageRepository->create([
            'chat_id' => $chat->id,
            'sender_id' => Auth::id(),
            'message' => $message,
            'status' => $initialStatus,
        ]);

        // Persist any attachments (array of ['file_url' => ..., 'file_type' => ...])
        foreach ($attachments as $attachment) {
            if (! isset($attachment['file_url'], $attachment['file_type'])) {
                continue;
            }

            $messageModel->attachments()->create([
                'file_url' => $attachment['file_url'],
                'file_type' => $attachment['file_type'],
            ]);
        }

        // Clear unread count cache for the recipient
        $this->messageQueries->clearUnreadCountCache($chat->id, $otherUser->id);
        
        // Clear chat cache for both users as new message affects chat list
        $this->chatQueries->clearUserChatCache($currentUser->id);
        $this->chatQueries->clearUserChatCache($otherUser->id);

        // If user is following but not followed back, create a chat request
        if ($isFollowing && !$isFollowedBack) {
            $this->chatRequestRepository->updateOrCreate(
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

        // Broadcast message status update if delivered
        if ($initialStatus === 'delivered') {
            broadcast(new MessageStatusUpdated($messageModel));
        }

        return $this->messageRepository->loadSender($messageModel);
    }

    /**
     * Get messages for a chat
     */
    public function getMessages(Chat $chat, int $limit = 100)
    {
        return $this->messageRepository->getMessagesForChat($chat, $limit);
    }

    /**
     * Mark messages as read for a user in a chat
     */
    public function markMessagesAsRead(Chat $chat, int $userId): void
    {
        $unreadMessages = $this->messageQueries->getUnreadMessages($chat, $userId);

        foreach ($unreadMessages as $message) {
            $this->messageReadRepository->markAsRead($message, $userId);
        }
        
        // Update status of ALL messages sent TO this user (not from them) to 'seen'
        // This ensures that when user opens chat, all messages they received show as seen
        $messagesToUpdate = Message::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $userId) // Messages sent TO this user
            ->whereIn('status', ['sent', 'delivered']) // Only update sent/delivered messages
            ->get();

        foreach ($messagesToUpdate as $message) {
            $message->update(['status' => 'seen']);
            broadcast(new MessageStatusUpdated($message));
        }
        
        // Clear unread count cache
        $this->messageQueries->clearUnreadCountCache($chat->id, $userId);
    }

    /**
     * Get unread message count per user for the current user
     */
    public function getUnreadCountsPerUser(int $userId): array
    {
        $user = $this->userRepository->findById($userId);
        $chats = $this->chatQueries->getChatsForUser($user);

        $unreadCounts = [];

        foreach ($chats as $chat) {
            $otherUser = $chat->users->where('id', '!=', $userId)->first();
            if (!$otherUser) {
                continue;
            }

            $unreadCount = $this->messageQueries->getUnreadCount($chat, $userId);

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
        $chats = $this->chatQueries->getChatsForUnreadCount($userId);

        $totalUnread = 0;

        foreach ($chats as $chat) {
            $unreadCount = $this->messageQueries->getUnreadCount($chat, $userId);
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
        $followingIds = $this->userRepository->getFollowingIds($user);

        $chats = $this->chatQueries->getChatsForUser($user);

        return $chats
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
        
        return $this->chatRequestRepository->getPendingRequestsForUser($user->id);
    }

    /**
     * Find a chat request by ID
     */
    public function findChatRequest(int $requestId): ?\App\Models\ChatRequest
    {
        return $this->chatRequestRepository->findById($requestId);
    }

    /**
     * Get pending chat request between two users
     */
    public function getPendingRequest(int $fromUserId, int $toUserId): ?\App\Models\ChatRequest
    {
        return $this->chatRequestRepository->getPendingRequest($fromUserId, $toUserId);
    }

    /**
     * Check if user follows another user
     */
    public function isFollowing(User $currentUser, User $otherUser): bool
    {
        return $this->userRepository->isFollowing($currentUser, $otherUser);
    }

    /**
     * Check if user is followed back
     */
    public function isFollowedBack(User $currentUser, User $otherUser): bool
    {
        return $this->userRepository->isFollowedBack($currentUser, $otherUser);
    }
}
