<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class PostService
{
    protected PostRepository $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all posts with user relationship, ordered by latest.
     *
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPosts(int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getAll($perPage);
    }

    /**
     * Get posts for the authenticated user.
     *
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getUserPosts(int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getByUserId(auth()->id(), $perPage);
    }

    /**
     * Get a single post by ID with relationships.
     *
     * @param  int  $id
     * @return Post|null
     */
    public function getPostById(int $id): ?Post
    {
        return $this->repository->findById($id);
    }

    /**
     * Search posts by content, specialty, sub-specialty, or tags.
     *
     * @param  string  $query
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function searchPosts(string $query, int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->search($query, $perPage);
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

        return Storage::url($post->media);
    }
}
