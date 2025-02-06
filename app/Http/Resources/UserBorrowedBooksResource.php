<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBorrowedBooksResource extends JsonResource
{
  
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'borrowed_books' => $this->borrowedBooks->map(function ($borrowedBook) {
                return [
                    'id' => $borrowedBook->book->id,
                    'title' => $borrowedBook->book->title,
                    'author' => $borrowedBook->book->author,
                    'borrowed_at' => $borrowedBook->borrowed_at->format('Y-m-d H:i:s'),
                    'due_date' => $borrowedBook->due_date->format('Y-m-d H:i:s'),
                    'is_returned' => $borrowedBook->is_returned ? 'Да' : 'Нет',
                    'returned_at' => $borrowedBook->returned_at ? $borrowedBook->returned_at->format('Y-m-d H:i:s') : 'Не возвращено',
                ];
            }),
        ];
    }
}
