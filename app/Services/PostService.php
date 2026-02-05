<?php

namespace App\Services;

use App\Models\Post;
use App\Queries\PostQueries;
use App\Repositories\PostRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class PostService
{
    protected PostRepository $repository;
    protected PostQueries $queries;

    public function __construct(PostRepository $repository, PostQueries $queries)
    {
        $this->repository = $repository;
        $this->queries = $queries;
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
     * Get popular posts ordered by like count.
     * Uses PostQueries for heavy aggregation query.
     *
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getPopularPosts(int $perPage = 10): LengthAwarePaginator
    {
        return $this->queries->getPopular($perPage);
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
     * Get posts only from users that the authenticated user follows.
     * Uses PostQueries for heavy query with joins.
     *
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getFollowingPosts(int $perPage = 10): LengthAwarePaginator
    {
        $userId = auth()->id();

        if (!$userId) {
            // If not authenticated, just show the general feed
            return $this->getAllPosts($perPage);
        }

        return $this->queries->getFollowingForUser($userId, $perPage);
    }

    /**
     * Get a single post by ID with relationships.
     * Uses PostQueries for complex eager loading with nested relationships.
     *
     * @param  int  $id
     * @return Post|null
     */
    public function getPostById(int $id): ?Post
    {
        return $this->queries->findById($id);
    }

    /**
     * Search posts by content, specialty, sub-specialty, or tags.
     * Uses PostQueries for complex search with multiple joins and conditional logic.
     *
     * @param  string  $query
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function searchPosts(string $query, int $perPage = 10): LengthAwarePaginator
    {
        return $this->queries->search($query, $perPage);
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
