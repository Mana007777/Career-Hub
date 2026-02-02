<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRecommendation extends Model
{
    protected $table = 'job_recommendations';

    protected $fillable = [
        'user_id',
        'job_id',
        'score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(CareerJob::class, 'job_id');
    }
}
