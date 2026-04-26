<?php

namespace App\Actions\Fortify;

use App\Http\Requests\RegisterUserRequest;
use App\Jobs\SendUserNotification;
use App\Models\NotificationSetting;
use App\Models\User;
use App\Services\AiRecommendationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $normalizedInput = $input;
        $normalizedInput['email'] = strtolower(trim((string) ($input['email'] ?? '')));

        Validator::make(
            $normalizedInput,
            (new RegisterUserRequest)->rules(),
            [
                'email.unique' => Lang::has('validation.unique')
                    ? __('validation.unique', ['attribute' => 'email'])
                    : 'This email is already registered.',
            ]
        )->validate();

        
        $username = !empty($normalizedInput['username'])
            ? strtolower($normalizedInput['username'])
            : $this->generateUniqueUsername($normalizedInput['email'], $normalizedInput['name']);

        $user = User::create([
            'name' => $normalizedInput['name'],
            'email' => $normalizedInput['email'],
            'username' => $username,
            'password' => Hash::make($normalizedInput['password']),
            'role' => $normalizedInput['role'],
        ]);

        $user->forceFill(['password_set_at' => now()])->save();

        NotificationSetting::create([
            'user_id' => $user->id,
        ]);

        
        SendUserNotification::dispatchSync([
            'user_id' => $user->id,
            'source_user_id' => $user->id,
            'type' => 'welcome',
            'message' => 'Welcome to CareerOp! Your account has been successfully registered.',
        ]);

        app(AiRecommendationService::class)->registerUser($user, []);

        return $user;
    }

    /**
     * Generate a unique username from email or name
     */
    private function generateUniqueUsername(string $email, string $name): string
    {

        $baseUsername = explode('@', $email)[0];
        
        
        $baseUsername = strtolower(preg_replace('/[^a-z0-9]/', '', $baseUsername));
        
        if (strlen($baseUsername) < 3) {
            $baseUsername = strtolower(preg_replace('/[^a-z0-9]/', '', $name));
        }
        
        if (strlen($baseUsername) < 3) {
            $baseUsername = 'user' . substr(hash('sha256', $email), 0, 6);
        }
        
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
}
