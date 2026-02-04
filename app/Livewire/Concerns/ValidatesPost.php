<?php

namespace App\Livewire\Concerns;

trait ValidatesPost
{
    /**
     * Get validation rules for creating a post.
     *
     * @return array<string, mixed>
     */
    protected function getCreatePostRules(): array
    {
        return [
            'content' => ['required', 'string', 'max:5000'],
            'media' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,mp4,avi,mov', 'max:10240'],
        ];
    }

    /**
     * Get validation rules for updating a post.
     *
     * @return array<string, mixed>
     */
    protected function getUpdatePostRules(): array
    {
        return [
            'editContent' => ['required', 'string', 'max:5000'],
            'editMedia' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,mp4,avi,mov', 'max:10240'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    protected function getPostValidationMessages(): array
    {
        return [
            'content.required' => 'The content field is required.',
            'content.max' => 'The content may not be greater than 5000 characters.',
            'editContent.required' => 'The content field is required.',
            'editContent.max' => 'The content may not be greater than 5000 characters.',
            'media.file' => 'The media must be a valid file.',
            'media.mimes' => 'The media must be a file of type: jpeg, jpg, png, gif, mp4, avi, mov.',
            'media.max' => 'The media may not be greater than 10MB.',
            'editMedia.file' => 'The media must be a valid file.',
            'editMedia.mimes' => 'The media must be a file of type: jpeg, jpg, png, gif, mp4, avi, mov.',
            'editMedia.max' => 'The media may not be greater than 10MB.',
        ];
    }
}
