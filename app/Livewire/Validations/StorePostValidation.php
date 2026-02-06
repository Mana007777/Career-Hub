<?php

namespace App\Livewire\Validations;

class StorePostValidation
{
    public static function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'media' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,mp4,avi,mov', 'max:10240'],
            'jobType' => ['nullable', 'string', 'in:remote,full-time,part-time'],
            'specialties' => ['required', 'array', 'min:1'],
            'specialties.*.specialty_name' => ['required', 'string', 'max:255'],
            'specialties.*.sub_specialty_name' => ['required', 'string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*.name' => ['required', 'string', 'max:255'],
        ];
    }

    public static function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'content.required' => 'The content field is required.',
            'content.max' => 'The content may not be greater than 5000 characters.',
            'media.file' => 'The media must be a valid file.',
            'media.mimes' => 'The media must be a file of type: jpeg, jpg, png, gif, mp4, avi, mov.',
            'media.max' => 'The media may not be greater than 10MB.',
            'specialties.required' => 'Please add at least one specialty and sub-specialty.',
            'specialties.min' => 'Please add at least one specialty and sub-specialty.',
            'specialties.*.specialty_name.required' => 'Specialty name is required.',
            'specialties.*.specialty_name.max' => 'Specialty name may not be greater than 255 characters.',
            'specialties.*.sub_specialty_name.required' => 'Sub-specialty name is required.',
            'specialties.*.sub_specialty_name.max' => 'Sub-specialty name may not be greater than 255 characters.',
            'tags.*.name.required' => 'Tag name is required.',
            'tags.*.name.max' => 'Tag name may not be greater than 255 characters.',
        ];
    }
}
