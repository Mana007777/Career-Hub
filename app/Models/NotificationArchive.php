<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationArchive extends Model
{
    protected $table = 'notification_archives';

    protected $fillable = [
        'notification_id',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'archived_at' => 'datetime',
        ];
    }

    public function notification()
    {
        return $this->belongsTo(UserNotification::class, 'notification_id');
    }
}
