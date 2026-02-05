<?php

namespace App\Queries;

use App\Models\Post;
use App\Models\SubSpecialty;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * This query involves joining the follows table and filtering posts.
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
     * This query uses aggregation (withCount) and ordering.
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
     * Get a single post by ID with all nested relationships.
     * This query has complex eager loading with nested relationships for comments and replies.
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
}
