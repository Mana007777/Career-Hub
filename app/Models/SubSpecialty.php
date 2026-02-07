<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSpecialty extends Model
{
    use HasFactory;
    protected $fillable = [
        'specialty_id',
        'name',
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function jobs()
    {
        return $this->hasMany(CareerJob::class);
    }
}
