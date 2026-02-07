// Real-time chat functionality with Laravel Reverb
let currentChatListener = null;
let currentChatId = null;

// Wait for Echo to be available
function waitForEcho(callback) {
    if (typeof window.Echo !== 'undefined') {
        callback();
    } else {
        setTimeout(() => waitForEcho(callback), 100);
    }
}

// Set up Echo listener for a chat
function setupChatListener(chatId) {
    // Prevent duplicate subscriptions
    if (currentChatId === chatId && currentChatListener) {
        console.log('⚠️ Already subscribed to chat:', chatId);
        return;
    }
    
    waitForEcho(() => {
        // Remove previous listener if exists
        if (currentChatListener) {
            try {
                // Echo private channel doesn't have stop(), use leave() instead
                if (typeof currentChatListener.leave === 'function') {
                    currentChatListener.leave();
                } else if (typeof currentChatListener.stopListening === 'function') {
                    currentChatListener.stopListening('message.sent');
                }
            } catch (e) {
                console.warn('Error cleaning up previous listener:', e);
            }
            currentChatListener = null;
            currentChatId = null;
        }
        
        if (chatId && window.Echo) {
            console.log('Setting up Echo listener for chat:', chatId);
            currentChatId = chatId;
            
            currentChatListener = window.Echo.private(`chat.${chatId}`)
                .listen('.message.sent', (e) => {
                    console.log('✅ New message received via Echo:', e);
                    console.log('📦 Full event object:', JSON.stringify(e, null, 2));
                    console.log('📦 Event data structure:', {
                        'e.chat_id': e.chat_id,
                        'e.chatId': e.chatId,
                        'e.id': e.id,
                        'e.sender_id': e.sender_id,
                        'e.message': e.message,
                        'e.sender': e.sender,
                        'e.created_at': e.created_at
                    });
                    
                    // Check if data is nested (sometimes Echo wraps it)
                    let eventData = e;
                    if (e.data) {
                        console.log('📦 Data is nested in e.data:', e.data);
                        eventData = e.data;
                    }
                    
                    // Dispatch custom event for Livewire to handle
                    // The event data structure matches what MessageSent broadcasts
                    const messageData = {
                        chatId: eventData.chat_id || eventData.chatId || chatId,
                        message: {
                            id: eventData.id,
                            chat_id: eventData.chat_id || eventData.chatId || chatId,
                            sender_id: eventData.sender_id,
                            sender: eventData.sender || {},
                            message: eventData.message,
                            created_at: eventData.created_at
                        }
                    };
                    
                    console.log('📤 Dispatching new-message event with data:', messageData);
                    window.dispatchEvent(new CustomEvent('new-message', {
                        detail: messageData
                    }));
                })
                .subscribed(() => {
                    console.log(`✅ Successfully subscribed to private channel: chat.${chatId}`);
                })
                .error((error) => {
                    console.error('❌ Echo subscription error:', error);
                });
            
            console.log(`🔌 Attempting to subscribe to private channel: chat.${chatId}`);
        }
    });
}

// Listen for chat opened event (Livewire dispatches as browser CustomEvent)
window.addEventListener('chat-opened', function(event) {
    // Livewire 3 dispatches events with named parameters in event.detail
    const chatId = event.detail?.chatId || event.detail?.[0]?.chatId;
    if (chatId) {
        console.log('📬 chat-opened event received, chatId:', chatId);
        setupChatListener(chatId);
    } else {
        console.warn('⚠️ chat-opened event received but no chatId found:', event.detail);
    }
});

// Clean up listener when chat is closed
window.addEventListener('chat-closed', function() {
    if (currentChatListener) {
        try {
            if (typeof currentChatListener.leave === 'function') {
                currentChatListener.leave();
            } else if (typeof currentChatListener.stopListening === 'function') {
                currentChatListener.stopListening('message.sent');
            }
        } catch (e) {
            console.warn('Error cleaning up listener:', e);
        }
        currentChatListener = null;
        currentChatId = null;
    }
});

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Chat.js loaded, waiting for Echo...');
});
