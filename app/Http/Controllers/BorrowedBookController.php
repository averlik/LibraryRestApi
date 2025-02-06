<?php

namespace App\Http\Controllers;

use App\Models\BorrowedBook;
use App\Models\Book;
use App\Http\Resources\UserBorrowedBooksResource;
use App\Http\Resources\BorrowedBookResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorrowedBookController extends Controller
{
    // Просмотр всех записей (для библиотекарей)
    public function index()
    {
        $usersWithBooks = User::whereHas('borrowedBooks')->with('borrowedBooks.book')->get();
        return UserBorrowedBooksResource::collection($usersWithBooks);
    }

    // Просмотр одолжанных книг (для пользователя)
    public function userBorrowedBooks()
    {

        $user = Auth::user();
        $borrowedBooks = BorrowedBook::where('user_id', $user->id)->get();

        if ($borrowedBooks->isEmpty()) {
            return response()->json([
                'message' => 'У вас нет одолжанных книг.',
                'borrowed_books' => []
            ]);
        }

        return response()->json([
            'message' => 'Вот ваш список одолжанных книг:',
            'borrowed_books' => BorrowedBookResource::collection($borrowedBooks)
        ]);
    }

    // Взять книгу
    public function borrow(Book $book)
    {
        if ($book->is_borrowed) {
            return response()->json(['error' => 'Книга уже занята'], 400);
        }

        $book->is_borrowed = true;
        $book->save();

        BorrowedBook::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'borrowed_at' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(14), // Срок возврата — 14 дней
        ]);

        return response()->json(['message' => 'Книга успешно одолжена', 'due_date' => Carbon::now()->addDays(14)]);
    }

    // Вернуть книгу
    public function return(Book $book)
    {
        $borrowedBook = BorrowedBook::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->where('is_returned', false)
            ->first();

        if (!$borrowedBook) {
            return response()->json(['error' => 'Вы не брали эту книгу'], 403);
        }

        $borrowedBook->update([
            'is_returned' => true,
            'returned_at' => now(),
        ]);

        $book->update(['is_borrowed' => false]);

        return response()->json(['message' => 'Книга успешно возвращена']);
    }

}
