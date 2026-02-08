<?php

namespace App\Console\Commands;

use App\Models\CareerJob;
use App\Models\JobRecommendation;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Console\Command;

class SendJobAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:job-alerts {--days=1 : Number of days to look back for new jobs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send job alerts to users for new jobs matching their specialties (default: jobs from last 1 day)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Sending job alerts for jobs posted in the last {$days} day(s)...");

        // Get new jobs
        $newJobs = CareerJob::where('created_at', '>=', $cutoffDate)->get();

        if ($newJobs->isEmpty()) {
            $this->info('No new jobs found.');
            return Command::SUCCESS;
        }

        $notificationsSent = 0;
        $users = User::where('role', 'seeker')->get();

        foreach ($users as $user) {
            $userSpecialtyIds = $user->specialties()->pluck('specialties.id')->toArray();

            if (empty($userSpecialtyIds)) {
                continue;
            }

            // Find matching jobs
            $matchingJobs = $newJobs->filter(function ($job) use ($userSpecialtyIds) {
                return in_array($job->specialty_id, $userSpecialtyIds);
            });

            if ($matchingJobs->isNotEmpty()) {
                foreach ($matchingJobs as $job) {
                    // Check if notification already exists
                    $exists = UserNotification::where('user_id', $user->id)
                        ->where('post_id', null)
                        ->where('type', 'job_alert')
                        ->where('message', 'like', "%{$job->title}%")
                        ->exists();

                    if (!$exists) {
                        $companyName = $job->company ? $job->company->name : 'Unknown Company';
                        UserNotification::create([
                            'user_id' => $user->id,
                            'type' => 'job_alert',
                            'message' => "New job matching your specialty: {$job->title} at {$companyName}",
                            'is_read' => false,
                        ]);
                        $notificationsSent++;
                    }
                }
            }
        }

        $this->info("Sent {$notificationsSent} job alert notifications to users.");

        return Command::SUCCESS;
    }
}
