<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('borrowed_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('book_id')->constrained()->onDelete('cascade'); 
            $table->date('borrowed_at'); 
            $table->date('due_date'); // срок 
            $table->boolean('is_returned')->default(false); // статус 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('borrowed_books');
    }
};
