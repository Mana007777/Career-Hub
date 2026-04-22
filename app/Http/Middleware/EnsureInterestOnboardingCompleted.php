<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInterestOnboardingCompleted
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasVerifiedEmail()) {
            return $next($request);
        }

        if ($request->routeIs('interests.*')) {
            return $next($request);
        }

        $hasInterests = is_array($user->ai_interest_tags)
            && count(array_filter($user->ai_interest_tags, fn ($tag) => is_string($tag) && trim($tag) !== '')) > 0;

        if (! $hasInterests) {
            return redirect()->route('interests.show');
        }

        return $next($request);
    }
}
