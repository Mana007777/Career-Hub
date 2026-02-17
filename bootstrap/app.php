<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\AuthenticationRequiredException;
use App\Exceptions\InvalidExpirationDateException;
use App\Exceptions\ReportException;
use App\Exceptions\UserFollowException;
use App\Exceptions\UserBlockException;
use App\Exceptions\EndorsementException;
use App\Exceptions\CommentException;
use App\Exceptions\PostActionException;
use App\Exceptions\UserNotificationException;
use App\Exceptions\EmailVerificationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        channels: __DIR__.'/../routes/channels.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SecureHeaders::class,
            \App\Http\Middleware\EnsureSessionIsolation::class,
            \App\Http\Middleware\CheckUserSuspension::class,
        ]);
        $middleware->api(append: [
            \App\Http\Middleware\SecureHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Return clear JSON responses for our domain exceptions on API/JSON requests.
        // For normal web/Livewire requests we fall back to Laravel's default handling.

        $exceptions->render(function (AuthenticationRequiredException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 401);
            }

            return null;
        });

        $exceptions->render(function (InvalidExpirationDateException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 422);
            }

            return null;
        });

        $exceptions->render(function (
            ReportException|
            UserFollowException|
            UserBlockException|
            EndorsementException|
            CommentException|
            PostActionException|
            UserNotificationException|
            EmailVerificationException $e,
            $request
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 422);
            }

            return null;
        });
    })->create();
