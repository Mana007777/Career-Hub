<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Structural/media validation; main field validation is handled in Livewire.
            'media' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,mp4,avi,mov', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'media.file' => 'The media must be a valid file.',
            'media.mimes' => 'The media must be a file of type: jpeg, jpg, png, gif, mp4, avi, mov.',
            'media.max' => 'The media may not be greater than 10MB.',
        ];
    }
}

