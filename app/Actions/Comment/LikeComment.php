<?php

namespace App\Actions\Comment;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class LikeComment
{
    public function toggle(Comment $comment): bool
    {
        $userId = Auth::id();

        if (!$userId) {
            throw new \Exception('You must be logged in to like comments.');
        }

        $existing = $comment->likes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            return false; // Unlike
        } else {
            $comment->likes()->create(['user_id' => $userId]);
            return true; // Like
        }
    }
}
