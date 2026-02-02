<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'document_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
