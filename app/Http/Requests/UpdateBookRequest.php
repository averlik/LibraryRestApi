<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255',
            'author' => ['sometimes', 'string', 'max:255', 'regex:/^[a-zA-Zа-яА-ЯёЁ\s]+$/u'],
        ];
    }

    public function messages()
    {
        return [
            'author.regex' => 'Имя автора не должно содержать цифры или знаки пунктуации.',
        ];
    }
}
