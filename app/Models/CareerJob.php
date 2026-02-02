<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerJob extends Model
{
    protected $table = 'career_jobs';

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'specialty_id',
        'sub_specialty_id',
        'location',
        'job_type',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function subSpecialty()
    {
        return $this->belongsTo(SubSpecialty::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'job_tags', 'job_id', 'tag_id');
    }

    public function views()
    {
        return $this->hasMany(JobView::class, 'job_id');
    }

    public function recommendations()
    {
        return $this->hasMany(JobRecommendation::class, 'job_id');
    }
}
