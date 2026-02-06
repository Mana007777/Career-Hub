<?php

namespace App\Livewire\Validations;

class AddCommentValidation
{
    public static function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:5000'],
        ];
    }

    public static function messages(): array
    {
        return [
            'content.required' => 'The comment content is required.',
            'content.max' => 'The comment may not be greater than 5000 characters.',
        ];
    }
}
