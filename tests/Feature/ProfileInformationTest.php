<?php

use App\Models\User;
use App\Livewire\Profile\UpdateProfileInformationForm;
use Livewire\Livewire;

test('current profile information is available', function () {
    $user = User::factory()->create();
    \App\Models\Profile::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $component = Livewire::test(UpdateProfileInformationForm::class);

    expect($component->state['name'])->toEqual($user->name);
    expect($component->state['email'])->toEqual($user->email);
});

test('profile information can be updated', function () {
    $user = User::factory()->create();
    \App\Models\Profile::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $newEmail = 'test' . uniqid() . '@example.com';

    $component = Livewire::test(UpdateProfileInformationForm::class)
        ->set('state.name', 'Test Name')
        ->set('state.email', $newEmail);
    
    // Only set username if it exists and is not empty
    if (!empty($user->username)) {
        $component->set('state.username', $user->username);
    }
    
    $component->set('state.bio', '')
        ->set('state.location', '')
        ->set('state.website', '')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    $user->refresh();
    expect($user->name)->toEqual('Test Name')
        ->and($user->email)->toEqual($newEmail);
});
