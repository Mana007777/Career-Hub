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
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 10, ?int $userId = null): LengthAwarePaginator
    {
        $query = Post::with([
            'user', 
            'likes',
            'comments',
            'specialties' => function($query) {
                $query->with('subSpecialties');
            },
            'tags'
        ]);
        
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
        
        return $query->latest()
            ->paginate($perPage);
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
            ->with([
                'user',
                'likes',
                'comments',
                'specialties' => function($query) {
                    $query->with('subSpecialties');
                },
                'tags'
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
