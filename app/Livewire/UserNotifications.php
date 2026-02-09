<?php

namespace App\Livewire;

use App\Actions\Notification\MarkNotificationRead;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserNotifications extends Component
{
    use WithPagination;

    public function render(): View
    {
        $user = Auth::user();

        $notifications = collect();

        if ($user) {
            $notifications = app(NotificationRepository::class)->getForUser($user->id, 10);
        }

        return view('livewire.user-notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function getUnreadCountProperty(NotificationRepository $notificationRepository): int
    {
        $user = Auth::user();

        if (! $user) {
            return 0;
        }

        return $notificationRepository->getUnreadCount($user->id);
    }

    public function markAsRead(int $notificationId, MarkNotificationRead $markNotificationRead): void
    {
        try {
            $markNotificationRead->markAsRead($notificationId);
            $this->dispatch('notificationsUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to mark notification as read.');
        }
    }

    public function markAllAsRead(MarkNotificationRead $markNotificationRead): void
    {
        try {
            $markNotificationRead->markAllAsRead();
            $this->dispatch('notificationsUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to mark all notifications as read.');
        }
    }
}
