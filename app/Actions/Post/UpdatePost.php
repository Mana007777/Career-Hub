<?php

namespace App\Actions\Post;

use App\DTO\PostData;
use App\Models\Post;
use App\Models\Specialty;
use App\Models\SubSpecialty;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Use case: update an existing post.
 */
class UpdatePost
{
    /**
     * Update an existing post.
     *
     * @param  Post  $post
     * @param  PostData  $data
     * @return Post
     */
    public function update(Post $post, PostData $data): Post
    {
        $this->authorize($post);

        // Handle media update
        if ($data->media) {
            // Delete old media if exists
            if ($post->media) {
                Storage::disk('public')->delete($post->media);
            }
            $mediaPath = $this->storeMedia($data->media);
        } else {
            // If media is not being updated, keep the existing one
            $mediaPath = $post->media;
        }

        $post->update([
            'title'   => $data->title,
            'content' => $data->content,
            'media'   => $mediaPath,
            'job_type' => $data->jobType,
        ]);

        // Update specialties
        if (!empty($data->specialties)) {
            // Detach all existing specialties
            $post->specialties()->detach();
            
            // Attach new specialties
            foreach ($data->specialties as $specialtyData) {
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
        if (!empty($data->tags)) {
            $tagIds = [];
            foreach ($data->tags as $tagData) {
                $tag = Tag::firstOrCreate(['name' => trim($tagData['name'])]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        return $post->fresh()->load(['specialties', 'subSpecialties', 'tags']);
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
