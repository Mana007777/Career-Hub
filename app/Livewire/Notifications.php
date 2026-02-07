<?php

namespace App\Livewire;

use App\Actions\Notification\MarkNotificationRead;
use App\Livewire\Listeners\OpenNotificationsListener;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Notifications extends Component
{
    use WithPagination;

    public $showNotifications = false;

    protected $listeners = ['openNotifications' => 'handleOpenNotifications'];

    public function toggleNotifications(): void
    {
        $this->showNotifications = !$this->showNotifications;
    }

    public function closeNotifications(): void
    {
        $this->showNotifications = false;
    }

    public function markAsRead(int $notificationId, MarkNotificationRead $markNotificationRead): void
    {
        try {
            $markNotificationRead->markAsRead($notificationId);
            $this->dispatch('notificationsUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to mark notification as read. Please try again.');
        }
    }

    public function markAllAsRead(MarkNotificationRead $markNotificationRead): void
    {
        try {
            $markNotificationRead->markAllAsRead();
            $this->dispatch('notificationsUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to mark all notifications as read. Please try again.');
        }
    }

    public function handleOpenNotifications(): void
    {
        app(OpenNotificationsListener::class)->handle($this);
    }

    public function getUnreadCountProperty(NotificationRepository $notificationRepository): int
    {
        $user = Auth::user();

        if (!$user) {
            return 0;
        }

        return $notificationRepository->getUnreadCount($user->id);
    }

    public function render(NotificationRepository $notificationRepository): View
    {
        $user = Auth::user();

        $notifications = collect();

        if ($user) {
            $notifications = $notificationRepository->getForUser($user->id, 10);
        }

        return view('livewire.notifications', [
            'notifications' => $notifications,
        ]);
    }
}

