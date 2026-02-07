<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }

    public function jobs()
    {
        return $this->belongsToMany(CareerJob::class, 'job_tags', 'job_id', 'tag_id');
    }

    public function stats()
    {
        return $this->hasOne(TagStat::class);
    }
}

