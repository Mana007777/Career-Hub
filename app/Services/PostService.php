<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
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
        return Post::with(['user', 'likes', 'comments'])
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
