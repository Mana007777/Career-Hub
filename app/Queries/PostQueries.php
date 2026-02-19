<?php

namespace App\Queries;

use App\Models\Post;
use App\Models\SubSpecialty;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PostQueries
{
    /**
     * Search posts by title (if available), specialty, sub-specialty, or tags.
     * This is a complex query with multiple joins and conditional logic.
     *
     * @param  string  $query
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $query, int $perPage = 10, ?int $userId = null): LengthAwarePaginator
    {
        $searchTerm = '%' . $query . '%';
        $hasTitleColumn = Schema::hasColumn('posts', 'title');

        // Get sub-specialty IDs that match the search
        $matchingSubSpecialtyIds = SubSpecialty::where('name', 'like', $searchTerm)->pluck('id');

        // Get post IDs that have matching sub-specialties in pivot
        $postIdsWithMatchingSubSpecialties = DB::table('post_specialties')
            ->whereIn('sub_specialty_id', $matchingSubSpecialtyIds)
            ->pluck('post_id');

        // Get excluded user IDs (both blocked and blocked by) if user is authenticated
        $excludedIds = $userId ? $this->getExcludedUserIds($userId) : [];

        $queryBuilder = Post::with(['user', 'stars', 'comments', 'specialties', 'tags', 'suspension'])
            // Hide any posts that currently have a suspension record
            ->whereDoesntHave('suspension')
            // And hide posts from users who are currently suspended
            ->whereHas('user', function($q) {
                $q->whereDoesntHave('suspension');
            })
            ->where(function($q) use ($searchTerm, $postIdsWithMatchingSubSpecialties, $hasTitleColumn) {
                // Prefer searching by title when the column exists; fall back to content otherwise
                if ($hasTitleColumn) {
                    $q->where('title', 'like', $searchTerm);
                } else {
                    $q->where('content', 'like', $searchTerm);
                }

                // Search by specialty name
                $q->orWhereHas('specialties', function($sq) use ($searchTerm) {
                    $sq->where('name', 'like', $searchTerm);
                })
                // Search by sub-specialty name (through pivot)
                ->orWhereIn('id', $postIdsWithMatchingSubSpecialties)
                // Search by tag name
                ->orWhereHas('tags', function($tq) use ($searchTerm) {
                    $tq->where('name', 'like', $searchTerm);
                });
            });
        
        if (!empty($excludedIds)) {
            $queryBuilder->whereNotIn('user_id', $excludedIds);
        }
        
        return $queryBuilder->latest()
            ->paginate($perPage);
    }

    /**
     * Get posts from users that the given user is following.
     * This query involves joining the follows table and filtering posts.
     * Cached for 5 minutes per user as following posts change frequently.
     *
     * @param  int  $userId
     * @param  int  $perPage
     * @param  array  $filters
     * @return LengthAwarePaginator
     */
    public function getFollowingForUser(int $userId, int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        // Note: Pagination results can't be easily cached, so we cache the query logic
        // For better performance, we could cache the following IDs separately
        $followingIds = Cache::remember(
            "user:{$userId}:following_ids",
            now()->addMinutes(5),
            function () use ($userId) {
                return DB::table('follows')
                    ->where('follower_id', $userId)
                    ->pluck('following_id')
                    ->toArray();
            }
        );

        // Get excluded user IDs (both blocked and blocked by)
        $excludedIds = $this->getExcludedUserIds($userId);

        $query = Post::whereIn('user_id', $followingIds)
            ->whereNotIn('user_id', $excludedIds)
            ->whereDoesntHave('suspension')
            ->whereHas('user', fn ($q) => $q->whereDoesntHave('suspension'))
            ->with([
                'user',
                'specialties' => fn ($q) => $q->with('subSpecialties'),
                'tags',
            ])
            ->withCount(['stars', 'comments'])
            ->with(['stars' => fn ($q) => $q->where('user_id', $userId)]);
        
        // Apply filters
        $this->applyFilters($query, $filters);
        
        return $query->paginate($perPage);
    }

    /**
     * Get popular posts ordered by like count.
     * This query uses aggregation (withCount) and ordering.
     * Cached for 10 minutes as popular posts change as likes are added.
     *
     * @param  int  $perPage
     * @param  int|null  $userId
     * @param  array  $filters
     * @return LengthAwarePaginator
     */
    public function getPopular(int $perPage = 10, ?int $userId = null, array $filters = []): LengthAwarePaginator
    {
        // Get excluded user IDs (both blocked and blocked by) if user is authenticated
        $excludedIds = $userId ? $this->getExcludedUserIds($userId) : [];
        
        // Don't cache when filters are applied as cache keys would be too complex
        $hasFilters = !empty($filters['tags']) || !empty($filters['specialties']) || !empty($filters['jobType']);
        
        $query = Post::with([
                'user',
                'specialties' => fn ($q) => $q->with('subSpecialties'),
                'tags',
            ])
            ->withCount(['stars', 'comments'])
            ->whereDoesntHave('suspension')
            ->whereHas('user', fn ($q) => $q->whereDoesntHave('suspension'))
            ->whereHas('stars')
            ->when($userId, fn ($q) => $q->with(['stars' => fn ($sq) => $sq->where('user_id', $userId)]));
        
        if (!empty($excludedIds)) {
            $query->whereNotIn('user_id', $excludedIds);
        }
        
        // CRITICAL: Order by stars_count DESC (most stars first), then by date DESC
        // Do this BEFORE applyFilters to ensure it's not overridden
        $query->orderByDesc('stars_count')
              ->orderByDesc('created_at');
        
        // Apply filters (after ordering to ensure ordering is preserved)
        $this->applyFilters($query, $filters);
        
        // DISABLE CACHE TEMPORARILY to ensure fresh data
        // Only cache if no filters are applied
        if (!$hasFilters) {
            $cacheKey = "posts:popular:per_page:{$perPage}";
            // Always clear cache to get fresh data
            Cache::forget($cacheKey);
            
            // Return without caching for now to ensure it works
            return $query->paginate($perPage);
        }
        
        return $query->paginate($perPage);
    }
    
    /**
     * Apply filters to a query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $filters
     * @return void
     */
    private function applyFilters($query, array $filters): void
    {
        // Filter by tags
        if (!empty($filters['tags']) && is_array($filters['tags'])) {
            $query->whereHas('tags', function($q) use ($filters) {
                $q->whereIn('tags.id', $filters['tags']);
            });
        }
        
        // Filter by specialties
        if (!empty($filters['specialties']) && is_array($filters['specialties'])) {
            $query->whereHas('specialties', function($q) use ($filters) {
                $q->whereIn('specialties.id', $filters['specialties']);
            });
        }
        
        // Filter by job type
        if (!empty($filters['jobType'])) {
            $query->where('job_type', $filters['jobType']);
        }
        
        // Apply sort order (only if not already set, like in popular posts)
        if (isset($filters['sortOrder']) && !$query->getQuery()->orders) {
            if ($filters['sortOrder'] === 'asc') {
                $query->oldest();
            } else {
                $query->latest();
            }
        }
    }

    /**
     * Get a single post by ID with all nested relationships.
     * This query has complex eager loading with nested relationships for comments and replies.
     * Cached for 15 minutes as post detail pages are frequently accessed.
     *
     * @param  int  $id
     * @return Post|null
     */
    public function findById(int $id): ?Post
    {
        return Cache::remember(
            "post:{$id}:with_relationships",
            now()->addMinutes(15),
            function () use ($id) {
                return Post::with([
                        'user',
                        'comments' => function ($query) {
                            $query->with([
                                'user',
                                'replies' => function ($replyQuery) {
                                    $replyQuery->with(['user']);
                                },
                            ])->orderBy('created_at', 'asc');
                        },
                        'specialties' => function ($query) {
                            $query->with('subSpecialties');
                        },
                        'tags',
                        'suspension',
                    ])
                    ->whereDoesntHave('suspension')
                    ->find($id);
            }
        );
    }

    /**
     * Clear cache for a specific post.
     * Call this when a post is updated, liked, or commented on.
     *
     * @param  int  $postId
     * @return void
     */
    public function clearPostCache(int $postId): void
    {
        Cache::forget("post:{$postId}:with_relationships");
        // Clear all popular posts caches as likes/stars/comments affect popularity
        $this->clearPopularCaches();
    }

    /**
     * Clear all popular post caches.
     * 
     * @return void
     */
    public function clearPopularCaches(): void
    {
        // Clear all popular posts caches for common per_page values
        for ($i = 1; $i <= 50; $i++) {
            Cache::forget("posts:popular:per_page:{$i}");
        }
    }

    /**
     * Clear all post caches.
     * Useful when bulk updates occur.
     *
     * @return void
     */
    public function clearAllPostCaches(): void
    {
        // Clear all popular posts caches
        $this->clearPopularCaches();
        // Individual post caches will expire via TTL
    }

    /**
     * Get blocked user IDs for a user (users they blocked).
     *
     * @param  int  $userId
     * @return array
     */
    private function getBlockedUserIds(int $userId): array
    {
        return Cache::remember(
            "user:{$userId}:blocked_user_ids",
            now()->addMinutes(5),
            function () use ($userId) {
                return DB::table('blocks')
                    ->where('blocker_id', $userId)
                    ->pluck('blocked_id')
                    ->toArray();
            }
        );
    }

    /**
     * Get user IDs who have blocked the given user.
     *
     * @param  int  $userId
     * @return array
     */
    private function getBlockedByUserIds(int $userId): array
    {
        return Cache::remember(
            "user:{$userId}:blocked_by_user_ids",
            now()->addMinutes(5),
            function () use ($userId) {
                return DB::table('blocks')
                    ->where('blocked_id', $userId)
                    ->pluck('blocker_id')
                    ->toArray();
            }
        );
    }

    /**
     * Get all user IDs to exclude (both blocked and blocked by).
     * Public so PostRepository and others can reuse the same cached result.
     *
     * @param  int  $userId
     * @return array
     */
    public function getExcludedUserIds(int $userId): array
    {
        $blockedIds = $this->getBlockedUserIds($userId);
        $blockedByIds = $this->getBlockedByUserIds($userId);
        $excluded = array_unique(array_merge($blockedIds, $blockedByIds));
        return array_values(array_diff($excluded, [$userId]));
    }
}
