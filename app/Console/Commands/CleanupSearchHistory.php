<?php

namespace App\Console\Commands;

use App\Models\SearchHistory;
use Illuminate\Console\Command;

class CleanupSearchHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:search-history {--days=30 : Number of days to keep search history}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old search history records (default: older than 30 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $deleted = SearchHistory::where('created_at', '<', $cutoffDate)->delete();

        $this->info("Cleaned up {$deleted} search history records older than {$days} days.");

        return Command::SUCCESS;
    }
}
