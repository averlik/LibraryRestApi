<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'is_borrowed', 'borrowed_by'];

    protected $casts = [
        'is_borrowed' => 'boolean',
    ];
}
