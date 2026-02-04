<?php

namespace App\Actions\Post;

use App\Models\Post;
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
