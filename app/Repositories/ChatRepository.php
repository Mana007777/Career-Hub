<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ChatRepository
{
    /**
     * Create a new chat.
     * Simple operation - kept in repository.
     *
     * @param  array<string, mixed>  $data
     * @return Chat
     */
    public function create(array $data): Chat
    {
        return Chat::create($data);
    }

    /**
     * Attach users to a chat.
     * Simple operation - kept in repository.
     *
     * @param  Chat  $chat
     * @param  array<int>  $userIds
     * @return void
     */
    public function attachUsers(Chat $chat, array $userIds): void
    {
        $chat->users()->attach($userIds);
    }

    /**
     * Load users relationship on chat.
     * Simple operation - kept in repository.
     *
     * @param  Chat  $chat
     * @return Chat
     */
    public function loadUsers(Chat $chat): Chat
    {
        return $chat->load('users');
    }
}
