<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds security-related HTTP headers to all responses.
 *
 * Developer warnings:
 *
 * - CSP (Content-Security-Policy): This policy may block external scripts, styles,
 *   or fonts (e.g. Google Fonts, Tailwind CDN, analytics). If the site breaks or
 *   resources fail to load, whitelist the required origins in the relevant
 *   directive (script-src, style-src, font-src, etc.). script-src includes
 *   'unsafe-eval' so Alpine.js and Livewire modals (x-data, x-show, etc.) work.
 *
 * - HSTS (Strict-Transport-Security): Only use in production over HTTPS. Sending
 *   HSTS over HTTP or in local/dev can cause browsers to force HTTPS and break
 *   local development. This middleware only sets HSTS when the app is in
 *   production and the request is served over HTTPS.
 *
 * - Cookies: These headers do not set cookie options. Ensure all cookies
 *   use HttpOnly (where appropriate) and SameSite (e.g. SameSite=Lax or Strict)
 *   via config/session.php and when calling cookie() or Cookie::queue().
 */
class SecureHeaders
{
    /**
     * Handle an incoming request and add security headers to the response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);


        $response->headers->set('Content-Security-Policy', $this->buildCsp($request));

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        $response->headers->set('X-Content-Type-Options', 'nosniff');

        $response->headers->set('Referrer-Policy', 'no-referrer');

        if (app()->environment('production') && $request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }

    /**
     * Build the Content-Security-Policy value.
     * Allows Bunny Fonts, inline script/style (theme, Livewire), and Vite dev server when not in production.
     */
    private function buildCsp(Request $request): string
    {
        $isLocal = app()->environment('local');

        $scriptSrc = "'self' 'unsafe-inline' 'unsafe-eval'";
        if ($isLocal) {
            $scriptSrc .= ' http://localhost:5173 http://127.0.0.1:5173';
        }

        $styleSrc = "'self' 'unsafe-inline' https://fonts.bunny.net";
        if ($isLocal) {
            $styleSrc .= ' http://localhost:5173 http://127.0.0.1:5173';
        }

        $fontSrc = "'self' https://fonts.bunny.net";

        $appUrl = rtrim(config('app.url'), '/');
        $imgSrc = "'self' data: blob: https://ui-avatars.com " . $appUrl;
        if ($request->getSchemeAndHttpHost() !== $appUrl) {
            $imgSrc .= ' ' . $request->getSchemeAndHttpHost();
        }

        $connectSrc = "'self'";
        if ($isLocal) {
            $connectSrc .= ' ws://localhost:5173 ws://127.0.0.1:5173 ws://localhost:8080 wss://localhost:8080 ws://127.0.0.1:8080 wss://127.0.0.1:8080';
        }

        return "default-src 'self'; script-src {$scriptSrc}; style-src {$styleSrc}; font-src {$fontSrc}; img-src {$imgSrc}; connect-src {$connectSrc}";
    }
}
