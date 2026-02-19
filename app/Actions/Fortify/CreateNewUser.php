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

        
        $username = !empty($input['username']) 
            ? strtolower($input['username'])
            : $this->generateUniqueUsername($input['email'], $input['name']);

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'username' => $username,
            'password' => Hash::make($input['password']),
            'role' => $input['role'], 
        ]);

        
        NotificationSetting::create([
            'user_id' => $user->id,
        ]);

        
        SendUserNotification::dispatchSync([
            'user_id' => $user->id,
            'source_user_id' => $user->id,
            'type' => 'welcome',
            'message' => 'Welcome to CareerOp! Your account has been successfully registered.',
        ]);

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
            $baseUsername = 'user' . substr(md5($email), 0, 6);
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
