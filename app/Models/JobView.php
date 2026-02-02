<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobView extends Model
{
    protected $table = 'job_views';

    protected $fillable = [
        'job_id',
        'user_id',
        'viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
        ];
    }

    public function job()
    {
        return $this->belongsTo(CareerJob::class, 'job_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
