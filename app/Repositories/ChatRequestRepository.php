<?php

namespace App\Repositories;

use App\Models\ChatRequest;
use Illuminate\Database\Eloquent\Collection;

class ChatRequestRepository
{
    /**
     * Find a chat request by ID.
     * Simple query - kept in repository.
     *
     * @param  int  $id
     * @return ChatRequest|null
     */
    public function findById(int $id): ?ChatRequest
    {
        return ChatRequest::find($id);
    }

    /**
     * Get pending chat requests for a user.
     * Simple query - kept in repository.
     *
     * @param  int  $userId
     * @return Collection
     */
    public function getPendingRequestsForUser(int $userId): Collection
    {
        return ChatRequest::where('to_user_id', $userId)
            ->where('status', 'pending')
            ->with(['fromUser', 'message.sender'])
            ->latest()
            ->get();
    }

    /**
     * Get pending chat request between two users.
     * Simple query - kept in repository.
     *
     * @param  int  $fromUserId
     * @param  int  $toUserId
     * @return ChatRequest|null
     */
    public function getPendingRequest(int $fromUserId, int $toUserId): ?ChatRequest
    {
        return ChatRequest::where('from_user_id', $fromUserId)
            ->where('to_user_id', $toUserId)
            ->where('status', 'pending')
            ->with('message')
            ->latest()
            ->first();
    }

    /**
     * Create or update a chat request.
     * Simple operation - kept in repository.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $values
     * @return ChatRequest
     */
    public function updateOrCreate(array $attributes, array $values): ChatRequest
    {
        return ChatRequest::updateOrCreate($attributes, $values);
    }
}
