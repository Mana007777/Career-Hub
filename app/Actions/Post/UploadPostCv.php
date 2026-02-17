<?php

namespace App\Actions\Post;

use App\Jobs\SendUserNotification;
use App\Models\Post;
use App\Models\PostCv;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Use case: upload a CV for a post.
 */
class UploadPostCv
{
    /**
     * Upload a CV for a post.
     *
     * @param  Post  $post
     * @param  mixed  $cvFile
     * @param  string|null  $message
     * @return PostCv
     */
    public function upload(Post $post, $cvFile, ?string $message = null): PostCv
    {
        $userId = Auth::id();

        // Store the CV file
        $cvPath = $this->storeCv($cvFile);
        $originalFilename = $cvFile->getClientOriginalName();

        // Create the PostCv record
        $postCv = PostCv::create([
            'post_id' => $post->id,
            'user_id' => $userId,
            'cv_file' => $cvPath,
            'original_filename' => $originalFilename,
            'message' => $message,
        ]);

        // Send notification to post owner (queued on default queue, e.g. Redis)
        $postOwner = $post->user;
        if ($postOwner && $postOwner->id !== $userId) {
            $applicant = Auth::user();
            SendUserNotification::dispatchSync([
                'user_id' => $postOwner->id,
                'source_user_id' => $userId,
                'type' => 'cv_uploaded',
                'post_id' => $post->id,
                'message' => "{$applicant->name} uploaded a CV for your post: {$post->title}",
            ]);
        }

        return $postCv;
    }

    /**
     * Store the CV file and return the path.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string
     */
    protected function storeCv($file): string
    {
        $path = $file->store('posts/cvs', 'public');
        return $path;
    }
}
