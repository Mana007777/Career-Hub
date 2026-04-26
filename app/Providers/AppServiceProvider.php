<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\JobApplication;
use App\Models\Message;
use App\Models\Post;
use App\Models\PostSuspension;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserSuspension;
use App\Observers\CommentObserver;
use App\Observers\JobApplicationObserver;
use App\Observers\MessageObserver;
use App\Observers\PostObserver;
use App\Observers\PostSuspensionObserver;
use App\Observers\UserNotificationObserver;
use App\Observers\UserObserver;
use App\Observers\UserSuspensionObserver;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * When using Octane (FrankenPHP) the app is long-lived: avoid binding as singleton
     * anything that holds the current request, container, or config instance.
     * Use $this->app->bind() for request-scoped services, or inject request/config
     * via method parameters or the request()/config() helpers at runtime.
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
        User::observe(UserObserver::class);
        UserSuspension::observe(UserSuspensionObserver::class);
        Post::observe(PostObserver::class);
        PostSuspension::observe(PostSuspensionObserver::class);
        Comment::observe(CommentObserver::class);
        JobApplication::observe(JobApplicationObserver::class);
        Message::observe(MessageObserver::class);
        UserNotification::observe(UserNotificationObserver::class);
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
        if (! app()->isProduction() && config('broadcasting.default') !== 'log' && config('broadcasting.default') !== 'null') {
            \Illuminate\Support\Facades\Event::listen(\Illuminate\Broadcasting\BroadcastException::class, function ($exception) {
                // Log the error but don't throw it in development
                \Log::warning('Broadcasting error (suppressed in development): '.$exception->getMessage());

                return false;
            });
        }

        // Allow verification links clicked on a different device/browser (e.g. mobile Gmail app)
        // by using a signed route that does not require an existing authenticated session.
        VerifyEmail::toMailUsing(function ($notifiable) {
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify.external',
                now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => hash('sha256', $notifiable->getEmailForVerification()),
                ]
            );

            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $verificationUrl)
                ->line('If you did not create an account, no further action is required.');
        });
    }
}
