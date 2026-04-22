<?php

namespace App\Services;

use App\Models\Post;
use App\Queries\PostQueries;
use App\Repositories\PostRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Storage;

class PostService
{
    protected PostRepository $repository;
    protected PostQueries $queries;
    protected AiRecommendationService $aiRecommendationService;
    protected bool $recommendationNetworkDisconnected = false;

    public function __construct(PostRepository $repository, PostQueries $queries, AiRecommendationService $aiRecommendationService)
    {
        $this->repository = $repository;
        $this->queries = $queries;
        $this->aiRecommendationService = $aiRecommendationService;
    }

    /**
     * Get all posts with user relationship, ordered by latest.
     *
     * @param  int  $perPage
     * @param  array  $filters
     * @return LengthAwarePaginator
     */
    public function getAllPosts(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $userId = auth()->id();
        return $this->repository->getAll($perPage, $userId, $filters);
    }

    /**
     * Get popular posts ordered by like count.
     * Uses PostQueries for heavy aggregation query.
     *
     * @param  int  $perPage
     * @param  array  $filters
     * @return LengthAwarePaginator
     */
    public function getPopularPosts(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $userId = auth()->id();
        return $this->queries->getPopular($perPage, $userId, $filters);
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
     * @param  array  $filters
     * @return LengthAwarePaginator
     */
    public function getFollowingPosts(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $userId = auth()->id();

        if (!$userId) {
            // If not authenticated, just show the general feed
            return $this->getAllPosts($perPage, $filters);
        }

        return $this->queries->getFollowingForUser($userId, $perPage, $filters);
    }

    /**
     * Get personalized recommended posts from Career Hub AI.
     */
    public function getRecommendedPosts(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $this->recommendationNetworkDisconnected = false;

        $userId = auth()->id();
        if (! $userId) {
            return $this->getAllPosts($perPage, $filters);
        }

        $recommendation = $this->aiRecommendationService->getRecommendedPostIdsWithStatus($userId);
        $recommendedIds = $recommendation['ids'] ?? [];
        $this->recommendationNetworkDisconnected = (bool) ($recommendation['disconnected'] ?? false);

        if ($this->recommendationNetworkDisconnected) {
            return new Paginator(
                collect(),
                0,
                $perPage,
                Paginator::resolveCurrentPage(),
                [
                    'path' => Paginator::resolveCurrentPath(),
                    'pageName' => 'page',
                ]
            );
        }

        if ($recommendedIds === []) {
            return $this->getAllPosts($perPage, $filters);
        }

        $query = Post::query()
            ->with([
                'user',
                'specialties' => fn ($q) => $q->with('subSpecialties'),
                'tags',
            ])
            ->withCount(['stars', 'comments', 'shares'])
            ->withoutActiveSuspension()
            ->whereHas('user', fn ($q) => $q->withoutActiveSuspension())
            ->whereIn('id', $recommendedIds);

        $excludedIds = $this->queries->getExcludedUserIds($userId);
        if ($excludedIds !== []) {
            $query->whereNotIn('user_id', $excludedIds);
        }

        $query->with(['stars' => fn ($q) => $q->where('user_id', $userId)]);
        $query->withExists(['shares as viewer_has_reposted' => fn ($q) => $q->where('user_id', $userId)]);

        if (! empty($filters['tags']) && is_array($filters['tags'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('tags.id', $filters['tags']);
            });
        }

        if (! empty($filters['specialties']) && is_array($filters['specialties'])) {
            $query->whereHas('specialties', function ($q) use ($filters) {
                $q->whereIn('specialties.id', $filters['specialties']);
            });
        }

        if (! empty($filters['jobType'])) {
            $query->where('job_type', $filters['jobType']);
        }

        $posts = $query->get()->sortBy(function (Post $post) use ($recommendedIds) {
            $position = array_search($post->id, $recommendedIds, true);

            return $position === false ? PHP_INT_MAX : $position;
        })->values();

        $currentPage = Paginator::resolveCurrentPage();
        $items = $posts->forPage($currentPage, $perPage)->values();

        return new Paginator(
            $items,
            $posts->count(),
            $perPage,
            $currentPage,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );
    }

    public function isRecommendationNetworkDisconnected(): bool
    {
        return $this->recommendationNetworkDisconnected;
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
        $userId = auth()->id();
        return $this->queries->search($query, $perPage, $userId);
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
