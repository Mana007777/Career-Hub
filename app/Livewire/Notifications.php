<?php

namespace App\Livewire;

use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Notifications extends Component
{
    use WithPagination;

    public $showNotifications = false;

    protected $listeners = ['openNotifications' => 'toggleNotifications'];

    public function toggleNotifications(): void
    {
        $this->showNotifications = !$this->showNotifications;
    }

    public function closeNotifications(): void
    {
        $this->showNotifications = false;
    }

    public function markAsRead(int $notificationId): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        $notification = UserNotification::where('user_id', $user->id)
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->update(['is_read' => true]);
            $this->dispatch('notificationsUpdated');
        }
    }

    public function markAllAsRead(): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        UserNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $this->dispatch('notificationsUpdated');
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

    public function render()
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

