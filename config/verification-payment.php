<?php

return [
    // Amount in IQD charged for verification.
    'amount' => (int) env('VERIFICATION_PAYMENT_AMOUNT', 1000),

    // Optional override callback URL FIB calls after payment status change.
    'callback_url' => env('VERIFICATION_PAYMENT_CALLBACK_URL'),

    // Optional redirect URL used by FIB checkout flow.
    'redirect_url' => env('VERIFICATION_PAYMENT_REDIRECT_URL'),
];
