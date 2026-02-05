<?php

namespace App\Actions\Fortify;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use App\Models\Profile;
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
        $request = app(UpdateUserProfileRequest::class);

        Validator::make(
            $input,
            $request->rules(),
        )->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        // Update user information
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

        // Update or create profile
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
