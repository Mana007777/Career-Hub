<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerificationPaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// shield:ignore: api
Route::post('/payments/fib/callback', [VerificationPaymentController::class, 'handleCallback'])
    ->middleware(['signed', 'throttle:20,1'])
    ->name('payments.fib.callback');
