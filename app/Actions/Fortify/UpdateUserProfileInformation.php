<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Profile;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        $usernameBefore = $user->username;

        if (isset($input['username']) && $input['username'] === '') {
            $input['username'] = null;
        }
        
        Validator::make(
            $input,
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    \Illuminate\Validation\Rule::unique('users')->ignore($user->id),
                    'regex:/^[a-zA-Z0-9._%+\-]+@gmail\.com$/i',
                ],
                'username' => [
                    'nullable',
                    'string',
                    'max:255',
                    \Illuminate\Validation\Rule::unique('users')->ignore($user->id),
                    'regex:/^[a-z0-9_]+$/i',
                ],
                'bio' => ['nullable', 'string', 'max:1000'],
                'location' => ['nullable', 'string', 'max:255'],
                'website' => ['nullable', 'url', 'max:255'],
                'photo' => ['nullable', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            ],
            [
                'email.regex' => 'Please use a valid Gmail address (example@gmail.com).',
            ],
        )->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        
        $userData = [
            'name' => $input['name'],
            'email' => $input['email'],
        ];

        if (isset($input['username']) && !empty($input['username'])) {
            $userData['username'] = strtolower($input['username']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $userData);
        } else {
            $user->forceFill($userData)->save();
        }

        
        $profileData = [
            'bio' => $input['bio'] ?? null,
            'location' => $input['location'] ?? null,
            'website' => $input['website'] ?? null,
        ];

        $profile = $user->profile;
        if ($profile) {
            $profile->update($profileData);
        } else {
            $user->profile()->create($profileData);
        }

        $user->refresh();
        app(UserRepository::class)->clearUserCache(
            $user,
            $usernameBefore !== $user->username ? $usernameBefore : null
        );
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
