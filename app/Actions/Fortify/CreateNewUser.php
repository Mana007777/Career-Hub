<?php

namespace App\Actions\Fortify;

use App\Http\Requests\RegisterUserRequest;
use App\Jobs\SendUserNotification;
use App\Models\NotificationSetting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

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
        $request = app(RegisterUserRequest::class);

        Validator::make(
            $input,
            $request->rules()
        )->validate();

        // Use provided username or generate a unique one from email
        $username = !empty($input['username']) 
            ? strtolower($input['username'])
            : $this->generateUniqueUsername($input['email'], $input['name']);

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'username' => $username,
            'password' => Hash::make($input['password']),
            'role' => $input['role'], // Use selected role from form
        ]);

        // Create default notification settings
        NotificationSetting::create([
            'user_id' => $user->id,
        ]);

        // Queue a welcome notification for the new user (run on sync connection so it executes immediately)
        SendUserNotification::dispatch([
            'user_id' => $user->id,
            'source_user_id' => $user->id,
            'type' => 'welcome',
            'message' => 'Welcome to CareerOp! Your account has been successfully registered.',
        ])->onConnection('sync');

        return $user;
    }

    /**
     * Generate a unique username from email or name
     */
    private function generateUniqueUsername(string $email, string $name): string
    {
        // Extract username part from email (before @)
        $baseUsername = explode('@', $email)[0];
        
        // Clean the username: remove special characters, make lowercase
        $baseUsername = strtolower(preg_replace('/[^a-z0-9]/', '', $baseUsername));
        
        // If email-based username is too short, use name instead
        if (strlen($baseUsername) < 3) {
            $baseUsername = strtolower(preg_replace('/[^a-z0-9]/', '', $name));
        }
        
        // Ensure minimum length
        if (strlen($baseUsername) < 3) {
            $baseUsername = 'user' . substr(md5($email), 0, 6);
        }
        
        // Check if username exists, if so append numbers
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
}
