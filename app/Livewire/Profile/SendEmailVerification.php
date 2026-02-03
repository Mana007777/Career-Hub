<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SendEmailVerification extends Component
{
    public $verificationLinkSent = false;
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function sendEmailVerification()
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return;
        }

        try {
            // Send email verification notification
            $user->sendEmailVerificationNotification();

            $this->verificationLinkSent = true;
            
            session()->flash('verification-link-sent', true);
            
            $this->dispatch('verification-sent');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Email verification failed: ' . $e->getMessage());
            
            session()->flash('verification-error', 'Failed to send verification email. Please check your mail configuration.');
            
            $this->dispatch('verification-error');
        }
    }

    public function render()
    {
        return view('livewire.profile.send-email-verification');
    }
}
