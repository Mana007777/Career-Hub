<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserReputation;
use Illuminate\Console\Command;

class UpdateUserReputations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:user-reputations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and update user reputation scores based on their activity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating user reputations...');

        $users = User::all();
        $updated = 0;

        foreach ($users as $user) {
            $score = 0;

            // Posts get points
            $score += $user->posts()->count() * 5;

            // Post likes received
            $postLikes = $user->posts()->withCount('likes')->get()->sum('likes_count');
            $score += $postLikes * 2;

            // Comments get points
            $score += $user->comments()->count() * 3;

            // Followers
            $score += $user->followers()->count() * 10;

            // Endorsements received
            $score += $user->endorsedBy()->count() * 15;

            // Job applications (shows activity)
            $score += $user->jobApplications()->count() * 2;

            // Calculate level based on score
            $level = match (true) {
                $score >= 1000 => 'Expert',
                $score >= 500 => 'Advanced',
                $score >= 200 => 'Intermediate',
                $score >= 50 => 'Beginner',
                default => 'New',
            };

            UserReputation::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'score' => $score,
                    'level' => $level,
                ]
            );

            $updated++;
        }

        $this->info("Updated reputation scores for {$updated} users.");

        return Command::SUCCESS;
    }
}
