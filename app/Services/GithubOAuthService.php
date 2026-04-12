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

        // Signed state survives GitHub's cross-site redirect without relying on the session
        // cookie (Strict SameSite, localhost vs 127.0.0.1, etc.).
        $state = $this->buildSignedOAuthState();

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => 'read:user user:email',
            'state' => $state,
            'allow_signup' => 'true',
        ]);

        return 'https://github.com/login/oauth/authorize?'.$query;
    }

    public function handleCallback(string $code, ?string $stateFromRequest = null): User
    {
        if (! $this->verifySignedOAuthState($stateFromRequest)) {
            abort(403, 'Invalid GitHub OAuth state.');
        }

        $token = $this->fetchAccessToken($code);

        $githubUser = $this->fetchGithubUser($token);
        $email = $this->resolvePrimaryEmail($token, $githubUser);

        if (! $email) {
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

        if (! $response->successful()) {
            abort(400, 'Failed to get access token from GitHub.');
        }

        $token = $response->json()['access_token'] ?? null;

        if (! $token) {
            abort(400, 'GitHub did not return an access token.');
        }

        return $token;
    }

    protected function fetchGithubUser(string $token): array
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->get('https://api.github.com/user');

        if (! $response->successful()) {
            abort(400, 'Failed to fetch user information from GitHub.');
        }

        return $response->json();
    }

    protected function resolvePrimaryEmail(string $token, array $githubUser): ?string
    {
        if (! empty($githubUser['email'])) {
            return $githubUser['email'];
        }

        $response = Http::withToken($token)
            ->acceptJson()
            ->get('https://api.github.com/user/emails');

        if (! $response->successful()) {
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
            $username = $baseUsername.'_'.$suffix++;
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

        if (! $avatarUrl) {
            return;
        }

        try {
            $response = Http::get($avatarUrl);

            if (! $response->successful()) {
                return;
            }

            $extension = 'jpg';
            $contentType = $response->header('Content-Type');
            if (is_string($contentType) && str_contains($contentType, 'png')) {
                $extension = 'png';
            }

            $path = 'profile-photos/'.Str::uuid().'.'.$extension;

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

        // Remember in this session that the user authenticated via GitHub,
        // so we can show provider-specific guidance in the UI (e.g. on /user/profile)
        session(['logged_in_via_github' => true]);
    }

    private function buildSignedOAuthState(): string
    {
        $expiresAt = now()->addMinutes(10)->getTimestamp();
        $nonce = Str::random(32);
        $payload = $expiresAt.'|'.$nonce;
        $signature = hash_hmac('sha256', $payload, (string) config('app.key'));

        return rtrim(strtr(base64_encode($payload.'|'.$signature), '+/', '-_'), '=');
    }

    private function verifySignedOAuthState(?string $state): bool
    {
        if ($state === null || $state === '') {
            return false;
        }

        $decoded = base64_decode(strtr($state, '-_', '+/'), true);
        if ($decoded === false) {
            return false;
        }

        $parts = explode('|', $decoded, 3);
        if (count($parts) !== 3) {
            return false;
        }

        [$expiresAt, $nonce, $signature] = $parts;
        if (! ctype_digit((string) $expiresAt) || (int) $expiresAt < now()->getTimestamp()) {
            return false;
        }

        $payload = $expiresAt.'|'.$nonce;

        return hash_equals(
            hash_hmac('sha256', $payload, (string) config('app.key')),
            $signature
        );
    }
}
