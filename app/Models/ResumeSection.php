<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeSection extends Model
{
    protected $fillable = [
        'resume_id',
        'type',
        'content',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
