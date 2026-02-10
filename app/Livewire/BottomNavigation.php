<?php

namespace App\Livewire;

use App\Models\Report;
use App\Services\ChatService;
use App\Repositories\NotificationRepository;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BottomNavigation extends Component
{
    public int $totalUnreadMessages = 0;
    public int $pendingReportsCount = 0;
    public int $savedPostsCount = 0;
    public int $unreadNotifications = 0;

    protected $listeners = [
        'unread-counts-updated' => 'loadData',
        'notificationsUpdated' => 'loadData',
    ];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(NotificationRepository $notificationRepository = null): void
    {
        $user = Auth::user();

        if ($user) {
            // Load unread chat messages count
            $chatService = app(ChatService::class);
            $this->totalUnreadMessages = $chatService->getTotalUnreadCount($user->id);

            // Load count of saved posts (bookmarks)
            $this->savedPostsCount = $user->savedItems()
                ->where('item_type', \App\Models\Post::class)
                ->count();

            // Load pending reports count (admin only)
            if ($user->isAdmin()) {
                $this->pendingReportsCount = Report::where('status', 'pending')->count();
            }

            // Load unread notifications count
            $notificationRepository = $notificationRepository ?? app(NotificationRepository::class);
            $this->unreadNotifications = $notificationRepository->getUnreadCount($user->id);
        }
    }

    public function render()
    {
        return view('livewire.bottom-navigation');
    }
}
