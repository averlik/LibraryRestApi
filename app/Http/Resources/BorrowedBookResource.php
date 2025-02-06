<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowedBookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->book->id,
            'title' => $this->book->title,
            'author' => $this->book->author,
            'borrowed_at' => $this->borrowed_at->format('Y-m-d H:i:s'),
            'due_date' => $this->due_date->format('Y-m-d H:i:s'),
            'is_returned' => $this->is_returned ? 'Да' : 'Нет',
            'returned_at' => $this->returned_at ? $this->returned_at->format('Y-m-d H:i:s') : 'Не возвращено',
        ];
    }
}
