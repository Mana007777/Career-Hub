<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\Message;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatList extends Component
{
    public $isOpen = false;
    public $chats = [];
    public $unreadCounts = [];
    public $requests = [];

    protected $listeners = [
        'openChatList' => 'open',
        'closeChatList' => 'close',
        'unread-counts-updated' => 'refreshChats',
    ];

    public function mount()
    {
        $this->loadChats();
    }

    public function open()
    {
        $this->isOpen = true;
        $this->loadChats();
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function refreshChats()
    {
        $this->loadChats();
    }

    public function loadChats()
    {
        if (!Auth::check()) {
            $this->chats = [];
            $this->unreadCounts = [];
            $this->requests = [];
            return;
        }

        $chatService = app(ChatService::class);
        $this->chats = $chatService->getUserChats();
        $this->unreadCounts = $chatService->getUnreadCountsPerUser(Auth::id());
        $this->requests = $chatService->getPendingRequests();
    }

    public function openChat($userId)
    {
        $this->close();
        $this->dispatch('openChat', userId: $userId);
    }

    public function acceptRequest($requestId, ChatService $chatService)
    {
        $request = $chatService->findChatRequest($requestId);
        
        if (!$request || $request->to_user_id !== Auth::id() || $request->status !== 'pending') {
            return;
        }
        
        $request->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);
        
        // Follow back the user
        Auth::user()->following()->syncWithoutDetaching([$request->from_user_id]);
        
        // Refresh the list
        $this->loadChats();
        
        // Open the chat
        $this->openChat($request->from_user_id);
        
        $this->dispatch('unread-counts-updated');
    }

    public function rejectRequest($requestId, ChatService $chatService)
    {
        $request = $chatService->findChatRequest($requestId);
        
        if (!$request || $request->to_user_id !== Auth::id() || $request->status !== 'pending') {
            return;
        }
        
        $request->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);
        
        // Refresh the list
        $this->loadChats();
    }

    public function render()
    {
        return view('livewire.chat-list');
    }
}
