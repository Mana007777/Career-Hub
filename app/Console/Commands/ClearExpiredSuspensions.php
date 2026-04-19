<?php

namespace App\Console\Commands;

use App\Models\PostSuspension;
use App\Models\UserSuspension;
use App\Queries\PostQueries;
use Illuminate\Console\Command;

class ClearExpiredSuspensions extends Command
{
    protected $signature = 'notify:expired-suspensions';

    protected $description = 'Remove post and user suspensions whose optional end time has passed';

    public function handle(PostQueries $postQueries): int
    {
        $now = now();

        $expiredPostIds = PostSuspension::query()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->pluck('post_id');

        $deletedPosts = PostSuspension::query()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->delete();

        foreach ($expiredPostIds as $postId) {
            $postQueries->clearPostCache((int) $postId);
        }

        $deletedUsers = UserSuspension::query()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->delete();

        $this->info("Removed {$deletedPosts} expired post suspension(s) and {$deletedUsers} expired user suspension(s).");

        return self::SUCCESS;
    }
}
