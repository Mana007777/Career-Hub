<?php

namespace App\Actions\Post;

use App\Models\Post;
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

        return $post->fresh();
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
