<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;


class AuthTest extends TestCase
{
    use RefreshDatabase; // Очищает БД перед каждым тестом

    #[Test]
    public function user_can_register()
    {
        $response = $this->postJson('/api/user/register', [
            'name' => 'Иван Иванов',
            'email' => 'test@gmail.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(201) // Код 201 - создано
            ->assertJson(['message' => 'Пользователь зарегистрирован']);

        $this->assertDatabaseHas('users', ['email' => 'test@gmail.com']);
    }

    #[Test]
    public function user_cannot_register_with_invalid_email()
    {
        $response = $this->postJson('/api/user/register', [
            'name' => 'Иван Иванов',
            'email' => 'test@invalid.com', // Неподдерживаемый домен
            'password' => 'password123'
        ]);

        $response->assertStatus(422) // Код 422 - ошибка валидации
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function user_cannot_register_with_invalid_name()
    {
        $response = $this->postJson('/api/user/register', [
            'name' => 'Иван Иванов 23',
            'email' => 'test@gmail.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(422) // Код 422 - ошибка валидации
            ->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/user/login', [
            'email' => 'test@gmail.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    #[Test]
    public function user_cannot_login_with_wrong_password()
    {
        User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/user/login', [
            'email' => 'test@gmail.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Неверные данные']);
    }
}
