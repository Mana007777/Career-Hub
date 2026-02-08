<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\JobApplication;
use App\Models\Message;
use App\Models\Post;
use App\Models\PostLike;
use App\Observers\CommentObserver;
use App\Observers\JobApplicationObserver;
use App\Observers\MessageObserver;
use App\Observers\PostLikeObserver;
use App\Observers\PostObserver;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerObservers();
    }

    /**
     * Register model observers.
     */
    protected function registerObservers(): void
    {
        Post::observe(PostObserver::class);
        Comment::observe(CommentObserver::class);
        PostLike::observe(PostLikeObserver::class);
        JobApplication::observe(JobApplicationObserver::class);
        Message::observe(MessageObserver::class);
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );

        // Suppress broadcasting errors in development when broadcaster is not available
        if (!app()->isProduction() && config('broadcasting.default') !== 'log' && config('broadcasting.default') !== 'null') {
            \Illuminate\Support\Facades\Event::listen(\Illuminate\Broadcasting\BroadcastException::class, function ($exception) {
                // Log the error but don't throw it in development
                \Log::warning('Broadcasting error (suppressed in development): ' . $exception->getMessage());
                return false;
            });
        }
    }
}
