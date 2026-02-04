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
            'specialties' => ['required', 'array', 'min:1'],
            'specialties.*.specialty_name' => ['required', 'string', 'max:255'],
            'specialties.*.sub_specialty_name' => ['required', 'string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*.name' => ['required', 'string', 'max:255'],
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
            'editSpecialties' => ['required', 'array', 'min:1'],
            'editSpecialties.*.specialty_name' => ['required', 'string', 'max:255'],
            'editSpecialties.*.sub_specialty_name' => ['required', 'string', 'max:255'],
            'editTags' => ['nullable', 'array'],
            'editTags.*.name' => ['required', 'string', 'max:255'],
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
            'specialties.required' => 'Please add at least one specialty and sub-specialty.',
            'specialties.min' => 'Please add at least one specialty and sub-specialty.',
            'specialties.*.specialty_name.required' => 'Specialty name is required.',
            'specialties.*.specialty_name.max' => 'Specialty name may not be greater than 255 characters.',
            'specialties.*.sub_specialty_name.required' => 'Sub-specialty name is required.',
            'specialties.*.sub_specialty_name.max' => 'Sub-specialty name may not be greater than 255 characters.',
            'tags.*.name.required' => 'Tag name is required.',
            'tags.*.name.max' => 'Tag name may not be greater than 255 characters.',
            'editSpecialties.required' => 'Please add at least one specialty and sub-specialty.',
            'editSpecialties.min' => 'Please add at least one specialty and sub-specialty.',
            'editSpecialties.*.specialty_name.required' => 'Specialty name is required.',
            'editSpecialties.*.specialty_name.max' => 'Specialty name may not be greater than 255 characters.',
            'editSpecialties.*.sub_specialty_name.required' => 'Sub-specialty name is required.',
            'editSpecialties.*.sub_specialty_name.max' => 'Sub-specialty name may not be greater than 255 characters.',
            'editTags.*.name.required' => 'Tag name is required.',
            'editTags.*.name.max' => 'Tag name may not be greater than 255 characters.',
        ];
    }
}
