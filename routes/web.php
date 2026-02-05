<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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
});
