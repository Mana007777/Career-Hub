<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCv extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_id',
        'user_id',
        'cv_file',
        'original_filename',
        'message',
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
