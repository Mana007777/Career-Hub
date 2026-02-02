<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_id',
        'user_id',
        'status',
    ];

    public function job()
    {
        return $this->belongsTo(CareerJob::class, 'job_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

