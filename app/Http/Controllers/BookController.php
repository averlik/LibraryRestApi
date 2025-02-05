<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::where('is_borrowed', false)->get();
        return response()->json($books);
    }

    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());
        return response()->json(['message' => 'Книга добавлена', 'book' => $book], 201);
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());
        return response()->json(['message' => 'Книга обновлена', 'book' => $book]);
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['message' => 'Книга удалена']);
    }
}

// namespace App\Http\Controllers;

// use App\Models\Book;
// use Illuminate\Http\Request;

// class BookController extends Controller
// {
//     // Просмотр всех доступных книг
//     public function index()
//     {
//         $books = Book::where('is_borrowed', false)->get();
//         return response()->json($books);
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

//     // Обновление книги (для библиотекарей)
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

