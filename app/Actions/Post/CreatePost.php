<?php

namespace App\Actions\Post;

use App\DTO\PostData;
use App\Jobs\SendUserNotification;
use App\Models\Post;
use App\Models\Specialty;
use App\Models\SubSpecialty;
use App\Models\Tag;
use App\Queries\PostQueries;
use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Use case: create a new post.
 */
class CreatePost
{
    /**
     * Create a new post.
     *
     * @param  PostData  $data
     * @return Post
     */
    public function create(PostData $data): Post
    {
        if (Auth::user()?->isSeeker()) {
            throw new AccessDeniedHttpException(__('Seeker accounts cannot create posts. You can repost company listings instead.'));
        }

        $mediaPath = null;
        if ($data->media) {
            $mediaPath = $this->storeMedia($data->media);
        }

        $userId = Auth::id();

        $post = Post::create([
            'user_id' => $userId,
            'title'   => $data->title,
            'content' => $data->content,
            'media'   => $mediaPath,
            'job_type' => $data->jobType,
        ]);

        
        if (!empty($data->specialties)) {
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

                
                if (!$post->specialties()->wherePivot('specialty_id', $specialty->id)
                    ->wherePivot('sub_specialty_id', $subSpecialty->id)->exists()) {
                    $post->specialties()->attach($specialty->id, [
                        'sub_specialty_id' => $subSpecialty->id,
                    ]);
                }
            }
        }

       
        if (!empty($data->tags)) {
            $tagIds = [];
            foreach ($data->tags as $tagData) {
                $tag = Tag::firstOrCreate(['name' => trim($tagData['name'])]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        $post->load(['specialties', 'subSpecialties', 'tags', 'user.followers']);

        
        $userRepository = app(UserRepository::class);
        $userRepository->clearUserCache($post->user);

        
        app(PostQueries::class)->clearAllPostCaches();

       
        $author = $post->user;
        if ($author && $author->followers && $author->followers->isNotEmpty()) {
            foreach ($author->followers as $follower) {
                SendUserNotification::dispatch([
                    'user_id' => $follower->id,
                    'source_user_id' => $author->id,
                    'type' => 'new_post_from_following',
                    'post_id' => $post->id,
                    'message' => "{$author->name} shared a new post.",
                ])->onConnection('sync');
            }
        }

        return $post;
    }

    /**
     * Store the media file and return the path.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string
     */
    protected function storeMedia($file): string
    {
        $this->assertSafePostMediaUpload($file);
        // shield:ignore: upload
        $path = $file->store('posts/media', 'public');
        return $path;
    }

    private function assertSafePostMediaUpload(mixed $file): void
    {
        if (! $file instanceof UploadedFile || ! $file->isValid()) {
            throw new \RuntimeException('Invalid post media upload.');
        }

        if ($file->getSize() > (8 * 1024 * 1024)) {
            throw new \RuntimeException('Post media exceeds 8MB limit.');
        }

        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/gif',
        ];

        if (! in_array((string) $file->getMimeType(), $allowedMimeTypes, true)) {
            throw new \RuntimeException('Unsupported post media type.');
        }
    }
}
