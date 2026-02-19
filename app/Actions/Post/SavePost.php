<?php

namespace App\Actions\Post;

use App\Exceptions\AuthenticationRequiredException;
use App\Models\Post;
use App\Models\SavedItem;
use Illuminate\Support\Facades\Auth;

class SavePost
{
    /**
     * Toggle save/unsave for a post for the authenticated user.
     *
     * @throws \Exception
     */
    public function toggle(Post $post): bool
    {
        $userId = Auth::id();

        if (! $userId) {
            throw new AuthenticationRequiredException('You must be logged in to save posts.');
        }

        $existing = SavedItem::where('user_id', $userId)
            ->where('item_type', Post::class)
            ->where('item_id', $post->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return false; 

        SavedItem::create([
            'user_id' => $userId,
            'item_type' => Post::class,
            'item_id' => $post->id,
        ]);

        return true; 
    }
}

