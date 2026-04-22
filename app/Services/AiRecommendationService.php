<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiRecommendationService
{
    public function getRecommendedPostIds(int $laravelUserId): array
    {
        $baseUrl = rtrim((string) config('services.career_hub_ai.base_url', ''), '/');
        $serviceToken = (string) config('services.career_hub_ai.token', '');
        $timeout = (int) config('services.career_hub_ai.timeout', 8);

        if ($baseUrl === '' || $serviceToken === '') {
            return [];
        }

        try {
            $response = Http::timeout($timeout)
                ->acceptJson()
                ->withHeaders([
                    'X-Service-Token' => $serviceToken,
                ])
                ->get("{$baseUrl}/api/v1/recommendations/{$laravelUserId}/");

            if (! $response->successful()) {
                Log::warning('Career Hub AI recommendation request failed', [
                    'status' => $response->status(),
                    'user_id' => $laravelUserId,
                ]);

                return [];
            }

            $recommendations = $response->json('recommendations', []);
            if (! is_array($recommendations)) {
                return [];
            }

            return collect($recommendations)
                ->pluck('laravel_post_id')
                ->map(fn ($id) => (int) $id)
                ->filter(fn (int $id) => $id > 0)
                ->values()
                ->all();
        } catch (\Throwable $e) {
            Log::warning('Career Hub AI recommendation request exception', [
                'user_id' => $laravelUserId,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    public function registerUser(User $user, array $interestTags = []): bool
    {
        return $this->send('post', '/api/v1/users/register/', [
            'laravel_user_id' => $user->id,
            'role' => $user->role ?? 'seeker',
            'specialties' => array_values($interestTags),
            'specialty_weights' => collect($interestTags)->mapWithKeys(fn (string $tag) => [$tag => 1.0])->all(),
        ]);
    }

    public function updateUserInterests(User $user, array $interestTags): bool
    {
        return $this->send('put', "/api/v1/users/{$user->id}/specialties/", [
            'specialties' => array_values($interestTags),
        ]);
    }

    public function trackInteraction(User $user, int $postId, string $action): bool
    {
        return $this->send('post', '/api/v1/interactions/track/', [
            'laravel_user_id' => $user->id,
            'laravel_post_id' => $postId,
            'action' => $action,
        ]);
    }

    public function trackSearchInterest(User $user, string $query): void
    {
        $normalizedQuery = Str::of($query)->lower()->squish()->toString();
        if ($normalizedQuery === '') {
            return;
        }

        $throttleKey = 'ai-search-interest:'.$user->id.':'.md5($normalizedQuery);
        if (! Cache::add($throttleKey, true, now()->addSeconds(45))) {
            return;
        }

        $matchedTags = Tag::query()
            ->select('name')
            ->where(function ($builder) use ($normalizedQuery): void {
                $builder->whereRaw('LOWER(name) LIKE ?', ['%'.$normalizedQuery.'%'])
                    ->orWhereRaw('? LIKE CONCAT("%", LOWER(name), "%")', [$normalizedQuery]);
            })
            ->limit(5)
            ->pluck('name')
            ->map(fn (string $tag) => Str::lower(trim($tag)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($matchedTags === []) {
            $matchedTags = [Str::limit($normalizedQuery, 50, '')];
        }

        $currentTags = collect($user->ai_interest_tags ?? [])
            ->filter(fn ($tag) => is_string($tag) && trim($tag) !== '')
            ->map(fn (string $tag) => Str::lower(trim($tag)))
            ->values();

        $updatedTags = $currentTags
            ->merge($matchedTags)
            ->unique()
            ->take(25)
            ->values()
            ->all();

        $user->forceFill([
            'ai_interest_tags' => $updatedTags,
        ])->save();

        $this->registerUser($user, $updatedTags);
        $this->updateUserInterests($user, $updatedTags);
    }

    public function syncPost(Post $post): bool
    {
        $post->loadMissing(['tags']);

        $payload = [
            'laravel_post_id' => $post->id,
            'company_id' => $post->user_id,
            'title' => (string) ($post->title ?? ''),
            'content' => (string) ($post->content ?? ''),
            'tags' => $post->tags->pluck('name')->map(fn ($tag) => Str::lower((string) $tag))->values()->all(),
            'total_likes' => (int) $post->stars()->count(),
            'total_comments' => (int) $post->comments()->count(),
            'total_reposts' => (int) $post->shares()->count(),
            'created_at' => $post->created_at?->toIso8601String(),
        ];

        return $this->send('post', '/api/v1/posts/sync/', $payload);
    }

    public function deletePost(int $postId): bool
    {
        return $this->send('delete', "/api/v1/posts/{$postId}/", []);
    }

    private function send(string $method, string $path, array $payload): bool
    {
        $baseUrl = rtrim((string) config('services.career_hub_ai.base_url', ''), '/');
        $serviceToken = (string) config('services.career_hub_ai.token', '');
        $timeout = (int) config('services.career_hub_ai.timeout', 8);

        if ($baseUrl === '' || $serviceToken === '') {
            return false;
        }

        try {
            $client = Http::timeout($timeout)
                ->acceptJson()
                ->withHeaders(['X-Service-Token' => $serviceToken]);
            $url = $baseUrl.$path;

            if (strtolower($method) === 'delete') {
                $response = $client->delete($url);
            } else {
                $response = $client->{$method}($url, $payload);
            }

            if ($response->successful()) {
                return true;
            }

            Log::warning('Career Hub AI request failed', [
                'method' => strtoupper($method),
                'path' => $path,
                'status' => $response->status(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Career Hub AI request exception', [
                'method' => strtoupper($method),
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }
}
