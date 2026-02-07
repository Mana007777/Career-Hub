<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'reporter_id',
        'target_type',
        'target_id',
        'reason',
        'status',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Get the target model instance.
     * Note: We don't use morphTo because target_type stores lowercase strings,
     * not fully qualified class names. This accessor will only be used if the
     * relation hasn't been manually set via setRelation().
     */
    public function getTargetAttribute()
    {
        // If the relation is already loaded, return it
        if ($this->relationLoaded('target')) {
            return $this->getRelation('target');
        }
        
        // Otherwise, load it dynamically
        $modelClass = $this->getTargetModelClass();
        if (!$modelClass) {
            return null;
        }
        
        return $modelClass::find($this->target_id);
    }
    
    /**
     * Get the model class name for the target type.
     */
    public function getTargetModelClass(): ?string
    {
        return match($this->target_type) {
            'post' => Post::class,
            'user' => User::class,
            'comment' => Comment::class,
            default => null,
        };
    }
}
