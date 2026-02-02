<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagStat extends Model
{
    protected $table = 'tag_stats';

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
