<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine if the user can view any comments.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the comment.
     */
    public function view(User $user, Comment $comment): bool
    {
        return true;
    }

    /**
     * Determine if the user can create comments.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can update the comment.
     */
    public function update(User $user, Comment $comment): bool
    {
        // User can update their own comment, or admin can update any comment
        return $user->id === $comment->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can delete the comment.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // User can delete their own comment, or admin can delete any comment
        return $user->id === $comment->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can restore the comment.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can permanently delete the comment.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->isAdmin();
    }
}
