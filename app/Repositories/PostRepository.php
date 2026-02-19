<?php

namespace App\Repositories;

use App\Models\Post;
use App\Queries\PostQueries;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository
{
    protected PostQueries $queries;

    public function __construct(?PostQueries $queries = null)
    {
        $this->queries = $queries ?? app(PostQueries::class);
    }

    /**
     * Get all posts with relationships, ordered by latest.
     * Optimized for Octane: lightweight eager load, withCount for comments/stars,
     * cached excluded user IDs, no full comment/stars collection load.
     *
     * @param  int  $perPage
     * @param  int|null  $userId  Current user ID to filter blocked users
     * @param  array  $filters  Filter parameters (sortOrder, tags, specialties, jobType)
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 10, ?int $userId = null, array $filters = []): LengthAwarePaginator
    {
        $query = Post::query()
            ->with([
                'user',
                'specialties' => fn ($q) => $q->with('subSpecialties'),
                'tags',
            ])
            ->withCount(['stars', 'comments'])
            ->whereDoesntHave('suspension')
            ->whereHas('user', fn ($q) => $q->whereDoesntHave('suspension'));

        if ($userId) {
            $excludedIds = $this->queries->getExcludedUserIds($userId);
            if ($excludedIds !== []) {
                $query->whereNotIn('user_id', $excludedIds);
            }
            // Eager load only current user's star for "did I star this" (at most 1 row per post)
            $query->with(['stars' => fn ($q) => $q->where('user_id', $userId)]);
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
            ->whereDoesntHave('suspension')
            ->with([
                'user',
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
