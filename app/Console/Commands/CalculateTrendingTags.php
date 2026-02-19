<?php

namespace App\Console\Commands;

use App\Models\Tag;
use App\Models\TagStat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateTrendingTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:trending-tags {--days=7 : Number of days to consider for trending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate trending tags based on recent usage (default: last 7 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Calculating trending tags from the last {$days} days...");

        
        $trendingTags = DB::table('post_tags')
            ->join('posts', 'post_tags.post_id', '=', 'posts.id')
            ->join('tags', 'post_tags.tag_id', '=', 'tags.id')
            ->where('posts.created_at', '>=', $cutoffDate)
            ->select('tags.id', 'tags.name', DB::raw('COUNT(*) as recent_usage'))
            ->groupBy('tags.id', 'tags.name')
            ->orderByDesc('recent_usage')
            ->limit(20)
            ->get();

        $this->info("Found " . $trendingTags->count() . " trending tags:");
        
        foreach ($trendingTags as $index => $tag) {
            $this->line(($index + 1) . ". {$tag->name} - {$tag->recent_usage} uses");
        }

        
        foreach ($trendingTags as $tag) {
            $tagModel = Tag::find($tag->id);
            if ($tagModel) {
                TagStat::updateOrCreate(
                    ['tag_id' => $tag->id],
                    [
                        'usage_count' => $tag->recent_usage,
                        'last_used_at' => now(),
                    ]
                );
            }
        }

        $this->info("Updated trending tag statistics.");

        return Command::SUCCESS;
    }
}
