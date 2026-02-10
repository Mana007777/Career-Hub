<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserSuspension
{
    /**
     * Handle an incoming request.
     * Prevents suspended users from accessing the application.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for guests
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Allow admin users to access even if suspended (for admin panel access)
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Always ensure suspension relation is loaded so isSuspended() has fresh data
        $user->loadMissing('suspension');

        // Check if user is currently suspended (based on active suspension record)
        if ($user->isSuspended()) {
            // Allow logout and admin routes
            if ($request->routeIs('logout') || $request->is('admin/*')) {
                return $next($request);
            }

            // Capture suspension info before logging out
            $suspension = $user->suspension;

            // Logout the user and reset session
            // Use the web/session guard explicitly to avoid calling logout on request-based guards
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('account.suspended')
                ->with([
                    'suspended_until' => $suspension?->expires_at,
                    'suspension_reason' => $suspension?->reason,
                ]);
        }

        return $next($request);
    }
}
