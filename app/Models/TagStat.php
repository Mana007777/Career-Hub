<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagStat extends Model
{
    use HasFactory;
    protected $table = 'tag_stats';
    
    public $timestamps = false;

    protected $fillable = [
        'tag_id',
        'usage_count',
        'last_used_at',
    ];

    protected function casts(): array
    {
        return [
            'last_used_at' => 'datetime',
        ];
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
