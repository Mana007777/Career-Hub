<?php

namespace App\Actions\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SendEmailVerification
{
    public function send(): void
    {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception('User must be authenticated.');
        }

        if ($user->email_verified_at) {
            throw new \Exception('Email is already verified.');
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            Log::error('Email verification failed: ' . $e->getMessage());
            throw new \Exception('Failed to send verification email. Please check your mail configuration.');
        }
    }
}
