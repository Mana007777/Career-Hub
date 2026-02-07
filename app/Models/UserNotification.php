<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;
    protected $table = 'user_notifications';

    protected $fillable = [
        'user_id',
        'source_user_id',
        'type',
        'post_id',
        'message',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'source_user_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function archive()
    {
        return $this->hasOne(NotificationArchive::class, 'notification_id');
    }
}
