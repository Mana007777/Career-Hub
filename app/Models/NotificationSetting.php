<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $table = 'notification_settings';

    /**
     * The model does not have created_at/updated_at columns.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'follow',
        'like',
        'comment',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'follow' => 'boolean',
            'like' => 'boolean',
            'comment' => 'boolean',
            'message' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
