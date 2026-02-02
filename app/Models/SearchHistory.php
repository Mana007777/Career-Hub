<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    protected $table = 'search_history';

    protected $fillable = [
        'user_id',
        'keyword',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
