<?php

namespace App\Observers;

use App\Models\NotificationSetting;
use App\Models\Post;
use App\Models\UserNotification;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        // Notify followers about new post
        $author = $post->user;
        if ($author) {
            $followers = $author->followers()->get();
            
            foreach ($followers as $follower) {
                // Check if user wants to receive post notifications
                $settings = NotificationSetting::firstOrCreate(
                    ['user_id' => $follower->id],
                    ['follow' => true, 'like' => true, 'comment' => true, 'message' => true]
                );

                if ($settings->follow) {
                    UserNotification::create([
                        'user_id' => $follower->id,
                        'source_user_id' => $author->id,
                        'type' => 'new_post',
                        'post_id' => $post->id,
                        'message' => "{$author->name} posted: " . ($post->title ?: substr($post->content, 0, 50)),
                        'is_read' => false,
                    ]);
                }
            }

            // Event for Livewire components
            // Livewire components can listen to this using: $this->dispatch('post-created', ['post_id' => $post->id]);
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        // Event for Livewire components
        // Components can refresh when posts are updated
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        // Event for Livewire components
        // Components can remove deleted posts from UI
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
