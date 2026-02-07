<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        // Check if the authenticated user can delete this user
        // Users can delete themselves, or admins can delete any user
        $authenticatedUser = auth()->user();
        
        if ($authenticatedUser && $authenticatedUser->id !== $user->id) {
            Gate::authorize('delete', $user);
        }

        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
    }
}
