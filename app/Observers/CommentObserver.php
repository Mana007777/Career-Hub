<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\NotificationSetting;
use App\Models\UserNotification;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        $post = $comment->post;
        $commentAuthor = $comment->user;
        $postOwner = $post->user ?? null;

        // Notify post owner about new comment (if not commenting on own post)
        if ($postOwner && $postOwner->id !== $commentAuthor->id) {
            $settings = NotificationSetting::firstOrCreate(
                ['user_id' => $postOwner->id],
                ['follow' => true, 'like' => true, 'comment' => true, 'message' => true]
            );

            if ($settings->comment) {
                UserNotification::create([
                    'user_id' => $postOwner->id,
                    'source_user_id' => $commentAuthor->id,
                    'type' => 'new_comment',
                    'post_id' => $post->id,
                    'message' => "{$commentAuthor->name} commented on your post: " . substr($comment->content, 0, 50),
                    'is_read' => false,
                ]);
            }
        }

        // Notify parent comment author if this is a reply
        if ($comment->parent_id && $comment->parent) {
            $parentAuthor = $comment->parent->user;
            if ($parentAuthor && $parentAuthor->id !== $commentAuthor->id) {
                $settings = NotificationSetting::firstOrCreate(
                    ['user_id' => $parentAuthor->id],
                    ['follow' => true, 'like' => true, 'comment' => true, 'message' => true]
                );

                if ($settings->comment) {
                    UserNotification::create([
                        'user_id' => $parentAuthor->id,
                        'source_user_id' => $commentAuthor->id,
                        'type' => 'comment_reply',
                        'post_id' => $post->id,
                        'message' => "{$commentAuthor->name} replied to your comment",
                        'is_read' => false,
                    ]);
                }
            }
        }

        // Event for Livewire components
        // Components can refresh comments list when new comment is added
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        // Event for Livewire components
        // Components can update comment in UI
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        // Event for Livewire components
        // Components can remove deleted comment from UI
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }
}
