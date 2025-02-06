<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\BorrowedBook;

class BookSeeder extends Seeder
{
    public function run()
    {
        // // Отключаем проверки внешних ключей
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // // Очищаем таблицы
        // BorrowedBook::query()->delete();
        // Book::query()->delete();

        // // Включаем обратно проверки внешних ключей
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Наполняем таблицы заново
        Book::factory(30)->create();
        BorrowedBook::factory(15)->create();
    }
}
