<?php

namespace App\Repositories;

use App\Models\UserNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationRepository
{
    /**
     * Get notifications for a user with relationships.
     * Simple query - kept in repository.
     *
     * @param  int  $userId
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getForUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return UserNotification::with(['sourceUser', 'post'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get unread count for a user.
     * Simple query - kept in repository.
     *
     * @param  int  $userId
     * @return int
     */
    public function getUnreadCount(int $userId): int
    {
        return UserNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
}
