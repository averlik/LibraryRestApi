<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('user')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth.user');
});

Route::prefix('librarian')->group(function () {
    Route::post('/register', [AuthController::class, 'registerLibrarian']);
    Route::post('/login', [AuthController::class, 'loginLibrarian']);
    Route::post('/logout', [AuthController::class, 'logoutLibrarian'])->middleware('auth.librarian');
});

