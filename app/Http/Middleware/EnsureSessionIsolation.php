<?php

namespace App\Http\Middleware;

use App\Events\UserPresenceChanged;
use App\Services\MessageStatusService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        $wasOnline = $user ? $user->isActive() : false;

        $response = $next($request);

        // Prevent caching of authenticated pages to avoid stale session data
        if ($user) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            
            // Check if user's online status changed
            $isNowOnline = $user->isActive();
            if ($wasOnline !== $isNowOnline) {
                // Broadcast presence change
                broadcast(new UserPresenceChanged($user, $isNowOnline));
                
                // If user just came online, update sent messages to delivered
                if ($isNowOnline && !$wasOnline) {
                    app(MessageStatusService::class)->updateSentToDeliveredForUser($user);
                }
            }
        }

        return $response;
    }
}
