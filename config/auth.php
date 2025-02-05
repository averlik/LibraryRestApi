<?php
return [
    'defaults' => [
        'guard' => 'user', 
        'passwords' => 'users',
    ],

    'guards' => [
        'user' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
            'hash' => false,
        ],
        'librarian' => [
            'driver' => 'jwt',
            'provider' => 'librarians',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'librarians' => [
            'driver' => 'eloquent',
            'model' => App\Models\Librarian::class,
        ],
    ],
];
