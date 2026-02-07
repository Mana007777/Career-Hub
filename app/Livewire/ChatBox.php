<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatBox extends Component
{
    public $chatId = null;
    public $otherUserId = null;
    public $otherUser = null;
    public $messages = [];
    public $newMessage = '';
    public $isOpen = false;
    public $chat = null;
    public $isRequest = false;
    public $pendingRequest = null;

    protected $listeners = [
        'openChat' => 'openChat',
        'closeChat' => 'closeChat'
    ];

    public function mount()
    {
        // Initialize
    }

    public function openChat($userId = null)
    {
        if (!$userId) {
            return;
        }
        
        $chatService = app(ChatService::class);
        $otherUser = User::findOrFail($userId);
        $currentUser = Auth::user();
        
        // Check if current user follows the other user
        $isFollowing = $currentUser->following()->where('following_id', $otherUser->id)->exists();
        $isFollowedBack = $currentUser->followers()->where('follower_id', $otherUser->id)->exists();
        
        $this->otherUser = $otherUser;
        $this->otherUserId = $userId;
        
        // Check for pending request (incoming - someone wants to chat with current user)
        $this->pendingRequest = \App\Models\ChatRequest::where('from_user_id', $otherUser->id)
            ->where('to_user_id', $currentUser->id)
            ->where('status', 'pending')
            ->with('message')
            ->latest()
            ->first();
        
        // If not following, don't allow chat
        if (!$isFollowing) {
            $this->isRequest = false;
            $this->isOpen = true;
            return;
        }
        
        // If following but not followed back, it's a request scenario (outgoing)
        if ($isFollowing && !$isFollowedBack) {
            $this->isRequest = true;
        } else {
            $this->isRequest = false;
        }
        
        $this->chat = $chatService->getOrCreateChat($otherUser);
        $this->chatId = $this->chat->id;
        $this->messages = collect($chatService->getMessages($this->chat));
        $this->isOpen = true;
        
        // Mark messages as read when opening chat (only if not a request scenario)
        if (!$this->isRequest) {
            $chatService->markMessagesAsRead($this->chat, Auth::id());
        }
        
        // Dispatch browser event for chat.js to set up Echo listener
        $this->dispatch('chat-opened', chatId: $this->chatId);
        
        // Dispatch event to update unread counts
        $this->dispatch('unread-counts-updated');
    }
    
    public function acceptRequest()
    {
        if (!$this->pendingRequest) {
            return;
        }
        
        $this->pendingRequest->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);
        
        // Follow back the user
        Auth::user()->following()->syncWithoutDetaching([$this->pendingRequest->from_user_id]);
        
        $this->pendingRequest = null;
        $this->isRequest = false;
        
        // Reload chat
        $chatService = app(ChatService::class);
        $this->chat = $chatService->getOrCreateChat($this->otherUser);
        $this->chatId = $this->chat->id;
        $this->messages = collect($chatService->getMessages($this->chat));
        
        // Mark messages as read
        $chatService->markMessagesAsRead($this->chat, Auth::id());
        
        $this->dispatch('unread-counts-updated');
    }
    
    public function rejectRequest()
    {
        if (!$this->pendingRequest) {
            return;
        }
        
        $this->pendingRequest->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);
        
        $this->pendingRequest = null;
        $this->closeChat();
    }

    public function closeChat()
    {
        $this->isOpen = false;
        $this->chatId = null;
        $this->otherUser = null;
        $this->messages = [];
        $this->newMessage = '';
        $this->isRequest = false;
        $this->pendingRequest = null;
        
        $this->dispatch('chat-closed');
    }

    public function sendMessage()
    {
        if (empty(trim($this->newMessage))) {
            return;
        }

        $chatService = app(ChatService::class);
        
        if (!$this->chat) {
            $this->chat = $chatService->getOrCreateChat($this->otherUser);
            $this->chatId = $this->chat->id;
        }

        $message = $chatService->sendMessage($this->chat, trim($this->newMessage));
        
        // Add message immediately to local collection
        $this->messages->push($message);
        $this->newMessage = '';
        
        // Broadcast the message via Reverb (will use BROADCAST_CONNECTION from .env)
        // IMPORTANT: Make sure BROADCAST_CONNECTION=reverb in .env, not 'log'!
        try {
            \Log::info('📤 Broadcasting message', [
                'chat_id' => $this->chatId,
                'message_id' => $message->id,
                'sender_id' => $message->sender_id,
                'broadcast_driver' => config('broadcasting.default'),
                'channel' => 'chat.' . $this->chatId
            ]);
            
            $event = new MessageSent($message);
            broadcast($event);
            
            \Log::info('✅ Message broadcasted successfully', [
                'chat_id' => $this->chatId,
                'message_id' => $message->id
            ]);
            
            // Debug logging (check storage/logs/laravel.log)
            if (config('broadcasting.default') === 'log') {
                \Log::warning('⚠️ Broadcasting is set to LOG, not REVERB! Change BROADCAST_CONNECTION=reverb in .env', [
                    'chat_id' => $this->chatId,
                    'message_id' => $message->id,
                    'current_driver' => config('broadcasting.default')
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('❌ Failed to broadcast message', [
                'error' => $e->getMessage(),
                'chat_id' => $this->chatId,
                'message_id' => $message->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        // Scroll to bottom
        $this->dispatch('scroll-to-bottom');
    }

    public function refreshMessages()
    {
        if ($this->chatId && $this->chat) {
            $chatService = app(ChatService::class);
            $this->messages = collect($chatService->getMessages($this->chat));
            $this->dispatch('scroll-to-bottom');
        }
    }
    
    public function addMessage($messageData)
    {
        // Add a new message to the collection without full refresh
        \Log::info('addMessage called', [
            'chatId' => $this->chatId,
            'messageData' => $messageData,
            'isCollection' => is_object($this->messages) && method_exists($this->messages, 'push')
        ]);
        
        if (!$this->chatId || !isset($messageData['chat_id'])) {
            \Log::warning('addMessage: Missing chatId or messageData chat_id', [
                'chatId' => $this->chatId,
                'hasChatId' => isset($messageData['chat_id'])
            ]);
            return;
        }
        
        // Ensure messages is a collection
        if (!is_object($this->messages) || !method_exists($this->messages, 'push')) {
            $this->messages = collect($this->messages);
        }
        
        // Only add if it's for this chat and not from current user (to avoid duplicates)
        $isForThisChat = $messageData['chat_id'] == $this->chatId;
        $isFromOtherUser = $messageData['sender_id'] != Auth::id();
        
        \Log::info('addMessage: Checking conditions', [
            'isForThisChat' => $isForThisChat,
            'isFromOtherUser' => $isFromOtherUser,
            'messageChatId' => $messageData['chat_id'],
            'currentChatId' => $this->chatId,
            'senderId' => $messageData['sender_id'],
            'authId' => Auth::id()
        ]);
        
        if ($isForThisChat && $isFromOtherUser) {
            // Check if message already exists
            $exists = $this->messages->contains('id', $messageData['id']);
            if (!$exists) {
                \Log::info('addMessage: Adding new message', [
                    'messageId' => $messageData['id'],
                    'messageText' => $messageData['message'],
                    'senderName' => $messageData['sender']['name'] ?? 'Unknown'
                ]);
                
                // Create message object
                $newMessage = (object) [
                    'id' => $messageData['id'],
                    'chat_id' => $messageData['chat_id'],
                    'sender_id' => $messageData['sender_id'],
                    'sender' => (object) $messageData['sender'],
                    'message' => $messageData['message'],
                    'created_at' => $messageData['created_at'],
                ];
                
                // Add to collection
                $this->messages->push($newMessage);
                
                \Log::info('addMessage: Message added successfully', [
                    'messageId' => $messageData['id'],
                    'totalMessages' => $this->messages->count()
                ]);
                
                $this->dispatch('scroll-to-bottom');
                
        // Dispatch event to update unread counts in bottom nav and chat list
        $this->dispatch('unread-counts-updated');
            } else {
                \Log::info('addMessage: Message already exists', ['messageId' => $messageData['id']]);
            }
        } else {
            \Log::warning('addMessage: Message not added', [
                'reason' => !$isForThisChat ? 'Wrong chat' : 'From current user',
                'messageChatId' => $messageData['chat_id'],
                'currentChatId' => $this->chatId,
                'isForThisChat' => $isForThisChat,
                'isFromOtherUser' => $isFromOtherUser
            ]);
        }
    }

    public function render()
    {
        return view('livewire.chat-box');
    }
}
