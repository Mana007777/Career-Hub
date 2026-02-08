<?php

namespace App\Console\Commands;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateUserBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:user-badges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Award badges to users based on their achievements and activity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating user badges...');

        // Ensure badges exist
        $this->ensureBadgesExist();

        $users = User::all();
        $awarded = 0;

        foreach ($users as $user) {
            $badgesAwarded = 0;

            // First Post Badge
            if ($user->posts()->count() >= 1) {
                $badge = Badge::firstOrCreate(['name' => 'First Post'], ['icon' => '📝']);
                if (!$user->badges()->where('badges.id', $badge->id)->exists()) {
                    $user->badges()->attach($badge->id, ['earned_at' => now()]);
                    $badgesAwarded++;
                }
            }

            // Active Poster (10+ posts)
            if ($user->posts()->count() >= 10) {
                $badge = Badge::firstOrCreate(['name' => 'Active Poster'], ['icon' => '✍️']);
                if (!$user->badges()->where('badges.id', $badge->id)->exists()) {
                    $user->badges()->attach($badge->id, ['earned_at' => now()]);
                    $badgesAwarded++;
                }
            }

            // Popular (50+ followers)
            if ($user->followers()->count() >= 50) {
                $badge = Badge::firstOrCreate(['name' => 'Popular'], ['icon' => '⭐']);
                if (!$user->badges()->where('badges.id', $badge->id)->exists()) {
                    $user->badges()->attach($badge->id, ['earned_at' => now()]);
                    $badgesAwarded++;
                }
            }

            // Helper (50+ comments)
            if ($user->comments()->count() >= 50) {
                $badge = Badge::firstOrCreate(['name' => 'Helper'], ['icon' => '💬']);
                if (!$user->badges()->where('badges.id', $badge->id)->exists()) {
                    $user->badges()->attach($badge->id, ['earned_at' => now()]);
                    $badgesAwarded++;
                }
            }

            // Job Seeker (10+ applications)
            if ($user->jobApplications()->count() >= 10) {
                $badge = Badge::firstOrCreate(['name' => 'Job Seeker'], ['icon' => '💼']);
                if (!$user->badges()->where('badges.id', $badge->id)->exists()) {
                    $user->badges()->attach($badge->id, ['earned_at' => now()]);
                    $badgesAwarded++;
                }
            }

            if ($badgesAwarded > 0) {
                $awarded += $badgesAwarded;
            }
        }

        $this->info("Awarded {$awarded} new badges to users.");

        return Command::SUCCESS;
    }

    /**
     * Ensure default badges exist in the database.
     */
    private function ensureBadgesExist(): void
    {
        $defaultBadges = [
            ['name' => 'First Post', 'icon' => '📝'],
            ['name' => 'Active Poster', 'icon' => '✍️'],
            ['name' => 'Popular', 'icon' => '⭐'],
            ['name' => 'Helper', 'icon' => '💬'],
            ['name' => 'Job Seeker', 'icon' => '💼'],
        ];

        foreach ($defaultBadges as $badgeData) {
            Badge::firstOrCreate(
                ['name' => $badgeData['name']],
                ['icon' => $badgeData['icon']]
            );
        }
    }
}
