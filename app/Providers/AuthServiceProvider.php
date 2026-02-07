<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        User::class => UserPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define admin gate - only test@example.com can be admin
        Gate::define('is-admin', function (User $user) {
            return $user->isAdmin();
        });

        // Define gates for admin actions
        Gate::define('manage-posts', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-comments', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-reports', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('view-admin-panel', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('delete-any-post', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('delete-any-user', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('delete-any-comment', function (User $user) {
            return $user->isAdmin();
        });
    }
}
