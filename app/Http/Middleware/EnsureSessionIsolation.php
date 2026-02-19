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

        if ($user) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            
            $isNowOnline = $user->isActive();
            if ($wasOnline !== $isNowOnline) {
                broadcast(new UserPresenceChanged($user, $isNowOnline));
                
                if ($isNowOnline && !$wasOnline) {
                    app(MessageStatusService::class)->updateSentToDeliveredForUser($user);
                }
            }
        }

        return $response;
    }
}
