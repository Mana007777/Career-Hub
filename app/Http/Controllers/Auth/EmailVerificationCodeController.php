<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class EmailVerificationCodeController
{
    public function send(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $code = (string) random_int(100000, 999999);
        $cacheKey = $this->cacheKey((int) $user->id);
        Cache::put($cacheKey, [
            'hash' => hash('sha256', $code),
            'expires_at' => now()->addMinutes(10)->timestamp,
        ], now()->addMinutes(10));

        Mail::raw(
            "Your Career Hub verification code is: {$code}\n\nThis code expires in 10 minutes.",
            function ($message) use ($user): void {
                $message->to($user->email)->subject('Career Hub verification code');
            }
        );

        return back()->with('verification_code_sent', 'Verification code sent. Check your inbox.');
    }

    public function verify(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'verification_code' => ['required', 'digits:6'],
        ]);

        $payload = Cache::get($this->cacheKey((int) $user->id));
        if (! is_array($payload) || empty($payload['hash']) || empty($payload['expires_at'])) {
            return back()->withErrors([
                'verification_code' => 'Verification code expired. Please request a new code.',
            ]);
        }

        if ((int) $payload['expires_at'] < now()->timestamp) {
            Cache::forget($this->cacheKey((int) $user->id));

            return back()->withErrors([
                'verification_code' => 'Verification code expired. Please request a new code.',
            ]);
        }

        $incomingHash = hash('sha256', (string) $validated['verification_code']);
        if (! hash_equals((string) $payload['hash'], $incomingHash)) {
            return back()->withErrors([
                'verification_code' => 'Invalid verification code.',
            ]);
        }

        $user->markEmailAsVerified();
        Cache::forget($this->cacheKey((int) $user->id));

        return redirect()->route('dashboard')->with('status', 'Email verified successfully.');
    }

    private function cacheKey(int $userId): string
    {
        return "email_verification_code:{$userId}";
    }
}
