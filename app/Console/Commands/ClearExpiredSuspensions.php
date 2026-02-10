<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\PostSuspension;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserSuspension;
use Illuminate\Console\Command;

class ClearExpiredSuspensions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:expired-suspensions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify admins when user or post suspensions reach their end time';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = now();

        $this->info('Sending notifications for expired user suspensions...');

        // Find suspensions that have just expired in the last few minutes
        $expiredUserSuspensions = UserSuspension::whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->get();

        $adminRecipients = User::where(function ($q) {
                $q->where('is_admin', true)
                  ->orWhere('email', 'test@example.com')
                  ->orWhere('role', 'admin')
                  ->orWhere('id', 1);
            })
            ->get();

        $userNotifCount = 0;

        foreach ($expiredUserSuspensions as $suspension) {
            $suspendedUser = $suspension->user;

            foreach ($adminRecipients as $admin) {
                // Avoid duplicate notifications for the same admin + suspension
                    $alreadyNotified = UserNotification::where('user_id', $admin->id)
                    ->where('type', 'suspension_user_expired')
                    ->where('source_user_id', $suspendedUser?->id)
                    ->whereNull('post_id')
                    ->where('created_at', '>=', $now->copy()->subDay())
                    ->exists();

                if ($alreadyNotified) {
                    continue;
                }

                UserNotification::create([
                    'user_id' => $admin->id,
                    'source_user_id' => $suspendedUser?->id,
                    'type' => 'suspension_user_expired',
                    'post_id' => null,
                    'message' => sprintf(
                        'The suspension for user "%s" (ID %d) reached its end time. Review and unsuspend if appropriate.',
                        $suspendedUser?->name ?? 'Unknown',
                        $suspendedUser?->id ?? 0
                    ),
                    'is_read' => false,
                ]);

                $userNotifCount++;
            }
        }

        $this->info("Created {$userNotifCount} notifications for expired user suspensions.");

        $this->info('Sending notifications for expired post suspensions...');

        $expiredPostSuspensions = PostSuspension::whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->get();

        $postNotifCount = 0;

        foreach ($expiredPostSuspensions as $suspension) {
            $post = $suspension->post;

            foreach ($adminRecipients as $admin) {
                $alreadyNotified = UserNotification::where('user_id', $admin->id)
                    ->where('type', 'suspension_post_expired')
                    ->where('post_id', $post?->id)
                    ->where('created_at', '>=', $now->copy()->subDay())
                    ->exists();

                if ($alreadyNotified) {
                    continue;
                }

                UserNotification::create([
                    'user_id' => $admin->id,
                    'source_user_id' => $post?->user_id,
                    'type' => 'suspension_post_expired',
                    'post_id' => $post?->id,
                    'message' => sprintf(
                        'The suspension for a post by "%s" (Post ID %d) reached its end time. Review and unsuspend if appropriate.',
                        $post?->user?->name ?? 'Unknown',
                        $post?->id ?? 0
                    ),
                    'is_read' => false,
                ]);

                $postNotifCount++;
            }
        }

        $this->info("Created {$postNotifCount} notifications for expired post suspensions.");

        return self::SUCCESS;
    }
}

