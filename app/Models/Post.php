<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
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

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'post_specialties')
            ->withPivot('sub_specialty_id')
            ->withTimestamps();
    }

    public function subSpecialties()
    {
        return $this->belongsToMany(SubSpecialty::class, 'post_specialties', 'post_id', 'sub_specialty_id')
            ->withPivot('specialty_id')
            ->withTimestamps();
    }

    /**
     * Get the slug for the post.
     *
     * @return string
     */
    public function getSlugAttribute(): string
    {
        // Prefer title for slug; fall back to content
        $base = $this->title ?: $this->content;

        $snippet = substr(strip_tags($base), 0, 50);
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $snippet)));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // If slug is empty, use a default
        if (empty($slug)) {
            $slug = 'post';
        }
        
        return $slug . '-' . $this->id;
    }
}
