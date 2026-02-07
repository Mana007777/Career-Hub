<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function subSpecialties()
    {
        return $this->hasMany(SubSpecialty::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_specialties')
            ->withPivot('sub_specialty_id');
    }

    public function jobs()
    {
        return $this->hasMany(CareerJob::class);
    }
}
