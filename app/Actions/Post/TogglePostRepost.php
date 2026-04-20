<?php

namespace App\Actions\Post;

use App\Jobs\SendUserNotification;
use App\Models\Post;
use App\Models\Share;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Access\AuthorizationException;

class TogglePostRepost
{
    public function __construct(private UserRepository $userRepository) {}

    /**
     * @return bool True if repost was added, false if removed
     */
    public function toggle(User $user, Post $post): bool
    {
        if (!$user->isSeeker()) {
            throw new AuthorizationException(__('Only job seekers can repost listings.'));
        }

        $post->loadMissing('user');

        if (!$post->user || !$post->user->isCompany()) {
            throw new AuthorizationException(__('You can only repost posts published by companies.'));
        }

        $existing = Share::query()
            ->where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->userRepository->clearUserCache($user);

            return false;
        }

        Share::query()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
        $this->userRepository->clearUserCache($user);

        if ((int) $post->user_id !== (int) $user->id) {
            SendUserNotification::dispatchSync([
                'user_id' => $post->user_id,
                'source_user_id' => $user->id,
                'type' => 'post_reposted',
                'post_id' => $post->id,
                'message' => sprintf('%s reposted your post.', $user->name),
            ]);
        }

        return true;
    }
}
