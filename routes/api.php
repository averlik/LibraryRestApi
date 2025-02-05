<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;

Route::prefix('user')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth.user');
});

Route::prefix('librarian')->group(function () {
    Route::post('/login', [AuthController::class, 'loginLibrarian']);
    Route::post('/logout', [AuthController::class, 'logoutLibrarian'])->middleware('auth.librarian');
});

Route::middleware('auth:user')->group(function () {
    Route::get('/books', [BookController::class, 'index']); // Просмотр доступных книг
    Route::post('/books/{book}/borrow', [BookController::class, 'borrow']); // Взять книгу
    Route::post('/books/{book}/return', [BookController::class, 'return']); // Сдать книгу
});

Route::middleware('auth:librarian')->prefix('librarian')->group(function () {
    Route::post('/books', [BookController::class, 'store']); // Создание книги
    Route::get('/books', [BookController::class, 'index']); // Просмотр книг
    Route::put('/books/{book}', [BookController::class, 'update']); // Изменение книги
    Route::delete('/books/{book}', [BookController::class, 'destroy']); // Удаление книги
});