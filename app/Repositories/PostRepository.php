<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\SubSpecialty;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PostRepository
{
    /**
     * Get all posts with relationships, ordered by latest.
     *
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Post::with([
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
     * Get posts for a specific user.
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
     * Get a single post by ID with relationships.
     *
     * @param  int  $id
     * @return Post|null
     */
    public function findById(int $id): ?Post
    {
        return Post::with([
                'user',
                'likes.user',
                'likedBy',
                'comments' => function ($query) {
                    $query->with([
                        'user',
                        'likes.user',
                        'replies' => function ($replyQuery) {
                            $replyQuery->with(['user', 'likes.user']);
                        },
                    ])->orderBy('created_at', 'asc');
                },
                'specialties' => function ($query) {
                    $query->with('subSpecialties');
                },
                'tags',
            ])
            ->find($id);
    }

    /**
     * Search posts by title (if available), specialty, sub-specialty, or tags.
     *
     * @param  string  $query
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $query, int $perPage = 10): LengthAwarePaginator
    {
        $searchTerm = '%' . $query . '%';
        $hasTitleColumn = Schema::hasColumn('posts', 'title');

        // Get sub-specialty IDs that match the search
        $matchingSubSpecialtyIds = SubSpecialty::where('name', 'like', $searchTerm)->pluck('id');

        // Get post IDs that have matching sub-specialties in pivot
        $postIdsWithMatchingSubSpecialties = DB::table('post_specialties')
            ->whereIn('sub_specialty_id', $matchingSubSpecialtyIds)
            ->pluck('post_id');

        return Post::with(['user', 'likes', 'comments', 'specialties', 'tags'])
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
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get posts from users that the given user is following.
     *
     * @param  int  $userId
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getFollowingForUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        $followingIds = DB::table('follows')
            ->where('follower_id', $userId)
            ->pluck('following_id');

        return Post::whereIn('user_id', $followingIds)
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
     * Get popular posts ordered by like count.
     *
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getPopular(int $perPage = 10): LengthAwarePaginator
    {
        return Post::with([
                'user',
                'likes',
                'comments',
                'specialties' => function($query) {
                    $query->with('subSpecialties');
                },
                'tags'
            ])
            ->withCount('likes')
            ->orderByDesc('likes_count')
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
     *
     * @param  int  $id
     * @return Post|null
     */
    public function findForEdit(int $id): ?Post
    {
        return Post::with(['specialties', 'tags'])->find($id);
    }
}
