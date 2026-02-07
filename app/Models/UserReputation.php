<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReputation extends Model
{
    use HasFactory;
    protected $table = 'user_reputations';
    
    public $timestamps = false;

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
