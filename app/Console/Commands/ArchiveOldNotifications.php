<?php

namespace App\Console\Commands;

use App\Models\NotificationArchive;
use App\Models\UserNotification;
use Illuminate\Console\Command;

class ArchiveOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:notifications {--days=30 : Number of days before archiving read notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive old read notifications (default: older than 30 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        // Get read notifications older than cutoff date that aren't already archived
        $notifications = UserNotification::where('is_read', true)
            ->where('created_at', '<', $cutoffDate)
            ->whereDoesntHave('archive')
            ->get();

        $archived = 0;

        foreach ($notifications as $notification) {
            NotificationArchive::create([
                'notification_id' => $notification->id,
                'archived_at' => now(),
            ]);
            $archived++;
        }

        $this->info("Archived {$archived} old notifications.");

        return Command::SUCCESS;
    }
}
