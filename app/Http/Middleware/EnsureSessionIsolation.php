<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionIsolation
{
    /**
     * Handle an incoming request.
     *
     * This middleware ensures proper session handling and prevents
     * caching issues that could cause session confusion.
     *
     * Note: This does NOT allow multiple users in the same browser.
     * Cookies are shared across tabs by design - use different browsers
     * or incognito windows for testing multiple users.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent caching of authenticated pages to avoid stale session data
        if (auth()->check()) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
        }

        return $response;
    }
}
