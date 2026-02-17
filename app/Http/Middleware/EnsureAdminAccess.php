<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureAdminAccess
{
    /**
     * Handle an incoming request.
     * App admins (isAdmin) cannot access Filament; they use the Reports section in the main app.
     * Only non-admin users (e.g. super staff) may access the Filament panel.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('filament.admin.auth.login');
        }

        $user = Auth::user();

        // App admins cannot access Filament; redirect them to the main app
        if ($user->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('filament.admin.auth.login')
                ->with('error', 'Admins use the Reports section in the app. You cannot access this panel.');
        }

        return $next($request);
    }
}
