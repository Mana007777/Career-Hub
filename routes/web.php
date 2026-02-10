<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Show suspended account notice (for users whose accounts are suspended)
Route::get('/account/suspended', function () {
    return view('auth.suspended');
})->name('account.suspended');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/posts', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/posts/{slug}', function ($slug) {
        return view('dashboard', ['postSlug' => $slug]);
    })->name('posts.show');

    Route::get('/user/{username}', function ($username) {
        // Remove @ if present
        $username = ltrim($username);
        return view('dashboard', ['profileUsername' => $username]);
    })->name('user.profile');

    Route::get('/cvs', function () {
        return view('dashboard', ['showCvs' => true]);
    })->name('cvs');

    Route::get('/reports', function () {
        return view('dashboard', ['showReports' => true]);
    })->name('reports')->middleware('can:view-admin-panel');

    Route::get('/settings', function () {
        return view('dashboard', ['showSettings' => true]);
    })->name('settings');

    Route::get('/bookmarks', function () {
        return view('dashboard', ['showBookmarks' => true]);
    })->name('bookmarks');
});

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
