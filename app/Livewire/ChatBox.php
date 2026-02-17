<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Repositories\UserRepository;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChatBox extends Component
{
    use WithFileUploads;
    public $chatId = null;
    public $otherUserId = null;
    public $otherUser = null;
    public $messages = [];
    public $newMessage = '';
    public $attachment = null;
    public $isOpen = false;
    public $chat = null;
    public $isRequest = false;
    public $pendingRequest = null;

    protected $listeners = [
        'openChat' => 'openChat',
        'closeChat' => 'closeChat',
        'messageStatusUpdated' => 'handleStatusUpdate'
    ];

    public function mount()
    {
        // Initialize
    }

    public function openChat($userId = null, ChatService $chatService = null, UserRepository $userRepository = null)
    {
        if (!$userId) {
            return;
        }
        
        $chatService = $chatService ?? app(ChatService::class);
        $userRepository = $userRepository ?? app(UserRepository::class);
        
        $otherUser = $userRepository->findById($userId);
        $currentUser = Auth::user();
        
        // Check if current user follows the other user
        $isFollowing = $chatService->isFollowing($currentUser, $otherUser);
        $isFollowedBack = $chatService->isFollowedBack($currentUser, $otherUser);
        
        $this->otherUser = $otherUser;
        $this->otherUserId = $userId;
        
        // Check for pending request (incoming - someone wants to chat with current user)
        $this->pendingRequest = $chatService->getPendingRequest($otherUser->id, $currentUser->id);
        
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
            // Refresh messages to get updated status
            $this->messages = collect($chatService->getMessages($this->chat));
        }
        
        // Dispatch browser event for chat.js to set up Echo listener
        $this->dispatch('chat-opened', chatId: $this->chatId);
        
        // Dispatch event to update unread counts
        $this->dispatch('unread-counts-updated');
    }
    
    public function acceptRequest(ChatService $chatService)
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
        $chatService = app(ChatService::class);
        
        if (!$this->chat) {
            $this->chat = $chatService->getOrCreateChat($this->otherUser);
            $this->chatId = $this->chat->id;
        }

        $text = trim($this->newMessage ?? '');
        $attachmentsPayload = [];

        // Handle optional attachment upload
        if ($this->attachment) {
            $storedPath = $this->attachment->store('chat-attachments', 'public');
            $fileUrl = Storage::disk('public')->url($storedPath);
            $fileType = $this->attachment->getMimeType() ?? 'application/octet-stream';

            $attachmentsPayload[] = [
                'file_url' => $fileUrl,
                'file_type' => $fileType,
            ];
        }

        // Do not send completely empty messages (no text and no file)
        if ($text === '' && empty($attachmentsPayload)) {
            return;
        }

        $message = $chatService->sendMessage($this->chat, $text, $attachmentsPayload);
        
        // Add message immediately to local collection (messages may be array when rehydrated)
        $this->messages = collect($this->messages)->push($message)->values()->all();
        $this->newMessage = '';
        $this->attachment = null;
        
        // Broadcast the message via Reverb (will use BROADCAST_CONNECTION from .env)
        // IMPORTANT: Make sure BROADCAST_CONNECTION=reverb in .env, not 'log'!
        try {
            Log::info('📤 Broadcasting message', [
                'chat_id' => $this->chatId,
                'message_id' => $message->id,
                'sender_id' => $message->sender_id,
                'broadcast_driver' => config('broadcasting.default'),
                'channel' => 'chat.' . $this->chatId
            ]);
            
            $event = new MessageSent($message);
            broadcast($event);
            
            Log::info('✅ Message broadcasted successfully', [
                'chat_id' => $this->chatId,
                'message_id' => $message->id
            ]);
            
            // Debug logging (check storage/logs/laravel.log)
            if (config('broadcasting.default') === 'log') {
                Log::warning('⚠️ Broadcasting is set to LOG, not REVERB! Change BROADCAST_CONNECTION=reverb in .env', [
                    'chat_id' => $this->chatId,
                    'message_id' => $message->id,
                    'current_driver' => config('broadcasting.default')
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ Failed to broadcast message', [
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

    /**
     * Handle message status update from broadcast
     */
    public function handleStatusUpdate($messageId, $status)
    {
        // Update status in the collection (messages may be array when rehydrated)
        if (empty($this->messages)) {
            return;
        }
        $this->messages = collect($this->messages)->map(function ($message) use ($messageId, $status) {
            $id = is_object($message)
                ? ($message->id ?? null)
                : ($message['id'] ?? null);
            if ((int) $id === (int) $messageId) {
                if (is_object($message)) {
                    $message->status = $status;
                } else {
                    $message['status'] = $status;
                }
            }
            return $message;
        })->values()->all();
    }
    
    public function addMessage($messageData)
    {
        // Normalize to array (Livewire can pass object from JS)
        $messageData = is_array($messageData) ? $messageData : (array) $messageData;
        $chatIdFromMessage = $messageData['chat_id'] ?? $messageData['chatId'] ?? null;

        if (!$this->chatId || !$chatIdFromMessage || (int) $chatIdFromMessage !== (int) $this->chatId) {
            return;
        }

        $messages = collect($this->messages);
        $isFromOtherUser = (int) ($messageData['sender_id'] ?? 0) !== (int) Auth::id();
        if (!$isFromOtherUser) {
            return;
        }

        $messageId = $messageData['id'] ?? null;
        if (!$messageId || $messages->contains(fn ($m) => (is_object($m) ? $m->id : $m['id'] ?? null) == $messageId)) {
            return;
        }

        $sender = $messageData['sender'] ?? [];
        $sender = is_array($sender) ? $sender : (array) $sender;
        $newMessage = [
            'id' => $messageId,
            'chat_id' => $chatIdFromMessage,
            'sender_id' => $messageData['sender_id'] ?? null,
            'sender' => $sender,
            'message' => $messageData['message'] ?? '',
            'status' => $messageData['status'] ?? 'sent',
            'created_at' => $messageData['created_at'] ?? now()->toIso8601String(),
            'attachments' => $messageData['attachments'] ?? [],
        ];

        $this->messages = $messages->push($newMessage)->values()->all();
        $this->dispatch('scroll-to-bottom');
        $this->dispatch('unread-counts-updated');
    }

    public function render()
    {
        return view('livewire.chat-box');
    }
}
