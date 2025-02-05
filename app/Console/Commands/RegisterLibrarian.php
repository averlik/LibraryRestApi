<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Librarian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterLibrarian extends Command
{
    protected $signature = 'librarian:register {name} {email} {password}';
    protected $description = 'Регистрация нового библиотекаря';

    private $allowedDomains = ['gmail.com', 'mail.ru', 'yandex.ru', 'yahoo.com'];

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255|regex:/^[a-zA-Zа-яА-ЯёЁ\s]+$/u',
            'email' => [
                'required',
                'string',
                'email',
                'unique:librarians,email',
                function ($attribute, $value, $fail) {
                    $domain = substr(strrchr($value, "@"), 1);
                    if (!in_array($domain, $this->allowedDomains)) {
                        $fail('Почта должна быть зарегистрирована на одном из следующих доменов: gmail.com, mail.ru, yandex.ru, yahoo.com.');
                    }
                },
            ],
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
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
