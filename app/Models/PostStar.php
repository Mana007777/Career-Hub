<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostStar extends Model
{
    protected $table = 'post_stars';

    protected $fillable = [
        'post_id',
        'user_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
