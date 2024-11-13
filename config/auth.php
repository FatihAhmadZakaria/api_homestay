<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'user' => [ // Menambahkan guard 'user'
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'admins' => [ // Menambahkan provider 'admins'
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'api' => [
    'driver' => 'sanctum',
    'provider' => 'users',
    'hash' => false,
    ],


    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];
