<?php

namespace App\Livewire;

use App\Actions\User\BlockUser;
use App\Actions\User\EndorseUser;
use App\Actions\User\FollowUser;
use App\Models\Post as PostModel;
use App\Repositories\EndorsementRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use App\Services\PostService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserProfile extends Component
{
    use WithPagination, AuthorizesRequests;

    public $username;
    public $user;
    public $isFollowing = false;
    public $isBlocked = false;
    public $isBlockedBy = false;
    public $followersCount = 0;
    public $followingCount = 0;
    public $postsCount = 0;
    public $showFollowersModal = false;
    public $showFollowingModal = false;
    public $showAdminActionsModal = false;
    public $adminActionType = ''; // 'suspend', 'unsuspend', 'delete'
    public $suspendReason = '';
    public $suspendExpiresAt = null;
    public $organizationMemberships = [];
    public $pendingOrganizationInvitationId = null;
    public bool $viewerCompanyAlreadyMember = false;
    public $endorsementsBySkill = [];
    public $endorsableSkills = [];
    public $showEndorseModal = false;
    public $endorsementCount = 0;
    public $selectedSkillToEndorse = '';
    public $customSkill = '';

    public function mount(string $username, FollowUser $followUserAction, BlockUser $blockUserAction, UserRepository $userRepository): void
    {
        // Remove @ if present
        $this->username = ltrim($username, '@');
        $this->loadUser($followUserAction, $blockUserAction, $userRepository);
    }

    protected function loadUser(FollowUser $followUserAction, BlockUser $blockUserAction, UserRepository $userRepository): void
    {
        // Allow admins to see suspended users
        $includeSuspended = Auth::check() && Auth::user()->isAdmin();
        $this->user = $userRepository->findByUsernameWithCounts($this->username, $includeSuspended);
        
        // Ensure suspension relationship is loaded
        if (!$this->user->relationLoaded('suspension')) {
            $this->user->load('suspension');
        }
        
        if (Auth::check()) {
            $this->isBlockedBy = $blockUserAction->isBlockedBy($this->user);
            
            // If the profile owner has blocked the current user, show 404 (unless admin)
            if ($this->isBlockedBy && !Auth::user()->isAdmin()) {
                abort(404, 'User not found');
            }
            
            $this->isFollowing = $followUserAction->isFollowing($this->user);
            $this->isBlocked = $blockUserAction->isBlocked($this->user);
        }
        
        $this->followersCount = $this->user->followers_count;
        $this->followingCount = $this->user->following_count;
        $this->postsCount = $this->user->posts_count;

        $endorsementRepository = app(EndorsementRepository::class);
        $this->endorsementsBySkill = $endorsementRepository->getEndorsementsBySkillForUser($this->user)->all();
        $this->endorsementCount = $endorsementRepository->getEndorsementCountForUser($this->user);

        if (Auth::check() && Auth::id() !== $this->user->id) {
            $this->endorsableSkills = app(EndorseUser::class)->getEndorsableSkills($this->user)->all();
        }
        
        // Preload organizations the user belongs to
        $this->user->loadMissing(['organizations']);
        $this->organizationMemberships = $this->user->organizations;
        
        // Preload pending invitation for current viewer (if viewer is a company)
        if (Auth::check() && Auth::user()->isCompany() && Auth::id() !== $this->user->id) {
            $companyId = Auth::id();

            // Check if this user is already a member of the viewer company
            $this->viewerCompanyAlreadyMember = $this->organizationMemberships->contains('id', $companyId);

            $pending = \App\Models\OrganizationMembership::where('company_id', $companyId)
                ->where('user_id', $this->user->id)
                ->where('status', 'pending')
                ->first();
            $this->pendingOrganizationInvitationId = $pending?->id;
        }
    }

    public function toggleFollow(FollowUser $followUserAction): void
    {
        try {
            if ($this->isFollowing) {
                $followUserAction->unfollow($this->user);
                $this->isFollowing = false;
                session()->flash('success', 'You have unfollowed ' . $this->user->name);
            } else {
                $followUserAction->follow($this->user);
                $this->isFollowing = true;
                session()->flash('success', 'You are now following ' . $this->user->name);
            }

            // Refresh counts using withCount
            $this->user->loadCount('followers');
            $this->followersCount = $this->user->followers_count;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update follow status. Please try again.');
        }
    }

    /**
     * Company invites this user to join their organization.
     */
    public function inviteToOrganization(): void
    {
        if (!Auth::check() || !Auth::user()->isCompany()) {
            session()->flash('error', 'Only company accounts can send organization invitations.');
            return;
        }

        if (!$this->user) {
            session()->flash('error', 'User not found.');
            return;
        }

        if (Auth::id() === $this->user->id) {
            session()->flash('error', 'You cannot invite yourself.');
            return;
        }

        try {
            $companyId = Auth::id();
            $membership = \App\Models\OrganizationMembership::firstOrCreate(
                [
                    'company_id' => $companyId,
                    'user_id' => $this->user->id,
                ],
                [
                    'status' => 'pending',
                    'invited_by' => $companyId,
                ]
            );

            // If already accepted, do not change it or resend an invite
            if ($membership->status === 'accepted') {
                session()->flash('success', 'This user is already a member of your organization.');
                $this->viewerCompanyAlreadyMember = true;
                $this->pendingOrganizationInvitationId = null;
                return;
            }

            // If it was previously rejected, reset to pending
            if ($membership->status !== 'pending') {
                $membership->status = 'pending';
                $membership->invited_by = $companyId;
                $membership->accepted_at = null;
                $membership->rejected_at = null;
                $membership->save();
            }

            $this->pendingOrganizationInvitationId = $membership->id;

            // Notify the user about the invitation
            \App\Models\UserNotification::create([
                'user_id' => $this->user->id,
                'source_user_id' => $companyId,
                'type' => 'organization_invite',
                'post_id' => null,
                'message' => sprintf('%s has invited you to join their organization.', Auth::user()->name),
                'is_read' => false,
            ]);

            session()->flash('success', 'Invitation sent successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send invitation: ' . $e->getMessage());
        }
    }

    /**
     * User accepts an organization invitation (from their own profile).
     */
    public function acceptOrganizationInvite(int $membershipId): void
    {
        if (!Auth::check() || !Auth::user()) {
            session()->flash('error', 'You must be logged in to accept invitations.');
            return;
        }

        try {
            $membership = \App\Models\OrganizationMembership::where('id', $membershipId)
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->firstOrFail();

            $membership->status = 'accepted';
            $membership->accepted_at = now();
            $membership->rejected_at = null;
            $membership->save();

            // Refresh organizations on profile
            $this->user->load('organizations');
            $this->organizationMemberships = $this->user->organizations;

            // Notify the company that user accepted
            if ($membership->company_id) {
                \App\Models\UserNotification::create([
                    'user_id' => $membership->company_id,
                    'source_user_id' => Auth::id(),
                    'type' => 'organization_invite_accepted',
                    'post_id' => null,
                    'message' => sprintf('%s has accepted your organization invitation.', Auth::user()->name),
                    'is_read' => false,
                ]);
            }

            // Clear pending state for this viewer if relevant
            if (Auth::user()->isCompany() && Auth::id() === $membership->company_id) {
                $this->pendingOrganizationInvitationId = null;
            }

            session()->flash('success', 'Invitation accepted.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to accept invitation: ' . $e->getMessage());
        }
    }

    /**
     * User rejects an organization invitation.
     */
    public function rejectOrganizationInvite(int $membershipId): void
    {
        if (!Auth::check() || !Auth::user()) {
            session()->flash('error', 'You must be logged in to reject invitations.');
            return;
        }

        try {
            $membership = \App\Models\OrganizationMembership::where('id', $membershipId)
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->firstOrFail();

            $membership->status = 'rejected';
            $membership->rejected_at = now();
            $membership->accepted_at = null;
            $membership->save();

            // Optionally notify the company
            if ($membership->company_id) {
                \App\Models\UserNotification::create([
                    'user_id' => $membership->company_id,
                    'source_user_id' => Auth::id(),
                    'type' => 'organization_invite_rejected',
                    'post_id' => null,
                    'message' => sprintf('%s has declined your organization invitation.', Auth::user()->name),
                    'is_read' => false,
                ]);
            }

            session()->flash('success', 'Invitation rejected.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reject invitation: ' . $e->getMessage());
        }
    }

    public function getMediaUrl(PostModel $post): ?string
    {
        return app(PostService::class)->getMediaUrl($post);
    }

    public function openFollowersModal(UserRepository $userRepository): void
    {
        if (!$this->user) {
            return;
        }

        // Load followers with profile using repository
        $this->user->setRelation('followers', $userRepository->getFollowersWithProfile($this->user));
        
        $this->showFollowersModal = true;
    }

    public function openFollowingModal(UserRepository $userRepository): void
    {
        if (!$this->user) {
            return;
        }

        // Load following with profile using repository
        $this->user->setRelation('following', $userRepository->getFollowingWithProfile($this->user));
        
        $this->showFollowingModal = true;
    }

    public function closeFollowersModal(): void
    {
        $this->showFollowersModal = false;
    }

    public function closeFollowingModal(): void
    {
        $this->showFollowingModal = false;
    }

    public function openEndorseModal(EndorseUser $endorseUserAction): void
    {
        if (!Auth::check() || Auth::id() === $this->user->id) {
            return;
        }
        $this->endorsableSkills = $endorseUserAction->getEndorsableSkills($this->user)->all();
        $this->selectedSkillToEndorse = $this->endorsableSkills[0] ?? '';
        $this->customSkill = '';
        $this->showEndorseModal = true;
    }

    public function closeEndorseModal(): void
    {
        $this->showEndorseModal = false;
        $this->selectedSkillToEndorse = '';
        $this->customSkill = '';
    }

    public function endorseUser(EndorseUser $endorseUserAction, EndorsementRepository $endorsementRepository): void
    {
        try {
            if (!Auth::check() || Auth::id() === $this->user->id) {
                session()->flash('error', 'You cannot endorse yourself.');
                return;
            }
            $skill = trim($this->customSkill ?: $this->selectedSkillToEndorse ?: ($this->endorsableSkills[0] ?? ''));
            if (empty($skill)) {
                session()->flash('error', 'Please select or enter a skill to endorse.');
                return;
            }
            $endorseUserAction->endorse($this->user, $skill);
            $this->endorsementsBySkill = $endorsementRepository->getEndorsementsBySkillForUser($this->user)->all();
            $this->endorsementCount = $endorsementRepository->getEndorsementCountForUser($this->user);
            $this->endorsableSkills = $endorseUserAction->getEndorsableSkills($this->user)->all();
            $this->customSkill = '';
            $this->closeEndorseModal();
            session()->flash('success', "You endorsed {$this->user->name} for {$skill}.");
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function removeEndorsement(string $skill, EndorseUser $endorseUserAction, EndorsementRepository $endorsementRepository): void
    {
        try {
            if (!Auth::check()) {
                session()->flash('error', 'You must be logged in to remove an endorsement.');
                return;
            }
            $endorseUserAction->removeEndorsement($this->user, $skill);
            $this->endorsementsBySkill = $endorsementRepository->getEndorsementsBySkillForUser($this->user)->all();
            $this->endorsementCount = $endorsementRepository->getEndorsementCountForUser($this->user);
            $this->endorsableSkills = $endorseUserAction->getEndorsableSkills($this->user)->all();
            session()->flash('success', "Endorsement for {$skill} removed.");
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function toggleBlock(BlockUser $blockUserAction): void
    {
        try {
            if ($this->isBlocked) {
                $blockUserAction->unblock($this->user);
                $this->isBlocked = false;
                session()->flash('success', 'You have unblocked ' . $this->user->name);
            } else {
                $blockUserAction->block($this->user);
                $this->isBlocked = true;
                $this->isFollowing = false; // Unfollow when blocking
                session()->flash('success', 'You have blocked ' . $this->user->name);
            }

            // Refresh the user data
            $this->loadUser(app(FollowUser::class), $blockUserAction, app(UserRepository::class));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function deleteUserAsAdmin(int $userId): void
    {
        try {
            $userToDelete = \App\Models\User::findOrFail($userId);
            
            // Use policy for authorization
            $this->authorize('delete', $userToDelete);
            
            // Delete the user using Jetstream's DeleteUser action
            app(\Laravel\Jetstream\Contracts\DeletesUsers::class)->delete($userToDelete);
            
            session()->flash('success', 'User deleted successfully!');
            $this->closeAdminActionsModal();
            $this->redirect(route('dashboard'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            session()->flash('error', 'You are not authorized to delete this user.');
            $this->closeAdminActionsModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete user. Please try again.');
            $this->closeAdminActionsModal();
        }
    }

    public function openAdminActionsModal(string $action): void
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            session()->flash('error', 'You are not authorized to perform this action.');
            return;
        }

        $this->adminActionType = $action;
        
        if ($action === 'suspend') {
            $this->suspendReason = '';
            $this->suspendExpiresAt = null;
        }
        
        $this->showAdminActionsModal = true;
    }

    public function openSuspendUserModal(): void
    {
        $this->openAdminActionsModal('suspend');
    }

    public function openUnsuspendUserModal(): void
    {
        $this->openAdminActionsModal('unsuspend');
    }

    public function openDeleteUserModal(): void
    {
        $this->openAdminActionsModal('delete');
    }

    public function closeAdminActionsModal(): void
    {
        $this->showAdminActionsModal = false;
        $this->adminActionType = '';
        $this->suspendReason = '';
        $this->suspendExpiresAt = null;
    }

    public function openSuspendModal(): void
    {
        try {
            if (!Auth::check()) {
                session()->flash('error', 'You must be logged in to perform this action.');
                return;
            }

            if (!Auth::user()->isAdmin()) {
                session()->flash('error', 'You are not authorized to suspend users.');
                return;
            }

            // Reload user to ensure we have fresh data
            if (!$this->user || !$this->user->id) {
                $this->loadUser(app(FollowUser::class), app(\App\Actions\User\BlockUser::class), app(UserRepository::class));
            }

            if (!$this->user) {
                session()->flash('error', 'User not found.');
                return;
            }

            // Ensure user is fresh and load suspension relationship
            $this->user->refresh();
            if (!$this->user->relationLoaded('suspension')) {
                $this->user->load('suspension');
            }

            $this->suspendReason = '';
            $this->suspendExpiresAt = null;
            $this->showSuspendModal = true;
            
            // Debug logging
            \Log::info('Suspend modal opened', [
                'user_id' => $this->user->id,
                'admin_id' => Auth::id(),
                'showSuspendModal' => $this->showSuspendModal,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error opening suspend modal: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $this->user->id ?? null,
                'admin_id' => Auth::id(),
            ]);
            session()->flash('error', 'Failed to load suspension form. Please try again.');
        }
    }

    public function closeSuspendModal(): void
    {
        $this->showSuspendModal = false;
        $this->suspendReason = '';
        $this->suspendExpiresAt = null;
    }

    public function suspendUser(): void
    {
        try {
            \Log::info('suspendUser method called', [
                'user_id' => $this->user->id ?? null,
                'admin_id' => Auth::id(),
                'suspendReason' => $this->suspendReason,
                'suspendExpiresAt' => $this->suspendExpiresAt,
            ]);

            if (!$this->user) {
                session()->flash('error', 'User not found.');
                \Log::error('User not found in suspendUser');
                return;
            }

            if (!Auth::check() || !Auth::user()->isAdmin()) {
                session()->flash('error', 'You are not authorized to suspend users.');
                \Log::error('Not authorized to suspend user', ['admin_id' => Auth::id()]);
                return;
            }

            // Normalize empty string to null for expires_at
            if (empty($this->suspendExpiresAt) || $this->suspendExpiresAt === '') {
                $this->suspendExpiresAt = null;
            }

            $rules = [
                'suspendReason' => 'required|string|max:1000',
            ];
            
            $messages = [
                'suspendReason.required' => 'Please provide a reason for suspending this user.',
                'suspendReason.max' => 'The reason cannot exceed 1000 characters.',
            ];

            // Only validate expires_at if it's not null
            if ($this->suspendExpiresAt !== null) {
                $rules['suspendExpiresAt'] = 'required|date|after:now';
                $messages['suspendExpiresAt.required'] = 'If provided, expiration date is required.';
                $messages['suspendExpiresAt.after'] = 'The expiration date must be in the future.';
            }

            $this->validate($rules, $messages);

            $expiresAt = null;
            if (!empty($this->suspendExpiresAt)) {
                try {
                    // Handle datetime-local format (Y-m-d\TH:i)
                    $expiresAt = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $this->suspendExpiresAt);
                } catch (\Exception $e) {
                    // Try alternative format
                    try {
                        $expiresAt = \Carbon\Carbon::parse($this->suspendExpiresAt);
                    } catch (\Exception $e2) {
                        \Log::error('Failed to parse expiration date', [
                            'date' => $this->suspendExpiresAt,
                            'error' => $e2->getMessage(),
                        ]);
                        throw new \App\Exceptions\InvalidExpirationDateException('Invalid expiration date format.');
                    }
                }
            }

            $suspension = \App\Models\UserSuspension::updateOrCreate(
                ['user_id' => $this->user->id],
                [
                    'reason' => trim($this->suspendReason),
                    'expires_at' => $expiresAt,
                ]
            );

            \Log::info('User suspension created', [
                'suspension_id' => $suspension->user_id,
                'reason' => $suspension->reason,
                'expires_at' => $suspension->expires_at,
            ]);

            // Log admin action
            \App\Models\AdminLog::create([
                'admin_id' => Auth::id(),
                'action' => 'Suspended user: ' . $this->user->name . ' - Reason: ' . $this->suspendReason,
                'target_type' => \App\Models\User::class,
                'target_id' => $this->user->id,
            ]);

            session()->flash('success', 'User suspended successfully!');
            $this->closeAdminActionsModal();
            $this->loadUser(app(FollowUser::class), app(\App\Actions\User\BlockUser::class), app(UserRepository::class));
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in suspendUser', [
                'errors' => $e->errors(),
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error in suspendUser: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Failed to suspend user: ' . $e->getMessage());
        }
    }

    public function unsuspendUser(): void
    {
        try {
            if (!$this->user) {
                session()->flash('error', 'User not found.');
                return;
            }

            if (!Auth::check() || !Auth::user()->isAdmin()) {
                session()->flash('error', 'You are not authorized to unsuspend users.');
                return;
            }

            $this->user->suspension?->delete();

            // Log admin action
            \App\Models\AdminLog::create([
                'admin_id' => Auth::id(),
                'action' => 'Unsuspended user: ' . $this->user->name,
                'target_type' => \App\Models\User::class,
                'target_id' => $this->user->id,
            ]);

            session()->flash('success', 'User unsuspended successfully!');
            $this->closeAdminActionsModal();
            $this->loadUser(app(FollowUser::class), app(\App\Actions\User\BlockUser::class), app(UserRepository::class));
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to unsuspend user. Please try again.');
        }
    }

    public function handleAdminAction(): void
    {
        if ($this->adminActionType === 'suspend') {
            $this->suspendUser();
        } elseif ($this->adminActionType === 'unsuspend') {
            $this->unsuspendUser();
        } elseif ($this->adminActionType === 'delete') {
            $this->deleteUserAsAdmin($this->user->id);
        }
    }

    public function render(PostRepository $postRepository): View
    {
        if (!$this->user) {
            abort(404, 'User not found');
        }

        // Don't show posts if user is blocked or has blocked the current user
        $posts = null;
        if (!($this->isBlocked || $this->isBlockedBy)) {
            $posts = $postRepository->getByUserId($this->user->id, 10);
        }

        return view('livewire.user-profile', [
            'posts' => $posts,
        ]);
    }
}
