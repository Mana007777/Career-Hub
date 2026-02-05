<?php

namespace App\Livewire;

use App\Actions\User\FollowUser;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Services\PostService;
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

    public function mount($username)
    {
        // Remove @ if present
        $this->username = ltrim($username, '@');
        $this->loadUser();
    }

    protected function loadUser()
    {
        $this->user = User::where('username', $this->username)->firstOrFail();
        
        $this->followersCount = $this->user->followers()->count();
        $this->followingCount = $this->user->following()->count();
        $this->postsCount = $this->user->posts()->count();

        if (Auth::check()) {
            $followAction = new FollowUser();
            $this->isFollowing = $followAction->isFollowing($this->user);
        }
    }

    public function toggleFollow()
    {
        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to follow users.');
            return;
        }

        if (Auth::id() === $this->user->id) {
            session()->flash('error', 'You cannot follow yourself.');
            return;
        }

        try {
            $followAction = new FollowUser();
            
            if ($this->isFollowing) {
                $followAction->unfollow($this->user);
                $this->isFollowing = false;
                session()->flash('success', 'You have unfollowed ' . $this->user->name);
            } else {
                $followAction->follow($this->user);
                $this->isFollowing = true;
                session()->flash('success', 'You are now following ' . $this->user->name);
            }

            // Refresh counts
            $this->followersCount = $this->user->followers()->count();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function getMediaUrl($post)
    {
        $postService = new PostService(new PostRepository());
        return $postService->getMediaUrl($post);
    }

    public function render()
    {
        if (!$this->user) {
            abort(404, 'User not found');
        }

        $postRepository = new PostRepository();
        $posts = $postRepository->getByUserId($this->user->id, 10);

        return view('livewire.user-profile', [
            'posts' => $posts,
        ]);
    }
}
