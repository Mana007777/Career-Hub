<?php

namespace App\Repositories;

use App\Models\PostCv;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostCvRepository
{
    /**
     * Get all CVs for posts owned by a specific user.
     * Simple query with whereHas - kept in repository.
     *
     * @param  int  $userId
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getCvsForUserPosts(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return PostCv::with(['post', 'user'])
            ->whereHas('post', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find a CV by ID with relationships.
     * Simple query - kept in repository.
     *
     * @param  int  $id
     * @return PostCv|null
     */
    public function findById(int $id): ?PostCv
    {
        return PostCv::with('post')->find($id);
    }

    /**
     * Check if user has uploaded CV for a post.
     * Simple query - kept in repository.
     *
     * @param  int  $postId
     * @param  int  $userId
     * @return bool
     */
    public function hasUserUploadedCv(int $postId, int $userId): bool
    {
        return PostCv::where('post_id', $postId)
            ->where('user_id', $userId)
            ->exists();
    }
}
