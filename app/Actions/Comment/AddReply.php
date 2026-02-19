<?php

namespace App\Actions\Comment;

use App\Exceptions\AuthenticationRequiredException;
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

        

        return $reply;
    }
}
