<?php

use App\Models\User;
use App\Models\Verification;
use FirstIraqiBank\FIBPaymentSDK\Model\FibPayment;

test('user cannot create checkout for another users verification', function () {
    $owner = User::factory()->withPersonalTeam()->create();
    $otherUser = User::factory()->withPersonalTeam()->create();
    $verification = Verification::factory()->create([
        'user_id' => $owner->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($otherUser)
        ->postJson(route('verification-payments.checkout', $verification));

    $response->assertForbidden();
});

test('fib callback updates verification payment status and paid timestamp', function () {
    $verification = Verification::factory()->create([
        'fib_payment_id' => 'fib-payment-123',
        'payment_status' => FibPayment::PENDING,
        'paid_at' => null,
    ]);

    $response = $this->postJson(route('payments.fib.callback'), [
        'id' => 'fib-payment-123',
        'status' => FibPayment::PAID,
    ]);

    $response->assertOk()
        ->assertJson(['message' => 'Callback processed successfully.']);

    expect($verification->fresh()->payment_status)->toBe(FibPayment::PAID)
        ->and($verification->fresh()->paid_at)->not->toBeNull();
});
