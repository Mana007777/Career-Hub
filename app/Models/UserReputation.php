<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReputation extends Model
{
    protected $table = 'user_reputations';

    protected $fillable = [
        'user_id',
        'score',
        'level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
