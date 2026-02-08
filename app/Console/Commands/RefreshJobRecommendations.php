<?php

namespace App\Console\Commands;

use App\Models\JobRecommendation;
use Illuminate\Console\Command;

class RefreshJobRecommendations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:job-recommendations {--days=30 : Remove recommendations older than X days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old job recommendations and refresh them (default: older than 30 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        // Delete old recommendations
        $deleted = JobRecommendation::where('created_at', '<', $cutoffDate)->delete();

        $this->info("Cleaned up {$deleted} old job recommendations (older than {$days} days).");
        $this->info("Note: New recommendations will be generated when users interact with the system.");

        return Command::SUCCESS;
    }
}
