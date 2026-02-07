<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;
    protected $table = 'certifications';

    protected $fillable = [
        'user_id',
        'name',
        'issuer',
        'issue_date',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expires_at' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
