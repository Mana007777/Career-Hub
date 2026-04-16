<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetUserLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        if (Auth::check() && Auth::user()?->locale) {
            $locale = Auth::user()->locale;
        } elseif (session()->has('locale')) {
            $locale = session('locale');
        }

        if ($locale) {
            // Normalize aliases so translation files are resolved consistently.
            if (in_array($locale, ['ku', 'kurdish'], true)) {
                $locale = 'ckb';
            }

            app()->setLocale($locale);

            // Carbon doesn't have full "ckb" resources; map to Kurdish locale.
            Carbon::setLocale($locale === 'ckb' ? 'ku' : $locale);
        }

        return $next($request);
    }
}
