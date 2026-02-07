<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the user.
     */
    public function view(User $user, User $model): bool
    {
        return true;
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user): bool
    {
        return true; // Registration is open to all
    }

    /**
     * Determine if the user can update the user.
     */
    public function update(User $user, User $model): bool
    {
        // User can update their own profile, or admin can update any user
        return $user->id === $model->id || $user->isAdmin();
    }

    /**
     * Determine if the user can delete the user.
     */
    public function delete(User $user, User $model): bool
    {
        // Only admin can delete users (and cannot delete themselves)
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine if the user can restore the user.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can permanently delete the user.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Only admin can force delete users (and cannot delete themselves)
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine if the user can suspend the user.
     */
    public function suspend(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine if the user can view admin panel.
     */
    public function viewAdminPanel(User $user): bool
    {
        return $user->isAdmin();
    }
}
