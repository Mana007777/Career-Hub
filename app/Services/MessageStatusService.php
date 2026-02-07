<?php

namespace App\Services;

use App\Events\MessageStatusUpdated;
use App\Models\Message;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class MessageStatusService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Update message status from 'sent' to 'delivered' when user comes online.
     * Called when a user's presence changes to online.
     *
     * @param  User  $user
     * @return void
     */
    public function updateSentToDeliveredForUser(User $user): void
    {
        // Get all messages sent to this user that are still 'sent'
        $chats = $user->chats()->get();
        
        foreach ($chats as $chat) {
            $otherUser = $chat->users->where('id', '!=', $user->id)->first();
            if (!$otherUser) {
                continue;
            }

            // Update messages sent by other user to this user that are still 'sent'
            $messages = Message::where('chat_id', $chat->id)
                ->where('sender_id', $otherUser->id)
                ->where('status', 'sent')
                ->get();

            foreach ($messages as $message) {
                $message->update(['status' => 'delivered']);
                broadcast(new MessageStatusUpdated($message));
            }
        }
    }
}
