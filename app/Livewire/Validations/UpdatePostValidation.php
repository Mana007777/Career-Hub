<?php

namespace App\Livewire\Validations;

class UpdatePostValidation
{
    public static function rules(): array
    {
        return [
            'editTitle' => ['required', 'string', 'max:255'],
            'editContent' => ['required', 'string', 'max:5000'],
            'editMedia' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,mp4,avi,mov', 'max:10240'],
            'editJobType' => ['nullable', 'string', 'in:remote,full-time,part-time'],
            'editSpecialties' => ['required', 'array', 'min:1'],
            'editSpecialties.*.specialty_name' => ['required', 'string', 'max:255'],
            'editSpecialties.*.sub_specialty_name' => ['required', 'string', 'max:255'],
            'editTags' => ['nullable', 'array'],
            'editTags.*.name' => ['required', 'string', 'max:255'],
        ];
    }

    public static function messages(): array
    {
        return [
            'editTitle.required' => 'The title field is required.',
            'editTitle.max' => 'The title may not be greater than 255 characters.',
            'editContent.required' => 'The content field is required.',
            'editContent.max' => 'The content may not be greater than 5000 characters.',
            'editMedia.file' => 'The media must be a valid file.',
            'editMedia.mimes' => 'The media must be a file of type: jpeg, jpg, png, gif, mp4, avi, mov.',
            'editMedia.max' => 'The media may not be greater than 10MB.',
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
