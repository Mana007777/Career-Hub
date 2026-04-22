<?php

use App\Http\Controllers\VerificationPaymentController;
use App\Http\Controllers\InterestOnboardingController;
use App\Http\Controllers\Auth\ExternalEmailVerificationController;
use App\Http\Controllers\Auth\EmailVerificationCodeController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Show suspended account notice (for users whose accounts are suspended)
Route::get('/account/suspended', function () {
    return view('auth.suspended');
})->name('account.suspended');

Route::get('/email/verify/external/{id}/{hash}', ExternalEmailVerificationController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify.external');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->get('/email/verification-status', function (Request $request) {
    return response()->json([
        'verified' => (bool) $request->user()?->hasVerifiedEmail(),
        'redirect' => route('dashboard'),
    ]);
})->name('verification.status');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'throttle:6,1',
])->group(function () {
    Route::post('/email/verify-code/send', [EmailVerificationCodeController::class, 'send'])->name('verification.code.send');
    Route::post('/email/verify-code/confirm', [EmailVerificationCodeController::class, 'verify'])->name('verification.code.confirm');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    \App\Http\Middleware\EnsureInterestOnboardingCompleted::class,
])->group(function () {
    Route::get('/interests', [InterestOnboardingController::class, 'show'])->name('interests.show');
    Route::post('/interests', [InterestOnboardingController::class, 'store'])->name('interests.store');

    Route::get('/posts', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/posts/{slug}', function (string $slug) {
        $postPageTitle = 'Post';
        $parts = explode('-', $slug);
        $id = end($parts);
        if (is_numeric($id)) {
            $headline = Post::query()->whereKey((int) $id)->value('title');
            if (is_string($headline) && trim($headline) !== '') {
                $postPageTitle = Str::limit(trim($headline), 72, '…');
            }
        }

        return view('dashboard', [
            'postSlug' => $slug,
            'postPageTitle' => $postPageTitle,
        ]);
    })->name('posts.show');

    Route::get('/user/{username}', function (string $username) {
        $username = ltrim($username, '@');
        $profilePageTitle = '@'.$username;
        $profileUser = User::query()->where('username', $username)->first(['name', 'username']);
        if ($profileUser !== null) {
            $profilePageTitle = $profileUser->name.' (@'.$profileUser->username.')';
        }

        return view('dashboard', [
            'profileUsername' => $username,
            'profilePageTitle' => $profilePageTitle,
        ]);
    })->name('user.profile');

    Route::get('/cvs', function () {
        return view('dashboard', ['showCvs' => true]);
    })->name('cvs');

    Route::get('/my-reposts', function () {
        if (! auth()->user()?->isSeeker()) {
            return redirect()->route('dashboard');
        }

        return view('dashboard', [
            'showMyReposts' => true,
            'myRepostsPageTitle' => __('Your reposts'),
        ]);
    })->name('my-reposts');

    Route::get('/reports', function () {
        return view('dashboard', ['showReports' => true]);
    })->name('reports')->middleware('can:view-admin-panel');

    Route::get('/settings', function () {
        return view('dashboard', ['showSettings' => true]);
    })->name('settings');

    Route::get('/bookmarks', function () {
        return view('dashboard', ['showBookmarks' => true]);
    })->name('bookmarks');

    Route::get('/search', function () {
        $q = request()->query('q');
        $searchPageTitle = 'Search';
        if (is_string($q) && trim($q) !== '') {
            $searchPageTitle = 'Search: '.Str::limit(trim($q), 48, '…');
        }

        return view('dashboard', [
            'openSearch' => true,
            'q' => $q,
            'type' => request()->query('type'),
            'searchPageTitle' => $searchPageTitle,
        ]);
    })->name('search');

    Route::prefix('/verification-payments')->name('verification-payments.')->group(function () {
        Route::post('/{verification}/checkout', [VerificationPaymentController::class, 'createCheckout'])->name('checkout');
        Route::get('/{verification}/status', [VerificationPaymentController::class, 'refreshStatus'])->name('status');
    });
});

// GitHub OAuth (manual, without Socialite) using Livewire components
Route::get('/auth/github/callback', \App\Livewire\Auth\GithubCallback::class)->name('auth.github.callback');
Route::get('/auth/google/callback', \App\Livewire\Auth\GoogleCallback::class)->name('auth.google.callback');

// Development helper: Quick user switch for testing
if (app()->environment('local')) {
    Route::get('/test/login-as/{userId}', function ($userId) {
        $user = \App\Models\User::findOrFail($userId);
        auth()->login($user);

        return redirect()->route('dashboard')->with('success', "Logged in as {$user->name}");
    })->name('test.login-as');

    Route::get('/test/users', function () {
        $users = \App\Models\User::select('id', 'name', 'email', 'username')->get();

        return view('test.users', ['users' => $users]);
    })->name('test.users');
}
