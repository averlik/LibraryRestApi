<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Просмотр всех доступных книг
    public function index()
    {
        $books = Book::where('is_borrowed', false)->get();
        return response()->json($books);
    }

    // Создание книги (для библиотекарей)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
        ]);

        $book = Book::create($request->all());

        return response()->json(['message' => 'Книга добавлена', 'book' => $book], 201);
    }

    // Обновление книги (для библиотекарей)
    public function update(Request $request, Book $book)
    {
        $book->update($request->all());
        return response()->json(['message' => 'Книга обновлена', 'book' => $book]);
    }

    // Удаление книги (для библиотекарей)
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['message' => 'Книга удалена']);
    }
}

// namespace App\Http\Controllers;

// use App\Models\Book;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class BookController extends Controller
// {
//     // Просмотр всех доступных книг
//     public function index()
//     {
//         $books = Book::where('is_borrowed', false)->get();
//         return response()->json($books);
//     }

//     // Взять книгу (для пользователей)
//     public function borrow(Book $book)
//     {
//         if ($book->is_borrowed) {
//             return response()->json(['error' => 'Книга уже занята'], 400);
//         }

//         $book->is_borrowed = true;
//         $book->borrowed_by = Auth::id();
//         $book->save();

//         return response()->json(['message' => 'Вы взяли книгу']);
//     }

//     // Вернуть книгу (для пользователей)
//     public function return(Book $book)
//     {
//         if ($book->borrowed_by !== Auth::id()) {
//             return response()->json(['error' => 'Вы не брали эту книгу'], 403);
//         }

//         $book->is_borrowed = false;
//         $book->borrowed_by = null;
//         $book->save();

//         return response()->json(['message' => 'Вы вернули книгу']);
//     }

//     // Создание книги (для библиотекарей)
//     public function store(Request $request)
//     {
//         $request->validate([
//             'title' => 'required|string',
//             'author' => 'required|string',
//         ]);

//         $book = Book::create($request->all());

//         return response()->json(['message' => 'Книга добавлена', 'book' => $book], 201);
//     }

//     // Обновление книги  (для библиотекарей)
//     public function update(Request $request, Book $book)
//     {
//         $book->update($request->all());
//         return response()->json(['message' => 'Книга обновлена', 'book' => $book]);
//     }

//     // Удаление книги (для библиотекарей)
//     public function destroy(Book $book)
//     {
//         $book->delete();
//         return response()->json(['message' => 'Книга удалена']);
//     }
// }
