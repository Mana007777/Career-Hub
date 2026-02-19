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
        
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        
        if ($user->isAdmin()) {
            return $next($request);
        }

        
        $user->loadMissing('suspension');

        
        if ($user->isSuspended()) {
            
            if ($request->routeIs('logout') || $request->is('admin/*')) {
                return $next($request);
            }

            
            $suspension = $user->suspension;

            
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
