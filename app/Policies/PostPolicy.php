<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine if the user can view any posts.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the post.
     */
    public function view(User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Determine if the user can create posts.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can update the post.
     */
    public function update(User $user, Post $post): bool
    {
        // User can update their own post, or admin can update any post
        return $user->id === $post->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can delete the post.
     */
    public function delete(User $user, Post $post): bool
    {
        // User can delete their own post, or admin can delete any post
        return $user->id === $post->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can restore the post.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can permanently delete the post.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }
}
