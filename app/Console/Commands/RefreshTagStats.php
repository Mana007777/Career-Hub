<?php

namespace App\Console\Commands;

use App\Models\Tag;
use App\Models\TagStat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefreshTagStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:tag-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh tag usage statistics from post_tags table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Refreshing tag statistics...');

        $tags = Tag::all();
        $updated = 0;

        foreach ($tags as $tag) {
            $usageCount = DB::table('post_tags')
                ->where('tag_id', $tag->id)
                ->count();

            $lastUsedAt = DB::table('post_tags')
                ->where('tag_id', $tag->id)
                ->max('created_at');

            TagStat::updateOrCreate(
                ['tag_id' => $tag->id],
                [
                    'usage_count' => $usageCount,
                    'last_used_at' => $lastUsedAt ? now()->parse($lastUsedAt) : null,
                ]
            );

            $updated++;
        }

        $this->info("Refreshed statistics for {$updated} tags.");

        return Command::SUCCESS;
    }
}
