<?php

namespace App\Actions\Notification;

use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;

class MarkNotificationRead
{
    public function markAsRead(int $notificationId): void
    {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception('User must be authenticated.');
        }

        $notification = UserNotification::where('user_id', $user->id)
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->update(['is_read' => true]);
        }
    }

    public function markAllAsRead(): void
    {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception('User must be authenticated.');
        }

        UserNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
