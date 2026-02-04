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

class CreatePost
{
    /**
     * Create a new post.
     *
     * @param  array<string, mixed>  $input
     * @return Post
     * @throws ValidationException
     */
    public function create(array $input): Post
    {
        $validated = $this->validate($input);

        $mediaPath = null;
        if (isset($validated['media']) && $validated['media']) {
            $mediaPath = $this->storeMedia($validated['media']);
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'media' => $mediaPath,
        ]);

        // Attach specialties to the post (find or create)
        if (isset($validated['specialties']) && is_array($validated['specialties'])) {
            foreach ($validated['specialties'] as $specialtyData) {
                // Find or create specialty
                $specialty = Specialty::firstOrCreate(
                    ['name' => trim($specialtyData['specialty_name'])]
                );

                // Find or create sub-specialty
                $subSpecialty = SubSpecialty::firstOrCreate(
                    [
                        'specialty_id' => $specialty->id,
                        'name' => trim($specialtyData['sub_specialty_name'])
                    ]
                );

                // Attach to post (avoid duplicates)
                if (!$post->specialties()->wherePivot('specialty_id', $specialty->id)
                    ->wherePivot('sub_specialty_id', $subSpecialty->id)->exists()) {
                    $post->specialties()->attach($specialty->id, [
                        'sub_specialty_id' => $subSpecialty->id,
                    ]);
                }
            }
        }

        // Attach tags to the post (find or create)
        if (isset($validated['tags']) && is_array($validated['tags'])) {
            $tagIds = [];
            foreach ($validated['tags'] as $tagData) {
                $tag = Tag::firstOrCreate(['name' => trim($tagData['name'])]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        return $post->load(['specialties', 'subSpecialties', 'tags']);
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
}
