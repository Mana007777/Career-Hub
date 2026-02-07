<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class BlockRepository
{
    /**
     * Get all users blocked by the current user.
     * Simple query - kept in repository.
     * Cached for 5 minutes as blocked list changes infrequently.
     *
     * @param  int  $userId
     * @return Collection
     */
    public function getBlockedUsers(int $userId): Collection
    {
        return Cache::remember(
            "user:{$userId}:blocked_users",
            now()->addMinutes(5),
            function () use ($userId) {
                $user = User::findOrFail($userId);
                return $user->blockedUsers()
                    ->with('profile')
                    ->get();
            }
        );
    }

    /**
     * Check if user A has blocked user B.
     * Simple query - kept in repository.
     *
     * @param  int  $blockerId
     * @param  int  $blockedId
     * @return bool
     */
    public function isBlocked(int $blockerId, int $blockedId): bool
    {
        return Cache::remember(
            "block:{$blockerId}:{$blockedId}",
            now()->addMinutes(5),
            function () use ($blockerId, $blockedId) {
                return \DB::table('blocks')
                    ->where('blocker_id', $blockerId)
                    ->where('blocked_id', $blockedId)
                    ->exists();
            }
        );
    }

    /**
     * Get IDs of users blocked by the given user.
     *
     * @param  int  $userId
     * @return array
     */
    public function getBlockedUserIds(int $userId): array
    {
        return Cache::remember(
            "user:{$userId}:blocked_user_ids",
            now()->addMinutes(5),
            function () use ($userId) {
                return \DB::table('blocks')
                    ->where('blocker_id', $userId)
                    ->pluck('blocked_id')
                    ->toArray();
            }
        );
    }

    /**
     * Get IDs of users who have blocked the given user.
     *
     * @param  int  $userId
     * @return array
     */
    public function getBlockedByUserIds(int $userId): array
    {
        return Cache::remember(
            "user:{$userId}:blocked_by_user_ids",
            now()->addMinutes(5),
            function () use ($userId) {
                return \DB::table('blocks')
                    ->where('blocked_id', $userId)
                    ->pluck('blocker_id')
                    ->toArray();
            }
        );
    }

    /**
     * Clear block-related cache for a user.
     *
     * @param  int  $userId
     * @return void
     */
    public function clearBlockCache(int $userId): void
    {
        Cache::forget("user:{$userId}:blocked_users");
        Cache::forget("user:{$userId}:blocked_user_ids");
        Cache::forget("user:{$userId}:blocked_by_user_ids");
        
        // Clear all block checks for this user
        $blockedIds = \DB::table('blocks')
            ->where('blocker_id', $userId)
            ->pluck('blocked_id');
        
        foreach ($blockedIds as $blockedId) {
            Cache::forget("block:{$userId}:{$blockedId}");
            // Also clear the other user's blocked_by cache
            Cache::forget("user:{$blockedId}:blocked_by_user_ids");
        }
        
        $blockerIds = \DB::table('blocks')
            ->where('blocked_id', $userId)
            ->pluck('blocker_id');
        
        foreach ($blockerIds as $blockerId) {
            Cache::forget("block:{$blockerId}:{$userId}");
            // Also clear the blocker's blocked_user_ids cache
            Cache::forget("user:{$blockerId}:blocked_user_ids");
        }
        
        // Clear popular posts cache as blocking affects what posts are shown
        Cache::forget("posts:popular:per_page:10");
    }
}
