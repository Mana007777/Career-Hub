<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class UserRepository
{
    /**
     * Find a user by username with profile and counts.
     * Simple query - kept in repository.
     * Cached for 10 minutes as user data changes infrequently.
     *
     * @param  string  $username
     * @return User
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByUsernameWithCounts(string $username): User
    {
        return Cache::remember(
            "user:{$username}:with_counts",
            now()->addMinutes(10),
            function () use ($username) {
                return User::with('profile')
                    ->withCount(['followers', 'following', 'posts'])
                    ->where('username', $username)
                    ->firstOrFail();
            }
        );
    }

    /**
     * Get followers for a user with profile, excluding blocked users.
     * Simple query - kept in repository.
     * Cached for 5 minutes as followers list changes infrequently.
     *
     * @param  User  $user
     * @return Collection
     */
    public function getFollowersWithProfile(User $user): Collection
    {
        return Cache::remember(
            "user:{$user->id}:followers:with_profile:filtered",
            now()->addMinutes(5),
            function () use ($user) {
                // Get excluded user IDs (both blocked and blocked by)
                $blockedIds = \DB::table('blocks')
                    ->where('blocker_id', $user->id)
                    ->pluck('blocked_id')
                    ->toArray();
                
                $blockedByIds = \DB::table('blocks')
                    ->where('blocked_id', $user->id)
                    ->pluck('blocker_id')
                    ->toArray();
                
                $excludedIds = array_unique(array_merge($blockedIds, $blockedByIds));
                
                $query = $user->followers()->with('profile');
                
                if (!empty($excludedIds)) {
                    $query->whereNotIn('follower_id', $excludedIds);
                }
                
                return $query->get();
            }
        );
    }

    /**
     * Get following users with profile, excluding blocked users.
     * Simple query - kept in repository.
     * Cached for 5 minutes as following list changes infrequently.
     *
     * @param  User  $user
     * @return Collection
     */
    public function getFollowingWithProfile(User $user): Collection
    {
        return Cache::remember(
            "user:{$user->id}:following:with_profile:filtered",
            now()->addMinutes(5),
            function () use ($user) {
                // Get excluded user IDs (both blocked and blocked by)
                $blockedIds = \DB::table('blocks')
                    ->where('blocker_id', $user->id)
                    ->pluck('blocked_id')
                    ->toArray();
                
                $blockedByIds = \DB::table('blocks')
                    ->where('blocked_id', $user->id)
                    ->pluck('blocker_id')
                    ->toArray();
                
                $excludedIds = array_unique(array_merge($blockedIds, $blockedByIds));
                
                $query = $user->following()->with('profile');
                
                if (!empty($excludedIds)) {
                    $query->whereNotIn('following_id', $excludedIds);
                }
                
                return $query->get();
            }
        );
    }

    /**
     * Find a user by ID.
     * Simple query - kept in repository.
     *
     * @param  int  $id
     * @return User
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Get following user IDs for a user.
     * Simple query - kept in repository.
     * Cached for 5 minutes as following list changes infrequently.
     *
     * @param  User  $user
     * @return array<int>
     */
    public function getFollowingIds(User $user): array
    {
        return Cache::remember(
            "user:{$user->id}:following_ids",
            now()->addMinutes(5),
            function () use ($user) {
                return $user->following()->pluck('following_id')->toArray();
            }
        );
    }

    /**
     * Check if user follows another user.
     * Simple query - kept in repository.
     * Cached for 2 minutes as follow status can change frequently.
     *
     * @param  User  $currentUser
     * @param  User  $otherUser
     * @return bool
     */
    public function isFollowing(User $currentUser, User $otherUser): bool
    {
        return Cache::remember(
            "user:{$currentUser->id}:is_following:{$otherUser->id}",
            now()->addMinutes(2),
            function () use ($currentUser, $otherUser) {
                return $currentUser->following()->where('following_id', $otherUser->id)->exists();
            }
        );
    }

    /**
     * Check if user is followed back.
     * Simple query - kept in repository.
     * Cached for 2 minutes as follow status can change frequently.
     *
     * @param  User  $currentUser
     * @param  User  $otherUser
     * @return bool
     */
    public function isFollowedBack(User $currentUser, User $otherUser): bool
    {
        return Cache::remember(
            "user:{$currentUser->id}:is_followed_back:{$otherUser->id}",
            now()->addMinutes(2),
            function () use ($currentUser, $otherUser) {
                return $currentUser->followers()->where('follower_id', $otherUser->id)->exists();
            }
        );
    }

    /**
     * Clear cache for a user.
     * Call this when user data, followers, or following relationships change.
     *
     * @param  User  $user
     * @return void
     */
    public function clearUserCache(User $user): void
    {
        Cache::forget("user:{$user->username}:with_counts");
        Cache::forget("user:{$user->id}:followers:with_profile");
        Cache::forget("user:{$user->id}:followers:with_profile:filtered");
        Cache::forget("user:{$user->id}:following:with_profile");
        Cache::forget("user:{$user->id}:following:with_profile:filtered");
        Cache::forget("user:{$user->id}:following_ids");
        
        // Clear follow status caches (pattern-based clearing would require Redis SCAN)
        // For now, we'll rely on TTL expiration
    }

    /**
     * Clear follow relationship cache between two users.
     *
     * @param  int  $userId1
     * @param  int  $userId2
     * @return void
     */
    public function clearFollowCache(int $userId1, int $userId2): void
    {
        Cache::forget("user:{$userId1}:is_following:{$userId2}");
        Cache::forget("user:{$userId2}:is_following:{$userId1}");
        Cache::forget("user:{$userId1}:is_followed_back:{$userId2}");
        Cache::forget("user:{$userId2}:is_followed_back:{$userId1}");
    }
}
