<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'is_group',
    ];

    protected function casts(): array
    {
        return [
            'is_group' => 'boolean',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_participants');
    }


    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the other user in a one-on-one chat
     */
    public function getOtherUser(?int $currentUserId = null): ?User
    {
        if ($this->is_group) {
            return null;
        }

        $currentUserId = $currentUserId ?? auth()->id();
        
        return $this->users()->where('users.id', '!=', $currentUserId)->first();
    }
}
