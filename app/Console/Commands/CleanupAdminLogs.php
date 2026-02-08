<?php

namespace App\Console\Commands;

use App\Models\AdminLog;
use Illuminate\Console\Command;

class CleanupAdminLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:admin-logs {--days=90 : Number of days to keep admin logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old admin logs (default: older than 90 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $deleted = AdminLog::where('created_at', '<', $cutoffDate)->delete();

        $this->info("Cleaned up {$deleted} admin log records older than {$days} days.");

        return Command::SUCCESS;
    }
}
