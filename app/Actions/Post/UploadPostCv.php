<?php

namespace App\Actions\Post;

use App\Jobs\SendUserNotification;
use App\Models\Post;
use App\Models\PostCv;
use Illuminate\Http\UploadedFile;
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
        $user = Auth::user();

        if (!$user) {
            throw new \RuntimeException('Authentication required to upload CV.');
        }

        if (($user->role ?? null) === 'company') {
            throw new \RuntimeException('Company accounts cannot upload CVs.');
        }

        
        $cvPath = $this->storeCv($cvFile);
        $originalFilename = $cvFile->getClientOriginalName();

        
        $postCv = PostCv::create([
            'post_id' => $post->id,
            'user_id' => $userId,
            'cv_file' => $cvPath,
            'original_filename' => $originalFilename,
            'message' => $message,
        ]);

            
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
        $this->assertSafeCvUpload($file);
        // shield:ignore: upload
        $path = $file->store('posts/cvs', 'public');
        return $path;
    }

    private function assertSafeCvUpload(mixed $file): void
    {
        if (! $file instanceof UploadedFile || ! $file->isValid()) {
            throw new \RuntimeException('Invalid CV upload.');
        }

        if ($file->getSize() > (10 * 1024 * 1024)) {
            throw new \RuntimeException('CV exceeds 10MB limit.');
        }

        $allowedMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        if (! in_array((string) $file->getMimeType(), $allowedMimeTypes, true)) {
            throw new \RuntimeException('Unsupported CV file type.');
        }
    }
}
