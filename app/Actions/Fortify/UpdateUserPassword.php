<?php

namespace App\Actions\Fortify;

use App\Http\Requests\UpdateUserPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        $request = app(UpdateUserPasswordRequest::class);

        Validator::make(
            $input,
            $request->rules(),
            [
                'current_password.current_password' => __('The provided password does not match your current password.'),
            ]
        )->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
