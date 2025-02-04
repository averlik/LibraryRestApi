<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Librarian;
use Illuminate\Support\Facades\Hash;

class RegisterLibrarian extends Command
{
    protected $signature = 'librarian:register {name} {email} {password}';
    protected $description = 'Регистрация нового библиотекаря';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Проверяем, существует ли библиотекарь с таким email
        if (Librarian::where('email', $email)->exists()) {
            $this->error('Библиотекарь с таким email уже существует.');
            return;
        }

        // Создаём библиотекаря
        Librarian::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info('Библиотекарь успешно зарегистрирован!');
    }
}
