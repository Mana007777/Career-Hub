<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GithubOAuthService
{
    public function getAuthorizationUrl(): string
    {
        $clientId = config('services.github.client_id');
        $redirectUri = config('services.github.redirect');

        $state = Str::random(40);
        session(['github_oauth_state' => $state]);

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => 'read:user user:email',
            'state' => $state,
            'allow_signup' => 'true',
        ]);

        return 'https://github.com/login/oauth/authorize?' . $query;
    }

    public function handleCallback(string $code, ?string $stateFromRequest = null): User
    {
        $expectedState = session('github_oauth_state');
        session()->forget('github_oauth_state');

        if (!$expectedState || !$stateFromRequest || !hash_equals($expectedState, $stateFromRequest)) {
            abort(403, 'Invalid GitHub OAuth state.');
        }

        $token = $this->fetchAccessToken($code);

        $githubUser = $this->fetchGithubUser($token);
        $email = $this->resolvePrimaryEmail($token, $githubUser);

        if (!$email) {
            abort(400, 'GitHub did not provide an email address for this account.');
        }

        return $this->findOrCreateLocalUser($githubUser, $email);
    }

    protected function fetchAccessToken(string $code): string
    {
        $response = Http::asForm()
            ->acceptJson()
            ->post('https://github.com/login/oauth/access_token', [
                'client_id' => config('services.github.client_id'),
                'client_secret' => config('services.github.client_secret'),
                'code' => $code,
                'redirect_uri' => config('services.github.redirect'),
            ]);

        if (!$response->successful()) {
            abort(400, 'Failed to get access token from GitHub.');
        }

        $token = $response->json()['access_token'] ?? null;

        if (!$token) {
            abort(400, 'GitHub did not return an access token.');
        }

        return $token;
    }

    protected function fetchGithubUser(string $token): array
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->get('https://api.github.com/user');

        if (!$response->successful()) {
            abort(400, 'Failed to fetch user information from GitHub.');
        }

        return $response->json();
    }

    protected function resolvePrimaryEmail(string $token, array $githubUser): ?string
    {
        if (!empty($githubUser['email'])) {
            return $githubUser['email'];
        }

        $response = Http::withToken($token)
            ->acceptJson()
            ->get('https://api.github.com/user/emails');

        if (!$response->successful()) {
            return null;
        }

        $emails = $response->json();
        $primary = collect($emails)->firstWhere('primary', true)
            ?? collect($emails)->firstWhere('verified', true)
            ?? Arr::first($emails);

        return is_array($primary) ? ($primary['email'] ?? null) : null;
    }

    protected function findOrCreateLocalUser(array $githubUser, string $email): User
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            return $user;
        }

        $name = $githubUser['name'] ?? $githubUser['login'] ?? $email;
        $login = $githubUser['login'] ?? explode('@', $email)[0];

        $baseUsername = Str::slug($login, '_');
        $username = $baseUsername;
        $suffix = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . $suffix++;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => Str::password(32),
            'role' => 'seeker', // sensible default; user can change later
        ]);

        // Mark email as verified since it comes from GitHub
        if (method_exists($user, 'markEmailAsVerified')) {
            $user->markEmailAsVerified();
        }

        // Try to download and set GitHub avatar as profile photo
        $this->setGithubAvatar($user, $githubUser);

        return $user;
    }

    protected function setGithubAvatar(User $user, array $githubUser): void
    {
        $avatarUrl = $githubUser['avatar_url'] ?? null;

        if (!$avatarUrl) {
            return;
        }

        try {
            $response = Http::get($avatarUrl);

            if (!$response->successful()) {
                return;
            }

            $extension = 'jpg';
            $contentType = $response->header('Content-Type');
            if (is_string($contentType) && str_contains($contentType, 'png')) {
                $extension = 'png';
            }

            $path = 'profile-photos/' . Str::uuid() . '.' . $extension;

            Storage::disk('public')->put($path, $response->body());

            // Use forceFill so we don't depend on fillable
            $user->forceFill([
                'profile_photo_path' => $path,
            ])->save();
        } catch (\Throwable $e) {
            // Fail silently; avatar isn't critical
            report($e);
        }
    }

    public function login(User $user, bool $remember = true)
    {
        Auth::login($user, $remember);
    }
}

