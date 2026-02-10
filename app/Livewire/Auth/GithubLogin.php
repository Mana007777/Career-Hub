<?php

namespace App\Livewire\Auth;

use App\Services\GithubOAuthService;
use Livewire\Component;

class GithubLogin extends Component
{
    public function redirectToGithub(GithubOAuthService $githubOAuthService)
    {
        $url = $githubOAuthService->getAuthorizationUrl();

        return redirect()->away($url);
    }

    public function render()
    {
        return view('livewire.auth.github-login');
    }
}

