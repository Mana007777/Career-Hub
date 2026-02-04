<?php

namespace App\Services;

use App\Models\Post;
use App\Models\SubSpecialty;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostService
{
    /**
     * Get all posts with user relationship, ordered by latest.
     *
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPosts(int $perPage = 10): LengthAwarePaginator
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
     * Get posts for the authenticated user.
     *
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getUserPosts(int $perPage = 10): LengthAwarePaginator
    {
        return Post::where('user_id', auth()->id())
            ->with(['user', 'likes', 'comments'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get a single post by ID with relationships.
     *
     * @param  int  $id
     * @return Post|null
     */
    public function getPostById(int $id): ?Post
    {
        return Post::with(['user', 'likes', 'comments'])
            ->find($id);
    }

    /**
     * Search posts by content.
     *
     * @param  string  $query
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function searchPosts(string $query, int $perPage = 10): LengthAwarePaginator
    {
        $searchTerm = '%' . $query . '%';
        
        // Get sub-specialty IDs that match the search
        $matchingSubSpecialtyIds = SubSpecialty::where('name', 'like', $searchTerm)->pluck('id');
        
        // Get post IDs that have matching sub-specialties in pivot
        $postIdsWithMatchingSubSpecialties = \DB::table('post_specialties')
            ->whereIn('sub_specialty_id', $matchingSubSpecialtyIds)
            ->pluck('post_id');
        
        return Post::with(['user', 'likes', 'comments', 'specialties', 'tags'])
            ->where(function($q) use ($searchTerm, $postIdsWithMatchingSubSpecialties) {
                $q->where('content', 'like', $searchTerm)
                  // Search by specialty name
                  ->orWhereHas('specialties', function($sq) use ($searchTerm) {
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
     * Get the media URL for a post.
     *
     * @param  Post  $post
     * @return string|null
     */
    public function getMediaUrl(Post $post): ?string
    {
        if (!$post->media) {
            return null;
        }

        return Storage::disk('public')->url($post->media);
    }
}
