<?php

namespace App\Console\Commands;

use App\Models\ChatRequest;
use Illuminate\Console\Command;

class CleanupChatRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:chat-requests {--days=30 : Number of days to keep chat requests}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old or rejected chat requests (default: older than 30 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        // Delete old rejected requests
        $rejected = ChatRequest::where('status', 'rejected')
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        // Delete very old pending requests (older than 60 days)
        $oldPending = ChatRequest::where('status', 'pending')
            ->where('created_at', '<', now()->subDays(60))
            ->delete();

        $total = $rejected + $oldPending;

        $this->info("Cleaned up {$total} chat requests ({$rejected} rejected, {$oldPending} old pending).");

        return Command::SUCCESS;
    }
}
