<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'author' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s-]+$/u'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Название книги обязательно.',
            'title.string' => 'Название книги должно быть строкой.',
            'title.max' => 'Название книги не должно превышать 255 символов.',
            'author.required' => 'Имя автора обязательно.',
            'author.string' => 'Имя автора должно быть строкой.',
            'author.max' => 'Имя автора не должно превышать 255 символов.',
            'author.regex' => 'Имя автора не должно содержать цифры или знаки пунктуации.',
        ];
    }
}
