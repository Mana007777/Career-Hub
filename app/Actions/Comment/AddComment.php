<?php

namespace App\Actions\Comment;

use App\Exceptions\AuthenticationRequiredException;
use App\Exceptions\CommentException;
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
            throw new AuthenticationRequiredException('You must be logged in to comment.');
        }

        $comment = Comment::create([
            'post_id'  => $post->id,
            'user_id'  => $userId,
            'parent_id'=> null,
            'content'  => trim($content),
        ]);

        
        app(PostQueries::class)->clearPostCache($post->id);

        

        return $comment;
    }
}
