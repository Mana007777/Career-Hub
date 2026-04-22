<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostStar;
use App\Models\SavedItem;
use App\Models\Share;
use App\Services\AiRecommendationService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class BackfillAiInteractions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:backfill-interactions
        {--chunk=200 : Number of interactions per API request}
        {--dry-run : Build dataset without sending to AI service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill historical likes/comments/reposts/saves into the AI recommendation service';

    /**
     * Execute the console command.
     */
    public function handle(AiRecommendationService $aiRecommendationService): int
    {
        $chunkSize = max((int) $this->option('chunk'), 50);
        $dryRun = (bool) $this->option('dry-run');

        $interactions = collect()
            ->merge($this->buildLikes())
            ->merge($this->buildComments())
            ->merge($this->buildReposts())
            ->merge($this->buildSaves())
            ->unique(fn (array $row) => implode(':', [$row['laravel_user_id'], $row['laravel_post_id'], $row['action']]))
            ->values();

        if ($interactions->isEmpty()) {
            $this->warn('No interactions found to backfill.');

            return self::SUCCESS;
        }

        $this->info("Prepared {$interactions->count()} interactions for backfill.");
        if ($dryRun) {
            $this->line('Dry run enabled. No API calls were sent.');

            return self::SUCCESS;
        }

        $failedBatches = 0;
        $sent = 0;
        foreach ($interactions->chunk($chunkSize) as $chunk) {
            $ok = $aiRecommendationService->trackInteractionsBulk($chunk->all());
            if (! $ok) {
                $failedBatches++;
                continue;
            }
            $sent += $chunk->count();
        }

        $this->info("Backfill complete. Sent {$sent} interactions.");
        if ($failedBatches > 0) {
            $this->warn("{$failedBatches} batch(es) failed. Check Laravel logs for AI request failures.");
        }

        return $failedBatches > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function buildLikes(): Collection
    {
        return PostStar::query()
            ->select(['user_id', 'post_id'])
            ->get()
            ->map(fn (PostStar $row) => $this->interactionRow((int) $row->user_id, (int) $row->post_id, 'like'));
    }

    private function buildComments(): Collection
    {
        return Comment::query()
            ->select(['user_id', 'post_id'])
            ->whereNotNull('user_id')
            ->whereNotNull('post_id')
            ->get()
            ->map(fn (Comment $row) => $this->interactionRow((int) $row->user_id, (int) $row->post_id, 'comment'));
    }

    private function buildReposts(): Collection
    {
        return Share::query()
            ->select(['user_id', 'post_id'])
            ->get()
            ->map(fn (Share $row) => $this->interactionRow((int) $row->user_id, (int) $row->post_id, 'repost'));
    }

    private function buildSaves(): Collection
    {
        return SavedItem::query()
            ->select(['user_id', 'item_id', 'item_type'])
            ->where('item_type', Post::class)
            ->get()
            ->map(fn (SavedItem $row) => $this->interactionRow((int) $row->user_id, (int) $row->item_id, 'save'));
    }

    private function interactionRow(int $userId, int $postId, string $action): array
    {
        return [
            'laravel_user_id' => $userId,
            'laravel_post_id' => $postId,
            'action' => $action,
        ];
    }
}
