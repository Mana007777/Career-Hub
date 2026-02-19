<?php

namespace App\Console\Commands;

use App\Models\CareerJob;
use App\Models\JobRecommendation;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateJobRecommendations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:job-recommendations {--limit=10 : Number of recommendations per user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate job recommendations for users based on their specialties and preferences';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = (int) $this->option('limit');
        $this->info('Generating job recommendations...');

        $users = User::where('role', 'seeker')->get();
        $totalGenerated = 0;

        foreach ($users as $user) {
            
            $userSpecialtyIds = $user->specialties()->pluck('specialties.id')->toArray();
            
            if (empty($userSpecialtyIds)) {
                continue;
            }

            
            $recommendedJobs = CareerJob::whereIn('specialty_id', $userSpecialtyIds)
                ->whereDoesntHave('recommendations', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereDoesntHave('applications', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            foreach ($recommendedJobs as $job) {
                
                $score = 100;
                if ($user->specialties()->where('specialties.id', $job->specialty_id)->exists()) {
                    $score += 50; 
                }

                JobRecommendation::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'job_id' => $job->id,
                    ],
                    ['score' => $score]
                );
                $totalGenerated++;
            }
        }

        $this->info("Generated {$totalGenerated} job recommendations for " . $users->count() . " users.");

        return Command::SUCCESS;
    }
}
