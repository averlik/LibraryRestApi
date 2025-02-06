<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\BorrowedBook;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class BookTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_user_can_view_available_books()
    {
        // Создаем пользователя
        $user = User::factory()->create();

        // Авторизуем пользователя
        $this->actingAs($user, 'user');

        // Создаем книги (одна забронирована, одна доступна)
        Book::factory()->create(['is_borrowed' => false]);
        Book::factory()->create(['is_borrowed' => true]);

        // Отправляем запрос
        $response = $this->getJson('/api/books');

        // Проверяем ответ
        $response->assertStatus(200)
            ->assertJsonCount(1) // Ожидаем только 1 доступную книгу
            ->assertJsonFragment(['is_borrowed' => false]);
    }


    #[Test]
    public function librarian_can_create_a_book()
    {
        $librarian = User::factory()->create(); 

        $response = $this->actingAs($librarian, 'librarian')
            ->postJson('/api/librarian/books', [
                'title' => 'Новая книга',
                'author' => 'Александр Пушкин'
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Книга успешно добавлена']);

        $this->assertDatabaseHas('books', ['title' => 'Новая книга']);
    }

    #[Test]
    public function librarian_cannot_create_book_with_invalid_data()
    {
        $librarian = User::factory()->create(); 

        $response = $this->actingAs($librarian, 'librarian')
            ->postJson('/api/librarian/books', [
                'title' => '', // Пустое поле
                'author' => '12345' // Неверный формат
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'author']);
    }

    #[Test]
    public function test_user_can_borrow_a_book()
    {
        $user = User::factory()->create(); // Создаём пользователя
        $book = Book::factory()->create(['is_borrowed' => false]); // Создаём доступную книгу

        // Пользователь берёт книгу
        $response = $this->actingAs($user, 'user') // Используем 'user' guard
            ->postJson("/api/books/{$book->id}/borrow");

        // Проверка успешного ответа
        $response->assertStatus(200)
            ->assertJson(['message' => 'Книга успешно одолжена']);

        // Проверка, что книга занята
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => true
        ]);
    }

    #[Test]
    public function test_user_can_return_a_book()
    {
        $user = User::factory()->create(); // Создаем пользователя
        $book = Book::factory()->create(['is_borrowed' => true]); // Создаем занятую книгу

        // Создаем запись о том, что пользователь взял книгу
        BorrowedBook::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'is_returned' => false
        ]);

        // Пользователь возвращает книгу
        $response = $this->actingAs($user, 'user')
            ->postJson("/api/books/{$book->id}/return");

        // Проверка успешного ответа
        $response->assertStatus(200)
            ->assertJson(['message' => 'Книга успешно возвращена']);

        // Проверка, что книга снова доступна
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => false
        ]);
    }
    

}
