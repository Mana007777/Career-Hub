<?php

namespace App\Livewire;

use App\Actions\Notification\MarkNotificationRead;
use App\Livewire\Listeners\OpenNotificationsListener;
use App\Models\UserNotification;
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

    public function getUnreadCountProperty(): int
    {
        $user = Auth::user();

        if (!$user) {
            return 0;
        }

        return UserNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    public function render(): View
    {
        $user = Auth::user();

        $notifications = collect();

        if ($user) {
            $notifications = UserNotification::with(['sourceUser', 'post'])
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        }

        return view('livewire.notifications', [
            'notifications' => $notifications,
        ]);
    }
}

