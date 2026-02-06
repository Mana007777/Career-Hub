<?php

namespace App\Livewire\Validations;

class AddReplyValidation
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
            'content.required' => 'The reply content is required.',
            'content.max' => 'The reply may not be greater than 5000 characters.',
        ];
    }
}
