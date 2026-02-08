<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'media',
        'job_type',
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

    public function stars()
    {
        return $this->hasMany(PostStar::class);
    }

    public function starredBy()
    {
        return $this->belongsToMany(User::class, 'post_stars');
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

    public function cvs()
    {
        return $this->hasMany(PostCv::class);
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

    public function suspension()
    {
        return $this->hasOne(PostSuspension::class);
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

    /**
     * Check if the post is currently suspended
     */
    public function isSuspended(): bool
    {
        if (!$this->suspension) {
            return false;
        }

        // Check if suspension has expired
        if ($this->suspension->expires_at && $this->suspension->expires_at->isPast()) {
            // Auto-delete expired suspension
            $this->suspension->delete();
            return false;
        }

        return true;
    }
}
