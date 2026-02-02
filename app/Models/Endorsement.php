<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endorsement extends Model
{
    protected $fillable = [
        'user_id',
        'endorsed_by',
        'skill',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function endorser()
    {
        return $this->belongsTo(User::class, 'endorsed_by');
    }
}
