<?php

namespace App\Actions\Comment;

use App\Jobs\SendUserNotification;
use App\Models\Comment;
use App\Models\Post;
use App\Queries\PostQueries;
use Illuminate\Support\Facades\Auth;

class AddComment
{
    public function create(Post $post, string $content): Comment
    {
        $userId = Auth::id();

        if (!$userId) {
            throw new \Exception('You must be logged in to comment.');
        }

        $comment = Comment::create([
            'post_id'  => $post->id,
            'user_id'  => $userId,
            'parent_id'=> null,
            'content'  => trim($content),
        ]);

        // Clear post cache as comment count changed
        app(PostQueries::class)->clearPostCache($post->id);

        // Notify post owner about a new comment
        if ($post->user_id !== $userId) {
            SendUserNotification::dispatch([
                'user_id'        => $post->user_id,
                'source_user_id' => $userId,
                'type'           => 'post_commented',
                'post_id'        => $post->id,
                'message'        => Auth::user()->name . ' commented on your post.',
            ])->onConnection('sync');
        }

        return $comment;
    }
}
