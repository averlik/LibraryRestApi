<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::where('is_borrowed', false)->get();
        return response()->json($books);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:books,title',
            'author' => 'required|string|max:255|regex:/^[\p{L}\s-]+$/u'
        ]);

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author
        ]);

        return response()->json(['message' => 'Книга успешно добавлена', 'book' => $book], 201);
    }

    // Обновление данных книги
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['error' => 'Книга не найдена'], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255|unique:books,title,' . $id . ',id,author,' . $request->author,
            'author' => 'sometimes|required|string|max:255|regex:/^[\p{L}\s-]+$/u',
        ]);
        
        $book->update($request->only(['title', 'author']));

        return response()->json(['message' => 'Книга успешно обновлена', 'book' => $book], 200);
    }

    public function destroy(Book $book)
    {   
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json(['error' => 'Книга не найдена'], 404);
        }
        
        $book->delete();
        
        return response()->json(['message' => 'Книга удалена']);
    }
}
