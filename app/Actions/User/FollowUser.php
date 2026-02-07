<?php

namespace App\Actions\User;

use App\Jobs\SendUserNotification;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FollowUser
{
    public function follow(User $userToFollow): bool
    {
        $currentUser = Auth::user();

        // Prevent users from following themselves
        if ($currentUser->id === $userToFollow->id) {
            throw new \Exception('You cannot follow yourself.');
        }

        // Check if already following
        if ($currentUser->following()->where('following_id', $userToFollow->id)->exists()) {
            throw new \Exception('You are already following this user.');
        }

        // Create follow relationship
        $currentUser->following()->attach($userToFollow->id);

        // Clear cache for both users
        $userRepository = app(UserRepository::class);
        $userRepository->clearUserCache($currentUser);
        $userRepository->clearUserCache($userToFollow);
        $userRepository->clearFollowCache($currentUser->id, $userToFollow->id);

        // Queue a notification for the user who was followed (sync connection = execute immediately)
        SendUserNotification::dispatch([
            'user_id' => $userToFollow->id,
            'source_user_id' => $currentUser->id,
            'type' => 'follow',
            'message' => "{$currentUser->name} started following you.",
        ])->onConnection('sync');

        return true;
    }

    public function unfollow(User $userToUnfollow): bool
    {
        $currentUser = Auth::user();

        // Check if actually following
        if (!$currentUser->following()->where('following_id', $userToUnfollow->id)->exists()) {
            throw new \Exception('You are not following this user.');
        }

        // Remove follow relationship
        $currentUser->following()->detach($userToUnfollow->id);

        // Clear cache for both users
        $userRepository = app(UserRepository::class);
        $userRepository->clearUserCache($currentUser);
        $userRepository->clearUserCache($userToUnfollow);
        $userRepository->clearFollowCache($currentUser->id, $userToUnfollow->id);

        return true;
    }

    public function isFollowing(User $user): bool
    {
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return false;
        }

        return $currentUser->following()->where('following_id', $user->id)->exists();
    }
}
