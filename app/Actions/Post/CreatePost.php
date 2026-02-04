<?php

namespace App\Actions\Post;

use App\Models\Post;
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

        return Post::create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'media' => $mediaPath,
        ]);
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
}
