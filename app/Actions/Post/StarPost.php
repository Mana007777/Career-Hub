<?php

namespace App\Actions\Post;

use App\Exceptions\AuthenticationRequiredException;
use App\Jobs\SendUserNotification;
use App\Models\Post;
use App\Queries\PostQueries;
use Illuminate\Support\Facades\Auth;

class StarPost
{
    public function toggle(Post $post): bool
    {
        $userId = Auth::id();

        if (!$userId) {
            throw new AuthenticationRequiredException('You must be logged in to star posts.');
        }

        $existing = $post->stars()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            
            
            app(PostQueries::class)->clearPostCache($post->id);
            
            return false; 
        } else {
            $post->stars()->create(['user_id' => $userId]);

            
            app(PostQueries::class)->clearPostCache($post->id);

            
            if ($post->user_id !== $userId) {
                SendUserNotification::dispatchSync([
                    'user_id'        => $post->user_id,
                    'source_user_id' => $userId,
                    'type'           => 'post_starred',
                    'post_id'        => $post->id,
                    'message'        => Auth::user()->name . ' starred your post.',
                ]);
            }

            return true; 
        }
    }
}
