<?php

namespace App\Actions\Post;

use App\Models\Post;
use App\Queries\PostQueries;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeletePost
{
    /**
     * Delete a post.
     *
     * @param  Post  $post
     * @return bool
     */
    public function delete(Post $post): bool
    {
        $this->authorize($post);

        // Delete associated media if exists
        if ($post->media) {
            Storage::disk('public')->delete($post->media);
        }

        $postId = $post->id;
        $userId = $post->user_id;

        // Clear post cache
        app(PostQueries::class)->clearPostCache($postId);
        
        // Clear user cache as post count changed
        $user = $post->user;
        if ($user) {
            app(UserRepository::class)->clearUserCache($user);
        }

        // Clear popular posts cache
        app(PostQueries::class)->clearAllPostCaches();

        return $post->delete();
    }

    /**
     * Authorize that the user can delete this post.
     *
     * @param  Post  $post
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function authorize(Post $post): void
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
