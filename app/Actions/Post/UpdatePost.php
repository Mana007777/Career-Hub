<?php

namespace App\Actions\Post;

use App\Models\Post;
use App\Models\Specialty;
use App\Models\SubSpecialty;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UpdatePost
{
    /**
     * Update an existing post.
     *
     * @param  Post  $post
     * @param  array<string, mixed>  $input
     * @return Post
     * @throws ValidationException
     */
    public function update(Post $post, array $input): Post
    {
        $this->authorize($post);

        $validated = $this->validate($input);

        // Handle media update
        if (isset($validated['media']) && $validated['media']) {
            // Delete old media if exists
            if ($post->media) {
                Storage::disk('public')->delete($post->media);
            }
            $validated['media'] = $this->storeMedia($validated['media']);
        } else {
            // If media is not being updated, keep the existing one
            unset($validated['media']);
        }

        $post->update($validated);

        // Update specialties
        if (isset($validated['specialties']) && is_array($validated['specialties'])) {
            // Detach all existing specialties
            $post->specialties()->detach();
            
            // Attach new specialties
            foreach ($validated['specialties'] as $specialtyData) {
                $specialty = Specialty::firstOrCreate(
                    ['name' => trim($specialtyData['specialty_name'])]
                );

                $subSpecialty = SubSpecialty::firstOrCreate(
                    [
                        'specialty_id' => $specialty->id,
                        'name' => trim($specialtyData['sub_specialty_name'])
                    ]
                );

                $post->specialties()->attach($specialty->id, [
                    'sub_specialty_id' => $subSpecialty->id,
                ]);
            }
        }

        // Update tags
        if (isset($validated['tags']) && is_array($validated['tags'])) {
            $tagIds = [];
            foreach ($validated['tags'] as $tagData) {
                $tag = Tag::firstOrCreate(['name' => trim($tagData['name'])]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        return $post->fresh()->load(['specialties', 'subSpecialties', 'tags']);
    }

    /**
     * Validate the input data.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     * @throws ValidationException
     */
    protected function validate(array $input): array
    {
        return Validator::make($input, [
            'content' => ['required', 'string', 'max:5000'],
            'media' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,mp4,avi,mov', 'max:10240'], // 10MB max
            'specialties' => ['required', 'array', 'min:1'],
            'specialties.*.specialty_name' => ['required', 'string', 'max:255'],
            'specialties.*.sub_specialty_name' => ['required', 'string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*.name' => ['required', 'string', 'max:255'],
        ], [
            'specialties.required' => 'Please add at least one specialty and sub-specialty.',
            'specialties.min' => 'Please add at least one specialty and sub-specialty.',
            'specialties.*.specialty_name.required' => 'Specialty name is required.',
            'specialties.*.specialty_name.max' => 'Specialty name may not be greater than 255 characters.',
            'specialties.*.sub_specialty_name.required' => 'Sub-specialty name is required.',
            'specialties.*.sub_specialty_name.max' => 'Sub-specialty name may not be greater than 255 characters.',
            'tags.*.name.required' => 'Tag name is required.',
            'tags.*.name.max' => 'Tag name may not be greater than 255 characters.',
        ])->validate();
    }

    /**
     * Store the media file and return the path.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string
     */
    protected function storeMedia($file): string
    {
        $path = $file->store('posts/media', 'public');
        return $path;
    }

    /**
     * Authorize that the user can update this post.
     *
     * @param  Post  $post
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function authorize(Post $post): void
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
