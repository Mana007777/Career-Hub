<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserPresenceChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $isOnline;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, bool $isOnline)
    {
        $this->user = $user;
        $this->isOnline = $isOnline;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('presence.users'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'user.presence.changed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'is_online' => $this->isOnline,
            'username' => $this->user->username,
            'name' => $this->user->name,
        ];
    }
}
