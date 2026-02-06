<?php

namespace App\Livewire;

use App\Actions\User\FollowUser;
use App\Models\Post as PostModel;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Services\PostService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserProfile extends Component
{
    use WithPagination;

    public $username;
    public $user;
    public $isFollowing = false;
    public $followersCount = 0;
    public $followingCount = 0;
    public $postsCount = 0;

    public function mount(string $username, FollowUser $followUserAction): void
    {
        // Remove @ if present
        $this->username = ltrim($username, '@');
        $this->loadUser($followUserAction);
    }

    protected function loadUser(FollowUser $followUserAction): void
    {
        $this->user = User::withCount(['followers', 'following', 'posts'])
            ->where('username', $this->username)
            ->firstOrFail();
        
        $this->followersCount = $this->user->followers_count;
        $this->followingCount = $this->user->following_count;
        $this->postsCount = $this->user->posts_count;

        if (Auth::check()) {
            $this->isFollowing = $followUserAction->isFollowing($this->user);
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

    public function getMediaUrl(PostModel $post): ?string
    {
        return app(PostService::class)->getMediaUrl($post);
    }

    public function render(PostRepository $postRepository): View
    {
        if (!$this->user) {
            abort(404, 'User not found');
        }

        $posts = $postRepository->getByUserId($this->user->id, 10);

        return view('livewire.user-profile', [
            'posts' => $posts,
        ]);
    }
}
