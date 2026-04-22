<?php

namespace App\Livewire\Auth;

use App\Services\GoogleOAuthService;
use Livewire\Component;

class GoogleLogin extends Component
{
    public function redirectToGoogle(GoogleOAuthService $googleOAuthService)
    {
        $url = $googleOAuthService->getAuthorizationUrl();

        return redirect()->away($url);
    }

    public function render()
    {
        return view('livewire.auth.google-login');
    }
}
