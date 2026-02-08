<?php

namespace App\Observers;

use App\Models\JobApplication;
use App\Models\UserNotification;

class JobApplicationObserver
{
    /**
     * Handle the JobApplication "created" event.
     */
    public function created(JobApplication $jobApplication): void
    {
        $job = $jobApplication->job;
        $applicant = $jobApplication->user;
        $company = $job->company ?? null;
        $companyOwner = $company->user ?? null;

        // Notify company owner about new application
        if ($companyOwner) {
            UserNotification::create([
                'user_id' => $companyOwner->id,
                'source_user_id' => $applicant->id,
                'type' => 'job_application',
                'post_id' => null,
                'message' => "{$applicant->name} applied for your job: {$job->title}",
                'is_read' => false,
            ]);
        }

        // Event for Livewire components
        // Components can refresh application lists
    }

    /**
     * Handle the JobApplication "updated" event.
     */
    public function updated(JobApplication $jobApplication): void
    {
        // Check if status changed
        if ($jobApplication->wasChanged('status')) {
            $job = $jobApplication->job;
            $applicant = $jobApplication->user;
            $oldStatus = $jobApplication->getOriginal('status');
            $newStatus = $jobApplication->status;

            // Notify applicant about status change
            $statusMessages = [
                'accepted' => "Congratulations! Your application for '{$job->title}' has been accepted!",
                'rejected' => "Your application for '{$job->title}' was not selected at this time.",
                'reviewing' => "Your application for '{$job->title}' is now under review.",
            ];

            if (isset($statusMessages[$newStatus])) {
                UserNotification::create([
                    'user_id' => $applicant->id,
                    'source_user_id' => $job->company->user_id ?? null,
                    'type' => 'application_status',
                    'post_id' => null,
                    'message' => $statusMessages[$newStatus],
                    'is_read' => false,
                ]);
            }

            // Event for Livewire components
            // Components can update application status in UI
        }
    }

    /**
     * Handle the JobApplication "deleted" event.
     */
    public function deleted(JobApplication $jobApplication): void
    {
        // Event for Livewire components
        // Components can remove deleted application from UI
    }

    /**
     * Handle the JobApplication "restored" event.
     */
    public function restored(JobApplication $jobApplication): void
    {
        //
    }

    /**
     * Handle the JobApplication "force deleted" event.
     */
    public function forceDeleted(JobApplication $jobApplication): void
    {
        //
    }
}
