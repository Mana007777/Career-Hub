<?php

return [
    
    'amount' => (int) env('VERIFICATION_PAYMENT_AMOUNT', 1000),

    
    'callback_url' => env('VERIFICATION_PAYMENT_CALLBACK_URL'),

   
    'redirect_url' => env('VERIFICATION_PAYMENT_REDIRECT_URL'),
];
