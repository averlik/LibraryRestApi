<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
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
}
