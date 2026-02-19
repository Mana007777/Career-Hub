<?php

namespace App\Actions\Comment;

use App\Exceptions\AuthenticationRequiredException;
use App\Models\Comment;
use App\Models\CommentClap;
use Illuminate\Support\Facades\Auth;

class ClapComment
{
    /**
     * Toggle a clap for the given comment by the current user.
     *
     * @return bool  true if clapped, false if unclapped
     */
    public function toggle(Comment $comment): bool
    {
        $userId = Auth::id();

        if (! $userId) {
            throw new AuthenticationRequiredException('You must be logged in to clap comments.');
        }

        $existing = CommentClap::where('comment_id', $comment->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();
            return false; 
        }

        CommentClap::create([
            'comment_id' => $comment->id,
            'user_id' => $userId,
        ]);

        return true; 
    }
}

