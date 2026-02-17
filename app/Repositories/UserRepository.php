<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
    public function findByUsernameWithCounts(string $username, bool $includeSuspended = false): User
    {
        $cacheKey = "user:{$username}:with_counts" . ($includeSuspended ? ':with_suspended' : '');
        
        return Cache::remember(
            $cacheKey,
            now()->addMinutes(10),
            function () use ($username, $includeSuspended) {
                $query = User::with(['profile', 'suspension'])
                    ->withCount(['followers', 'following', 'posts'])
                    ->where('username', $username);
                
                // Only filter out suspended users if not including them (for admins)
                if (!$includeSuspended) {
                    $query->whereDoesntHave('suspension', function($q) {
                        $q->where(function($query) {
                            $query->whereNull('expires_at')
                                ->orWhere('expires_at', '>', now());
                        });
                    });
                }
                
                return $query->firstOrFail();
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
        Cache::forget("user:{$user->username}:with_counts:with_suspended");
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

    /**
     * Search users by username or name.
     * Simple query - kept in repository.
     *
     * @param  string  $query
     * @param  int  $perPage
     * @param  int|null  $currentUserId  Current user ID to filter blocked users
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchUsers(string $query, int $perPage = 10, ?int $currentUserId = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $searchTerm = '%' . $query . '%';
        
        $userQuery = User::where(function($q) use ($searchTerm) {
                $q->where('username', 'like', $searchTerm)
                  ->orWhere('name', 'like', $searchTerm);
            })
            ->whereDoesntHave('suspension', function($q) {
                $q->where(function($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                });
            });
        
        // Filter out blocked users (bidirectional blocking)
        if ($currentUserId) {
            // Users the current user has blocked
            $blockedIds = DB::table('blocks')
                ->where('blocker_id', $currentUserId)
                ->pluck('blocked_id')
                ->toArray();
            
            // Users who have blocked the current user
            $blockedByIds = DB::table('blocks')
                ->where('blocked_id', $currentUserId)
                ->pluck('blocker_id')
                ->toArray();
            
            // Combine both arrays
            $excludedIds = array_unique(array_merge($blockedIds, $blockedByIds));
            
            if (!empty($excludedIds)) {
                $userQuery->whereNotIn('id', $excludedIds);
            }
        }
        
        return $userQuery->with('profile')
            ->orderBy('username')
            ->paginate($perPage);
    }
}
