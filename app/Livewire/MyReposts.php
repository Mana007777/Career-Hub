<?php

namespace App\Livewire;

use App\Models\Share;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyReposts extends Component
{
    use WithPagination;

    public function mount(): void
    {
        if (! Auth::user()?->isSeeker()) {
            $this->redirect(route('dashboard'));
        }
    }

    public function removeRepost(int $postId, UserRepository $userRepository): void
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        $deleted = Share::query()
            ->where('user_id', $user->id)
            ->where('post_id', $postId)
            ->delete();

        if ($deleted) {
            $userRepository->clearUserCache($user);
            session()->flash('success', __('Removed from your reposts.'));
        }

        $this->resetPage();
    }

    public function render(PostRepository $postRepository): View
    {
        $posts = $postRepository->getPostsRepostedByUserId((int) Auth::id(), 10);

        return view('livewire.my-reposts', [
            'posts' => $posts,
        ]);
    }
}
