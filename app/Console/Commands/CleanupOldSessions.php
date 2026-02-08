<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupOldSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:sessions {--days=7 : Number of days to keep sessions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old session records (default: older than 7 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffTimestamp = now()->subDays($days)->timestamp;

        $deleted = DB::table('sessions')
            ->where('last_activity', '<', $cutoffTimestamp)
            ->delete();

        $this->info("Cleaned up {$deleted} session records older than {$days} days.");

        return Command::SUCCESS;
    }
}
