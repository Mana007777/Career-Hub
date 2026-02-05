<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post as PostModel;
use App\Jobs\SendUserNotification;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostDetail extends Component
{
    public $slug;
    public $post;
    public $content = '';
    public $replyContent = [];
    public $showLikersModal = false;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->loadPost();
    }

    protected function loadPost()
    {
        // Extract ID from slug (format: slug-text-123)
        $parts = explode('-', $this->slug);
        $id = end($parts);

        if (is_numeric($id)) {
            $postService = app(PostService::class);
            $this->post = $postService->getPostById((int) $id);
            
            if (!$this->post) {
                abort(404, 'Post not found');
            }
        } else {
            abort(404, 'Invalid post slug');
        }
    }

    public function getMediaUrl($post)
    {
        $postService = app(PostService::class);
        return $postService->getMediaUrl($post);
    }

    public function togglePostLike(): void
    {
        if (!Auth::check() || !$this->post) {
            session()->flash('error', 'You must be logged in to like posts.');
            return;
        }

        $userId = Auth::id();
        $existing = $this->post->likes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            // no notification on unlike
        } else {
            $this->post->likes()->create(['user_id' => $userId]);

            // Notify post owner when someone likes their post
            if ($this->post->user_id !== $userId) {
                SendUserNotification::dispatch([
                    'user_id'        => $this->post->user_id,
                    'source_user_id' => $userId,
                    'type'           => 'post_liked',
                    'post_id'        => $this->post->id,
                    'message'        => Auth::user()->name . ' liked your post.',
                ])->onConnection('sync');
            }
        }

        $this->post->refresh()->loadMissing('likes.user', 'likedBy');
    }

    public function addComment(): void
    {
        if (!Auth::check() || !$this->post) {
            session()->flash('error', 'You must be logged in to comment.');
            return;
        }

        $text = trim($this->content);
        if ($text === '') {
            return;
        }

        Comment::create([
            'post_id'  => $this->post->id,
            'user_id'  => Auth::id(),
            'parent_id'=> null,
            'content'  => $text,
        ]);

        $this->content = '';
        $this->post->refresh()->loadMissing('comments.user', 'comments.likes', 'comments.replies.user', 'comments.replies.likes');

        // Notify post owner about a new comment
        if ($this->post->user_id !== Auth::id()) {
            SendUserNotification::dispatch([
                'user_id'        => $this->post->user_id,
                'source_user_id' => Auth::id(),
                'type'           => 'post_commented',
                'post_id'        => $this->post->id,
                'message'        => Auth::user()->name . ' commented on your post.',
            ])->onConnection('sync');
        }
    }

    public function addReply(int $parentId): void
    {
        if (!Auth::check() || !$this->post) {
            session()->flash('error', 'You must be logged in to reply.');
            return;
        }

        $text = trim($this->replyContent[$parentId] ?? '');
        if ($text === '') {
            return;
        }

        $reply = Comment::create([
            'post_id'   => $this->post->id,
            'user_id'   => Auth::id(),
            'parent_id' => $parentId,
            'content'   => $text,
        ]);

        $this->replyContent[$parentId] = '';
        $this->post->refresh()->loadMissing('comments.user', 'comments.likes', 'comments.replies.user', 'comments.replies.likes');

        // Notify parent comment owner about a reply
        $parent = Comment::find($parentId);
        if ($parent && $parent->user_id && $parent->user_id !== Auth::id()) {
            SendUserNotification::dispatch([
                'user_id'        => $parent->user_id,
                'source_user_id' => Auth::id(),
                'type'           => 'comment_replied',
                'post_id'        => $this->post->id,
                'message'        => Auth::user()->name . ' replied to your comment.',
            ])->onConnection('sync');
        }
    }

    public function toggleCommentLike(int $commentId): void
    {
        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to like comments.');
            return;
        }

        $comment = Comment::find($commentId);
        if (!$comment) {
            return;
        }

        $userId = Auth::id();
        $existing = $comment->likes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
        } else {
            $comment->likes()->create(['user_id' => $userId]);
        }

        $this->post->refresh()->loadMissing('comments.likes', 'comments.replies.likes');
    }

    public function toggleLikersModal(): void
    {
        $this->showLikersModal = !$this->showLikersModal;
    }

    public function render()
    {
        return view('livewire.post-detail');
    }
}
