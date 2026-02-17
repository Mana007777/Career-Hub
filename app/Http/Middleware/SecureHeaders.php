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

        // Restricts where content can be loaded from. Reduces XSS and data injection.
        // Allows: Bunny Fonts (fonts.bunny.net), inline scripts/styles (theme, Livewire/Alpine), Vite dev server in local.
        $response->headers->set('Content-Security-Policy', $this->buildCsp($request));

        // Prevents the page from being embedded in iframes on other sites (clickjacking).
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Stops browsers from MIME-sniffing; they must use the declared Content-Type.
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Controls how much referrer info is sent on navigation (no-referrer = none).
        $response->headers->set('Referrer-Policy', 'no-referrer');

        // HSTS: only set in production over HTTPS (see class docblock).
        if (app()->environment('production') && $request->secure()) {
            // Tells browsers to only use HTTPS for this site for the given max-age (1 year).
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

        // script-src: 'self' + inline + eval (Alpine/Livewire modals and directives) + Vite dev in local
        $scriptSrc = "'self' 'unsafe-inline' 'unsafe-eval'";
        if ($isLocal) {
            $scriptSrc .= ' http://localhost:5173 http://127.0.0.1:5173';
        }

        // style-src: 'self' + inline + Bunny Fonts + Vite dev in local
        $styleSrc = "'self' 'unsafe-inline' https://fonts.bunny.net";
        if ($isLocal) {
            $styleSrc .= ' http://localhost:5173 http://127.0.0.1:5173';
        }

        // font-src: fonts from same origin and Bunny Fonts
        $fontSrc = "'self' https://fonts.bunny.net";

        // img-src: same origin, data, blob, ui-avatars (Jetstream default), app URL (storage uses APP_URL; covers localhost vs 127.0.0.1)
        $appUrl = rtrim(config('app.url'), '/');
        $imgSrc = "'self' data: blob: https://ui-avatars.com " . $appUrl;
        if ($request->getSchemeAndHttpHost() !== $appUrl) {
            $imgSrc .= ' ' . $request->getSchemeAndHttpHost();
        }

        // connect-src: same origin + Vite HMR websocket + Laravel Echo/Pusher in local
        $connectSrc = "'self'";
        if ($isLocal) {
            $connectSrc .= ' ws://localhost:5173 ws://127.0.0.1:5173 ws://localhost:8080 wss://localhost:8080 ws://127.0.0.1:8080 wss://127.0.0.1:8080';
        }

        return "default-src 'self'; script-src {$scriptSrc}; style-src {$styleSrc}; font-src {$fontSrc}; img-src {$imgSrc}; connect-src {$connectSrc}";
    }
}
