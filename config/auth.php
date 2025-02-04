<?php
return [
    'defaults' => [
        'guard' => 'user', // По умолчанию обычный пользователь
        'passwords' => 'users',
    ],

    'guards' => [
        'user' => [
            'driver' => 'jwt',
            'provider' => 'users',
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
