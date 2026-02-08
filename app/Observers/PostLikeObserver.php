<?php

namespace App\Observers;

use App\Models\NotificationSetting;
use App\Models\PostLike;
use App\Models\UserNotification;

class PostLikeObserver
{
    /**
     * Handle the PostLike "created" event.
     */
    public function created(PostLike $postLike): void
    {
        $post = $postLike->post;
        $liker = $postLike->user;
        $postOwner = $post->user ?? null;

        // Notify post owner about like (if not liking own post)
        if ($postOwner && $postOwner->id !== $liker->id) {
            $settings = NotificationSetting::firstOrCreate(
                ['user_id' => $postOwner->id],
                ['follow' => true, 'like' => true, 'comment' => true, 'message' => true]
            );

            if ($settings->like) {
                // Check if notification already exists to avoid spam
                $existingNotification = UserNotification::where('user_id', $postOwner->id)
                    ->where('source_user_id', $liker->id)
                    ->where('type', 'post_like')
                    ->where('post_id', $post->id)
                    ->where('is_read', false)
                    ->exists();

                if (!$existingNotification) {
                    UserNotification::create([
                        'user_id' => $postOwner->id,
                        'source_user_id' => $liker->id,
                        'type' => 'post_like',
                        'post_id' => $post->id,
                        'message' => "{$liker->name} liked your post",
                        'is_read' => false,
                    ]);
                }
            }
        }

        // Event for Livewire components
        // Components can update like count and button state in real-time
    }

    /**
     * Handle the PostLike "updated" event.
     */
    public function updated(PostLike $postLike): void
    {
        //
    }

    /**
     * Handle the PostLike "deleted" event.
     */
    public function deleted(PostLike $postLike): void
    {
        $post = $postLike->post;

        // Event for Livewire components
        // Components can update like count when post is unliked
    }

    /**
     * Handle the PostLike "restored" event.
     */
    public function restored(PostLike $postLike): void
    {
        //
    }

    /**
     * Handle the PostLike "force deleted" event.
     */
    public function forceDeleted(PostLike $postLike): void
    {
        //
    }
}
