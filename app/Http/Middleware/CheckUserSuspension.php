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

        // Check if user is suspended
        if ($user->isSuspended()) {
            // Allow logout and admin routes
            if ($request->routeIs('logout') || $request->is('admin/*')) {
                return $next($request);
            }

            // Logout the user and redirect with message
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Your account has been suspended. Please contact support for more information.');
        }

        return $next($request);
    }
}
