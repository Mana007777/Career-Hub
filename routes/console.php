<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule cleanup and maintenance tasks

// Weekly tasks
Schedule::command('cleanup:sessions --days=7')
    ->weekly()
    ->sundays()
    ->at('02:00')
    ->description('Clean up old session records (weekly)');

Schedule::command('refresh:job-recommendations --days=30')
    ->weekly()
    ->sundays()
    ->at('03:00')
    ->description('Refresh job recommendations (weekly)');

// Daily tasks
Schedule::command('send:job-alerts --days=1')
    ->daily()
    ->at('09:00')
    ->description('Send job alerts for new jobs matching user specialties (daily)');

Schedule::command('calculate:trending-tags --days=7')
    ->daily()
    ->at('10:00')
    ->description('Calculate trending tags based on recent usage (daily)');

// Weekly tasks
Schedule::command('update:user-reputations')
    ->weekly()
    ->sundays()
    ->at('04:00')
    ->description('Update user reputation scores based on activity (weekly)');

Schedule::command('update:user-badges')
    ->weekly()
    ->sundays()
    ->at('05:00')
    ->description('Award badges to users based on achievements (weekly)');

Schedule::command('generate:job-recommendations --limit=10')
    ->weekly()
    ->sundays()
    ->at('06:00')
    ->description('Generate job recommendations for users (weekly)');

// Monthly tasks (1st of each month)
Schedule::command('cleanup:search-history --days=30')
    ->monthly()
    ->at('01:00')
    ->description('Clean up old search history (monthly)');

Schedule::command('archive:notifications --days=30')
    ->monthly()
    ->at('01:30')
    ->description('Archive old read notifications (monthly)');

Schedule::command('refresh:tag-stats')
    ->monthly()
    ->at('02:00')
    ->description('Refresh tag usage statistics (monthly)');

Schedule::command('cleanup:chat-requests --days=30')
    ->monthly()
    ->at('02:30')
    ->description('Clean up old chat requests (monthly)');

Schedule::command('cleanup:admin-logs --days=90')
    ->monthly()
    ->at('03:00')
    ->description('Clean up old admin logs (monthly, keeps 90 days)');
