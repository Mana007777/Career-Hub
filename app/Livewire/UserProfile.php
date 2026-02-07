<?php

namespace App\Livewire;

use App\Actions\User\BlockUser;
use App\Actions\User\FollowUser;
use App\Models\Post as PostModel;
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

    public function mount(string $username, FollowUser $followUserAction, BlockUser $blockUserAction, UserRepository $userRepository): void
    {
        // Remove @ if present
        $this->username = ltrim($username, '@');
        $this->loadUser($followUserAction, $blockUserAction, $userRepository);
    }

    protected function loadUser(FollowUser $followUserAction, BlockUser $blockUserAction, UserRepository $userRepository): void
    {
        $this->user = $userRepository->findByUsernameWithCounts($this->username);
        
        if (Auth::check()) {
            $this->isBlockedBy = $blockUserAction->isBlockedBy($this->user);
            
            // If the profile owner has blocked the current user, show 404
            if ($this->isBlockedBy) {
                abort(404, 'User not found');
            }
            
            $this->isFollowing = $followUserAction->isFollowing($this->user);
            $this->isBlocked = $blockUserAction->isBlocked($this->user);
        }
        
        $this->followersCount = $this->user->followers_count;
        $this->followingCount = $this->user->following_count;
        $this->postsCount = $this->user->posts_count;
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
            $this->redirect(route('dashboard'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            session()->flash('error', 'You are not authorized to delete this user.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete user. Please try again.');
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
