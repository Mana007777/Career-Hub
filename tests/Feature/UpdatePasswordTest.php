<?php

use App\Livewire\Profile\UpdatePasswordForm;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('password can be updated', function () {
    $this->actingAs($user = User::factory()->create());

    Livewire::test(UpdatePasswordForm::class)
        ->set('state.current_password', 'password')
        ->set('state.password', 'new-password-123')
        ->set('state.password_confirmation', 'new-password-123')
        ->call('updatePassword')
        ->assertHasNoErrors();

    expect(Hash::check('new-password-123', $user->fresh()->password))->toBeTrue();
});

test('current password must be correct', function () {
    $this->actingAs($user = User::factory()->create());

    Livewire::test(UpdatePasswordForm::class)
        ->set('state', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->call('updatePassword')
        ->assertHasErrors(['current_password']);

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});

test('new passwords must match', function () {
    $this->actingAs($user = User::factory()->create());

    Livewire::test(UpdatePasswordForm::class)
        ->set('state', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'wrong-password',
        ])
        ->call('updatePassword')
        ->assertHasErrors(['password']);

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});

test('github user can set password without current password', function () {
    $this->actingAs($user = User::factory()->withGithub()->create());

    Livewire::test(UpdatePasswordForm::class)
        ->set('state.password', 'new-password-456')
        ->set('state.password_confirmation', 'new-password-456')
        ->call('updatePassword')
        ->assertHasNoErrors();

    $user->refresh();
    expect(Hash::check('new-password-456', $user->password))->toBeTrue()
        ->and($user->password_set_at)->not->toBeNull();
});

test('github user must provide current password after first password is set', function () {
    $this->actingAs($user = User::factory()->withGithub()->create());

    Livewire::test(UpdatePasswordForm::class)
        ->set('state.password', 'first-password-789')
        ->set('state.password_confirmation', 'first-password-789')
        ->call('updatePassword')
        ->assertHasNoErrors();

    Livewire::test(UpdatePasswordForm::class)
        ->set('state.password', 'second-password-000')
        ->set('state.password_confirmation', 'second-password-000')
        ->call('updatePassword')
        ->assertHasErrors(['current_password']);

    Livewire::test(UpdatePasswordForm::class)
        ->set('state.current_password', 'first-password-789')
        ->set('state.password', 'second-password-000')
        ->set('state.password_confirmation', 'second-password-000')
        ->call('updatePassword')
        ->assertHasNoErrors();

    expect(Hash::check('second-password-000', $user->fresh()->password))->toBeTrue();
});
