<?php

namespace App\Jobs;

use App\Models\UserNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendUserNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The notification payload.
     *
     * @var array<string, mixed>
     */
    protected array $data;

    /**
     * Create a new job instance.
     *
     * @param  array<string, mixed>  $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UserNotification::create([
            'user_id' => $this->data['user_id'],
            'source_user_id' => $this->data['source_user_id'] ?? $this->data['user_id'],
            'type' => $this->data['type'],
            'post_id' => $this->data['post_id'] ?? null,
            'message' => $this->data['message'],
            'is_read' => false,
        ]);
    }
}

