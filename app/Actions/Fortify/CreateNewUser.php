<?php

namespace App\Actions\Fortify;

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
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users', 'regex:/^[a-z0-9_]+$/i'],
            'role' => ['required', 'string', 'in:seeker,company'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // Use provided username or generate a unique one from email
        $username = !empty($input['username']) 
            ? strtolower($input['username'])
            : $this->generateUniqueUsername($input['email'], $input['name']);

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'username' => $username,
            'password' => Hash::make($input['password']),
            'role' => $input['role'], // Use selected role from form
        ]);
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
