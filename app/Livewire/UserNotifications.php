<?php

namespace App\Livewire;

use App\Actions\Notification\MarkNotificationRead;
use App\Models\OrganizationMembership;
use App\Models\UserNotification;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserNotifications extends Component
{
    use WithPagination;

    public ?int $invitationNotificationId = null;
    public ?int $invitationMembershipId = null;
    public ?string $invitationCompanyName = null;
    public bool $showInvitationDecision = false;

    protected $listeners = [
        'notificationsUpdated' => '$refresh',
    ];

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

    public function openInvitationDecision(int $notificationId): void
    {
        $userId = Auth::id();
        if (! $userId) {
            return;
        }

        $notification = UserNotification::query()
            ->with('sourceUser')
            ->where('id', $notificationId)
            ->where('user_id', $userId)
            ->where('type', 'organization_invite')
            ->first();

        if (! $notification) {
            session()->flash('error', 'Invitation notification not found.');
            return;
        }

        $membership = OrganizationMembership::query()
            ->where('company_id', (int) $notification->source_user_id)
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->latest('id')
            ->first();

        if (! $membership) {
            session()->flash('error', 'This invitation is no longer pending.');
            return;
        }

        $this->invitationNotificationId = $notification->id;
        $this->invitationMembershipId = $membership->id;
        $this->invitationCompanyName = $notification->sourceUser?->name ?? 'Company';
        $this->showInvitationDecision = true;
    }

    public function closeInvitationDecision(): void
    {
        $this->showInvitationDecision = false;
        $this->invitationNotificationId = null;
        $this->invitationMembershipId = null;
        $this->invitationCompanyName = null;
    }

    public function acceptInvitation(): void
    {
        $this->handleInvitationDecision('accepted');
    }

    public function rejectInvitation(): void
    {
        $this->handleInvitationDecision('rejected');
    }

    private function handleInvitationDecision(string $decision): void
    {
        $userId = Auth::id();
        if (! $userId || ! $this->invitationMembershipId) {
            session()->flash('error', 'No active invitation selected.');
            return;
        }

        $membership = OrganizationMembership::query()
            ->where('id', $this->invitationMembershipId)
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->first();

        if (! $membership) {
            session()->flash('error', 'This invitation is no longer pending.');
            $this->closeInvitationDecision();
            return;
        }

        if ($decision === 'accepted') {
            $membership->status = 'accepted';
            $membership->accepted_at = now();
            $membership->rejected_at = null;
        } else {
            $membership->status = 'rejected';
            $membership->rejected_at = now();
            $membership->accepted_at = null;
        }
        $membership->save();

        if ($this->invitationNotificationId) {
            UserNotification::query()
                ->where('id', $this->invitationNotificationId)
                ->where('user_id', $userId)
                ->update(['is_read' => true]);
        }

        UserNotification::create([
            'user_id' => $membership->company_id,
            'source_user_id' => $userId,
            'type' => $decision === 'accepted' ? 'organization_invite_accepted' : 'organization_invite_rejected',
            'post_id' => null,
            'message' => sprintf(
                '%s has %s your organization invitation.',
                Auth::user()->name,
                $decision === 'accepted' ? 'accepted' : 'declined'
            ),
            'is_read' => false,
        ]);

        session()->flash('success', $decision === 'accepted' ? 'Invitation accepted.' : 'Invitation rejected.');
        $this->dispatch('notificationsUpdated');
        $this->closeInvitationDecision();
    }
}
