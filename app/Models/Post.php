<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'media',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'post_likes');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class);
    }
}
