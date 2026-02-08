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
     * Only allow test@example.com with admin role to access admin panel.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('filament.admin.auth.login');
        }

        $user = Auth::user();
        
        // Strictly only allow test@example.com with admin role
        $email = strtolower(trim($user->email ?? ''));
        $role = $user->role ?? '';
        
        if ($email !== 'test@example.com' || $role !== 'admin') {
            // Log out the user and redirect to login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('filament.admin.auth.login')
                ->with('error', 'Access denied. Only authorized administrators can access this panel.');
        }

        return $next($request);
    }
}
