<?php

namespace App\Actions\User;

use App\Exceptions\UserFollowException;
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

       
        if ($currentUser->id === $userToFollow->id) {
            throw new UserFollowException('You cannot follow yourself.');
        }

        
        if ($currentUser->following()->where('following_id', $userToFollow->id)->exists()) {
            throw new UserFollowException('You are already following this user.');
        }

        
        $currentUser->following()->attach($userToFollow->id);

        
        $userRepository = app(UserRepository::class);
        $userRepository->clearUserCache($currentUser);
        $userRepository->clearUserCache($userToFollow);
        $userRepository->clearFollowCache($currentUser->id, $userToFollow->id);

        
        SendUserNotification::dispatchSync([
            'user_id' => $userToFollow->id,
            'source_user_id' => $currentUser->id,
            'type' => 'follow',
            'message' => "{$currentUser->name} started following you.",
        ]);

        return true;
    }

    public function unfollow(User $userToUnfollow): bool
    {
        $currentUser = Auth::user();

      
        if (!$currentUser->following()->where('following_id', $userToUnfollow->id)->exists()) {
            throw new UserFollowException('You are not following this user.');
        }

    
        $currentUser->following()->detach($userToUnfollow->id);

        
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
