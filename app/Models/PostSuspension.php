<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostSuspension extends Model
{
    use HasFactory;
    
    protected $table = 'post_suspensions';

    public $timestamps = false;

    protected $primaryKey = 'post_id';

    public $incrementing = false;

    protected $fillable = [
        'post_id',
        'reason',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
