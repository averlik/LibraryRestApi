<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BorrowedBook;
use App\Models\Book;
use App\Models\User;

class BorrowedBookFactory extends Factory
{
    protected $model = BorrowedBook::class;

    public function definition()
    {
        $returnedAt = $this->faker->boolean(50) ? $this->faker->dateTimeBetween('-1 month', 'now') : null;
        $isReturned = $returnedAt !== null;
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $book = Book::where('is_borrowed', false)->inRandomOrder()->first() ?? Book::factory()->create();

        // Создаем запись в borrowed_books
        $borrowedBook = [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => $this->faker->dateTimeBetween('-2 months', '-1 week'),
            'due_date' => $this->faker->dateTimeBetween('-1 week', '+1 month'),
            'is_returned' => $isReturned,
            'returned_at' => $returnedAt,
        ];

    
        if (!$isReturned) {
            $book->update([
                'is_borrowed' => true,
                'borrowed_by' => $user->id,
            ]);
        }

        return $borrowedBook;
    }
}
