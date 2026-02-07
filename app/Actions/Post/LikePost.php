<?php

namespace App\Actions\Post;

use App\Jobs\SendUserNotification;
use App\Models\Post;
use App\Queries\PostQueries;
use Illuminate\Support\Facades\Auth;

class LikePost
{
    public function toggle(Post $post): bool
    {
        $userId = Auth::id();

        if (!$userId) {
            throw new \Exception('You must be logged in to like posts.');
        }

        $existing = $post->likes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            
            // Clear post cache as like count changed
            app(PostQueries::class)->clearPostCache($post->id);
            
            return false; // Unlike
        } else {
            $post->likes()->create(['user_id' => $userId]);

            // Clear post cache as like count changed
            app(PostQueries::class)->clearPostCache($post->id);

            // Notify post owner when someone likes their post
            if ($post->user_id !== $userId) {
                SendUserNotification::dispatch([
                    'user_id'        => $post->user_id,
                    'source_user_id' => $userId,
                    'type'           => 'post_liked',
                    'post_id'        => $post->id,
                    'message'        => Auth::user()->name . ' liked your post.',
                ])->onConnection('sync');
            }

            return true; // Like
        }
    }
}
