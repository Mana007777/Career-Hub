<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;
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
