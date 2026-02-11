<?php

namespace App\Actions\User;

use App\Exceptions\AuthenticationRequiredException;
use App\Exceptions\EmailVerificationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SendEmailVerification
{
    public function send(): void
    {
        $user = Auth::user();

        if (!$user) {
            throw new AuthenticationRequiredException('User must be authenticated.');
        }

        if ($user->email_verified_at) {
            throw new EmailVerificationException('Email is already verified.');
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            Log::error('Email verification failed: ' . $e->getMessage());
            throw new EmailVerificationException('Failed to send verification email. Please check your mail configuration.');
        }
    }
}
