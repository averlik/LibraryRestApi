<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\BookController;

// //аунтификация для пользователей
// Route::prefix('user')->group(function () {
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth.user');
// });

// //аунтификация для библиотекарей
// Route::prefix('librarian')->group(function () {
//     Route::post('/login', [AuthController::class, 'loginLibrarian']);
//     Route::post('/logout', [AuthController::class, 'logoutLibrarian'])->middleware('auth.librarian');
// });

// //маршруты для пользователей
// Route::middleware('auth:user')->group(function () {
//     Route::get('/books', [BookController::class, 'index']); // Просмотр доступных книг
//     Route::post('/books/{book}/borrow', [BookController::class, 'borrow']); // Взять книгу
//     Route::post('/books/{book}/return', [BookController::class, 'return']); // Сдать книгу
// });

// //маршруты для библиотекарей
// Route::middleware('auth:librarian')->prefix('librarian')->group(function () {
//     Route::post('/books', [BookController::class, 'store']); // Создание книги
//     Route::get('/books', [BookController::class, 'index']); // Просмотр книг
//     Route::put('/books/{book}', [BookController::class, 'update']); // Изменение книги
//     Route::delete('/books/{book}', [BookController::class, 'destroy']); // Удаление книги
// });

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowedBookController;

// Аутентификация для пользователей
Route::prefix('user')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:user');
});

// Аутентификация для библиотекарей
Route::prefix('librarian')->group(function () {
    Route::post('/login', [AuthController::class, 'loginLibrarian']);
    Route::post('/logout', [AuthController::class, 'logoutLibrarian'])->middleware('auth:librarian');
});

// Маршруты для пользователей
Route::middleware('auth:user')->group(function () {
    Route::get('/books', [BookController::class, 'index']); // Просмотр доступных книг
    Route::post('/books/{book}/borrow', [BorrowedBookController::class, 'borrow']); // Взять книгу
    Route::post('/books/{book}/return', [BorrowedBookController::class, 'return']); // Сдать книгу
    Route::get('/borrowed-books/user', [BorrowedBookController::class, 'userBorrowedBooks']); // Просмотр одолженных книг текущего пользователя
});

// Маршруты для библиотекарей
Route::middleware('auth:librarian')->prefix('librarian')->group(function () {
    // Управление книгами
    Route::post('/books', [BookController::class, 'store']); // Создание книги
    Route::get('/books', [BookController::class, 'index']); // Просмотр всех книг
    Route::put('/books/{book}', [BookController::class, 'update']); // Обновление книги
    Route::delete('/books/{book}', [BookController::class, 'destroy']); // Удаление книги

    // Управление одолженными книгами
    Route::get('/borrowed-books', [BorrowedBookController::class, 'index']); // Просмотр всех записей об одолженных книгах
});

