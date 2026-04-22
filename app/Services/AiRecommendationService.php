<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
}
