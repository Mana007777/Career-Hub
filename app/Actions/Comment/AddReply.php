<?php

namespace App\Actions\Comment;

use App\Exceptions\AuthenticationRequiredException;
use App\Jobs\SendUserNotification;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class AddReply
{
    public function create(Post $post, int $parentId, string $content): Comment
    {
        $userId = Auth::id();

        if (!$userId) {
            throw new AuthenticationRequiredException('You must be logged in to reply.');
        }

        $reply = Comment::create([
            'post_id'   => $post->id,
            'user_id'   => $userId,
            'parent_id' => $parentId,
            'content'   => trim($content),
        ]);

        // Notify parent comment owner about a reply (queued on default queue, e.g. Redis)
        $parent = Comment::find($parentId);
        if ($parent && $parent->user_id && $parent->user_id !== $userId) {
            SendUserNotification::dispatch([
                'user_id'        => $parent->user_id,
                'source_user_id' => $userId,
                'type'           => 'comment_replied',
                'post_id'        => $post->id,
                'message'        => Auth::user()->name . ' replied to your comment.',
            ]);
        }

        return $reply;
    }
}
