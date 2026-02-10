<?php

namespace App\Livewire\Auth;

use App\Services\GithubOAuthService;
use Illuminate\Http\Request;
use Livewire\Component;

class GithubCallback extends Component
{
    public ?string $error = null;

    public function mount(Request $request, GithubOAuthService $githubOAuthService)
    {
        $code = $request->query('code');
        $state = $request->query('state');

        if (!$code) {
            $this->error = 'Missing authorization code from GitHub.';
            return;
        }

        try {
            $user = $githubOAuthService->handleCallback($code, $state);
            $githubOAuthService->login($user);

            redirect()->intended(route('dashboard'))->send();
        } catch (\Throwable $e) {
            $this->error = 'GitHub login failed. Please try again or use email/password.';
            report($e);
        }
    }

    public function render()
    {
        return view('livewire.auth.github-callback');
    }
}

