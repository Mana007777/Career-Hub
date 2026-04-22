<?php

namespace App\Livewire\Auth;

use App\Services\GoogleOAuthService;
use Illuminate\Http\Request;
use Livewire\Component;

class GoogleCallback extends Component
{
    public ?string $error = null;

    public function mount(Request $request, GoogleOAuthService $googleOAuthService)
    {
        $code = $request->query('code');
        $state = $request->query('state');

        if (! $code) {
            $this->error = 'Missing authorization code from Google.';

            return;
        }

        try {
            $user = $googleOAuthService->handleCallback($code, $state);
            $googleOAuthService->login($user);

            redirect()->intended(route('dashboard'))->send();
        } catch (\Throwable $e) {
            report($e);
            $this->error = 'Google login failed. Please try again or use email/password.';
            if (app()->environment('local')) {
                $this->error .= ' Details: '.$e->getMessage();
            }
        }
    }

    public function render()
    {
        return view('livewire.auth.google-callback');
    }
}
