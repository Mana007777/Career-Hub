<?php

namespace App\Observers;

use App\Models\Message;
use App\Models\NotificationSetting;
use App\Models\UserNotification;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {
        $chat = $message->chat;
        $sender = $message->sender;
        
        // Get chat participants (excluding sender)
        $participants = $chat->users()->where('users.id', '!=', $sender->id)->get();

        foreach ($participants as $participant) {
            $recipient = $participant->user;
            
            if ($recipient) {
                // Check notification settings
                $settings = NotificationSetting::firstOrCreate(
                    ['user_id' => $recipient->id],
                    ['follow' => true, 'like' => true, 'comment' => true, 'message' => true]
                );

                if ($settings->message) {
                    UserNotification::create([
                        'user_id' => $recipient->id,
                        'source_user_id' => $sender->id,
                        'type' => 'new_message',
                        'post_id' => null,
                        'message' => "New message from {$sender->name}: " . substr($message->message, 0, 50),
                        'is_read' => false,
                    ]);
                }
            }
        }

        // Event for Livewire real-time chat
        // Livewire chat components can listen and update in real-time
        // Use: $this->dispatch('message-received', ['message_id' => $message->id]);
    }

    /**
     * Handle the Message "updated" event.
     */
    public function updated(Message $message): void
    {
            // Event for Livewire (e.g., status change)
            // Components can update message status (sent, delivered, read)
    }

    /**
     * Handle the Message "deleted" event.
     */
    public function deleted(Message $message): void
    {
        // Event for Livewire components
        // Components can remove deleted message from chat UI
    }

    /**
     * Handle the Message "restored" event.
     */
    public function restored(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     */
    public function forceDeleted(Message $message): void
    {
        //
    }
}
