<?php

namespace App\Livewire\Profile;

use App\Actions\User\SendEmailVerification as SendEmailVerificationAction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SendEmailVerification extends Component
{
    public $verificationLinkSent = false;
    public $user;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function sendEmailVerification(SendEmailVerificationAction $sendEmailVerificationAction): void
    {
        try {
            $sendEmailVerificationAction->send();

            $this->verificationLinkSent = true;
            
            session()->flash('verification-link-sent', true);
            
            $this->dispatch('verification-sent');
        } catch (\Exception $e) {
            session()->flash('verification-error', 'Failed to send verification email. Please try again.');
            
            $this->dispatch('verification-error');
        }
    }

    public function render(): View
    {
        return view('livewire.profile.send-email-verification');
    }
}
