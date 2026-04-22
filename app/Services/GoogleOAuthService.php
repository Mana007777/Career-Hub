<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GoogleOAuthService
{
    public function getAuthorizationUrl(): string
    {
        $state = $this->buildSignedOAuthState();

        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'prompt' => 'select_account',
        ]);

        return 'https://accounts.google.com/o/oauth2/v2/auth?'.$query;
    }

    public function handleCallback(string $code, ?string $stateFromRequest = null): User
    {
        if (! $this->verifySignedOAuthState($stateFromRequest)) {
            abort(403, 'Invalid Google OAuth state.');
        }

        $tokenPayload = $this->fetchAccessToken($code);
        $googleUser = $this->fetchGoogleUser((string) ($tokenPayload['access_token'] ?? ''));
        $email = $googleUser['email'] ?? null;

        if (! $email) {
            abort(400, 'Google did not provide an email address for this account.');
        }

        return $this->findOrCreateLocalUser($googleUser, $email);
    }

    protected function fetchAccessToken(string $code): array
    {
        $response = Http::asForm()
            ->acceptJson()
            ->post('https://oauth2.googleapis.com/token', [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => config('services.google.redirect'),
                'code' => $code,
                'grant_type' => 'authorization_code',
            ]);

        if (! $response->successful()) {
            abort(400, 'Failed to get access token from Google.');
        }

        $payload = $response->json();
        if (empty($payload['access_token'])) {
            abort(400, 'Google did not return an access token.');
        }

        return is_array($payload) ? $payload : [];
    }

    protected function fetchGoogleUser(string $token): array
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->get('https://www.googleapis.com/oauth2/v3/userinfo');

        if (! $response->successful()) {
            abort(400, 'Failed to fetch user information from Google.');
        }

        $payload = $response->json();

        return is_array($payload) ? $payload : [];
    }

    protected function findOrCreateLocalUser(array $googleUser, string $email): User
    {
        $googleId = (string) ($googleUser['sub'] ?? '');

        $user = User::where('email', $email)->first();
        if ($user) {
            if ($googleId !== '') {
                $user->forceFill(['google_id' => $googleId])->save();
            }

            return $user;
        }

        $name = (string) ($googleUser['name'] ?? $email);
        $baseUsername = Str::slug((string) ($googleUser['given_name'] ?? explode('@', $email)[0]), '_');
        if ($baseUsername === '') {
            $baseUsername = 'user_'.Str::lower(Str::random(6));
        }
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
            'role' => 'seeker',
        ]);

        if ($googleId !== '') {
            $user->forceFill(['google_id' => $googleId])->save();
        }

        if (($googleUser['email_verified'] ?? false) && method_exists($user, 'markEmailAsVerified')) {
            $user->markEmailAsVerified();
        }

        $this->setGoogleAvatar($user, $googleUser);

        return $user;
    }

    protected function setGoogleAvatar(User $user, array $googleUser): void
    {
        $avatarUrl = $googleUser['picture'] ?? null;
        if (! is_string($avatarUrl) || $avatarUrl === '') {
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
            $user->forceFill(['profile_photo_path' => $path])->save();
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function login(User $user, bool $remember = true): void
    {
        Auth::login($user, $remember);
        session(['logged_in_via_google' => true]);
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
