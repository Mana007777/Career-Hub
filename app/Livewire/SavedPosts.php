<?php

namespace App\Livewire;

use App\Actions\Post\SavePost;
use App\Models\Post;
use App\Models\SavedItem;
use App\Repositories\PostRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SavedPosts extends Component
{
    use WithPagination;

    public function togglePostSave(int $postId, SavePost $savePostAction, PostRepository $postRepository): void
    {
        try {
            $post = $postRepository->findById($postId);
            $savePostAction->toggle($post);

            // If a post was unsaved from this list, refresh pagination so it disappears
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update saved post. Please try again.');
        }
    }

    public function render(): View
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'You must be logged in to view saved posts.');
        }

        $posts = Post::query()
            ->select('posts.*')
            ->join('saved_items', function ($join) use ($user) {
                $join->on('saved_items.item_id', '=', 'posts.id')
                    ->where('saved_items.user_id', $user->id)
                    ->where('saved_items.item_type', Post::class);
            })
            ->with([
                'user',
                'stars',
                'comments',
                'specialties' => function ($query) {
                    $query->with('subSpecialties');
                },
                'tags',
                'suspension',
            ])
            ->whereDoesntHave('suspension')
            ->orderByDesc('saved_items.created_at')
            ->paginate(9);

        return view('livewire.saved-posts', [
            'posts' => $posts,
        ]);
    }
}


