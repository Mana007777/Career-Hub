<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository
{
    /**
     * Get all posts with relationships, ordered by latest.
     * Simple query - kept in repository.
     *
     * @param  int  $perPage
     * @param  int|null  $userId  Current user ID to filter blocked users
     * @param  array  $filters  Filter parameters (sortOrder, tags, specialties, jobType)
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 10, ?int $userId = null, array $filters = []): LengthAwarePaginator
    {
        $query = Post::with([
            'user', 
            'likes',
            'stars',
            'comments',
            'specialties' => function($query) {
                $query->with('subSpecialties');
            },
            'tags',
            'suspension'
        ])
        ->whereDoesntHave('suspension', function($q) {
            $q->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
        })
        ->whereHas('user', function($q) {
            $q->whereDoesntHave('suspension', function($sq) {
                $sq->where(function($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                });
            });
        });
        
        // Filter out posts from blocked users (bidirectional blocking)
        if ($userId) {
            // Users the current user has blocked
            $blockedIds = \DB::table('blocks')
                ->where('blocker_id', $userId)
                ->pluck('blocked_id')
                ->toArray();
            
            // Users who have blocked the current user
            $blockedByIds = \DB::table('blocks')
                ->where('blocked_id', $userId)
                ->pluck('blocker_id')
                ->toArray();
            
            // Combine both arrays
            $excludedIds = array_unique(array_merge($blockedIds, $blockedByIds));
            
            if (!empty($excludedIds)) {
                $query->whereNotIn('user_id', $excludedIds);
            }
        }
        
        // Apply filters
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
        
        // Apply sort order
        $sortOrder = $filters['sortOrder'] ?? 'desc';
        if ($sortOrder === 'asc') {
            $query->oldest();
        } else {
            $query->latest();
        }
        
        return $query->paginate($perPage);
    }

    /**
     * Get posts for a specific user.
     * Simple query - kept in repository.
     *
     * @param  int  $userId
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getByUserId(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Post::where('user_id', $userId)
            ->whereDoesntHave('suspension', function($q) {
                $q->where(function($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                });
            })
            ->with([
                'user',
                'likes',
                'stars',
                'comments',
                'specialties' => function($query) {
                    $query->with('subSpecialties');
                },
                'tags',
                'suspension'
            ])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create a new post.
     *
     * @param  array<string, mixed>  $data
     * @return Post
     */
    public function create(array $data): Post
    {
        return Post::create($data);
    }

    /**
     * Update an existing post.
     *
     * @param  Post  $post
     * @param  array<string, mixed>  $data
     * @return bool
     */
    public function update(Post $post, array $data): bool
    {
        return $post->update($data);
    }

    /**
     * Delete a post.
     *
     * @param  Post  $post
     * @return bool
     */
    public function delete(Post $post): bool
    {
        return $post->delete();
    }

    /**
     * Get post with relationships for editing.
     * Simple query - kept in repository.
     *
     * @param  int  $id
     * @return Post|null
     */
    public function findForEdit(int $id): ?Post
    {
        return Post::with(['specialties', 'tags'])->find($id);
    }

    /**
     * Find a post by ID.
     * Simple query - kept in repository.
     *
     * @param  int  $id
     * @return Post
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int $id): Post
    {
        return Post::findOrFail($id);
    }

    /**
     * Get empty paginated result (for search when no query).
     * Simple query - kept in repository.
     *
     * @param  int  $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getEmptyPaginated(int $perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Post::query()->whereRaw('1 = 0')->paginate($perPage);
    }
}
