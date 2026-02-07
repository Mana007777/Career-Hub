<?php

use Laravel\Reverb\Protocols\Pusher;

return [
    /*
    |--------------------------------------------------------------------------
    | Reverb Server Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the Reverb server settings. Reverb is Laravel's
    | WebSocket server that allows you to build real-time applications using
    | WebSockets instead of requiring polling.
    |
    */

    'app' => [
        'id' => env('REVERB_APP_ID', 'app-id'),
        'key' => env('REVERB_APP_KEY', 'app-key'),
        'secret' => env('REVERB_APP_SECRET', 'app-secret'),
    ],

    'host' => env('REVERB_HOST', '127.0.0.1'),
    'port' => env('REVERB_PORT', 8080),
    'scheme' => env('REVERB_SCHEME', 'http'),

    'allowed_origins' => [
        env('APP_URL', 'http://localhost'),
    ],

    'ping_interval' => env('REVERB_PING_INTERVAL', 30),

    /*
    |--------------------------------------------------------------------------
    | Reverb HTTP API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the HTTP API settings for Reverb. This API
    | allows you to interact with Reverb from your application.
    |
    */

    'http' => [
        'prefix' => env('REVERB_HTTP_PREFIX', 'reverb'),
        'middleware' => [
            'web',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Reverb Protocol Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the protocol settings for Reverb. The protocol
    | determines how clients connect to and interact with Reverb.
    |
    */

    'protocol' => Pusher::class,

];
