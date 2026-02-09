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
                // Ignore cleanup errors
            }
            currentChatListener = null;
            currentChatId = null;
        }
        
        if (chatId && window.Echo) {
            currentChatId = chatId;
            
            currentChatListener = window.Echo.private(`chat.${chatId}`)
                .listen('.message.sent', (e) => {
                    // Check if data is nested (sometimes Echo wraps it)
                    let eventData = e;
                    if (e.data) {
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
                            status: eventData.status || 'sent',
                            created_at: eventData.created_at
                        }
                    };
                    window.dispatchEvent(new CustomEvent('new-message', {
                        detail: messageData
                    }));
                })
                .listen('.message.status.updated', (e) => {
                    // Handle nested data structure
                    let eventData = e;
                    if (e.data) {
                        eventData = e.data;
                    }
                    
                    const messageId = eventData.message_id || eventData.messageId;
                    const status = eventData.status;
                    if (messageId && status) {
                        updateMessageStatus(messageId, status);
                    } else {
                        // Missing message_id or status in event; ignore
                    }
                })
                .subscribed(() => {
                    // Successfully subscribed to private channel
                })
                .error((error) => {
                    console.error('❌ Echo subscription error:', error);
                });
        }
    });
}

// Listen for chat opened event (Livewire dispatches as browser CustomEvent)
window.addEventListener('chat-opened', function(event) {
    // Livewire 3 dispatches events with named parameters in event.detail
    const chatId = event.detail?.chatId || event.detail?.[0]?.chatId;
    if (chatId) {
        setupChatListener(chatId);
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

// Set up presence channel for user online/offline status
function setupPresenceListener() {
    waitForEcho(() => {
        if (window.presenceListener) {
            return; // Already set up
        }
        
        window.presenceListener = window.Echo.join('presence.users')
            .here((users) => {
                users.forEach(user => {
                    updateUserStatus(user.id, true);
                });
            })
            .joining((user) => {
                updateUserStatus(user.id, true);
            })
            .leaving((user) => {
                updateUserStatus(user.id, false);
            })
            .listen('.user.presence.changed', (e) => {
                if (e.user_id) {
                    updateUserStatus(e.user_id, e.is_online);
                }
            });
    });
}

// Update user status in the UI
function updateUserStatus(userId, isOnline) {
    // Update status indicators
    const indicators = document.querySelectorAll(`.user-status-indicator-${userId}`);
    indicators.forEach(indicator => {
        if (indicator._x_dataStack && indicator._x_dataStack[0]) {
            indicator._x_dataStack[0].isOnline = isOnline;
        }
    });

    // Update status text
    const statusTexts = document.querySelectorAll(`.user-status-text-${userId}`);
    statusTexts.forEach(text => {
        if (text._x_dataStack && text._x_dataStack[0]) {
            text._x_dataStack[0].isOnline = isOnline;
        }
    });
}

// Set up message status listener
function setupMessageStatusListener(chatId) {
    waitForEcho(() => {
        if (!chatId || !window.Echo) {
            return;
        }
        
        const statusListenerKey = `status-${chatId}`;
        if (window[statusListenerKey]) {
            return; // Already listening
        }
        
        window[statusListenerKey] = window.Echo.private(`chat.${chatId}`)
            .listen('.message.status.updated', (e) => {
                if (e.message_id && e.status) {
                    updateMessageStatus(e.message_id, e.status);
                }
            });
    });
}

// Update message status in the UI
function updateMessageStatus(messageId, status) {
    // Dispatch event for Alpine/Livewire listeners
    // First, dispatch custom event for Alpine.js listeners
    window.dispatchEvent(new CustomEvent('message-status-updated', {
        detail: { messageId: parseInt(messageId), status }
    }));
    
    // Then update Alpine.js data directly
    const statusElements = document.querySelectorAll(`.message-status-${messageId}`);
    statusElements.forEach(element => {
        // Try Alpine.js data stack first
        if (element._x_dataStack && element._x_dataStack[0]) {
            element._x_dataStack[0].status = status;
        } else {
            // Fallback: update text directly
            const statusText = status === 'seen' ? '✓✓ Seen' : (status === 'delivered' ? '✓✓ Delivered' : '✓ Sent');
            element.textContent = statusText;
            
            // Update color classes
            element.classList.remove('text-gray-400', 'text-blue-400', 'text-green-400');
            if (status === 'seen') {
                element.classList.add('text-green-400');
            } else if (status === 'delivered') {
                element.classList.add('text-blue-400');
            } else {
                element.classList.add('text-gray-400');
            }
        }
    });
    
    // Call Livewire method to update status in component
    if (window.Livewire) {
        // Find all Livewire components and try to call the method
        const components = window.Livewire.all();
        for (const component of components) {
            if (component && typeof component.call === 'function') {
                try {
                    component.call('handleStatusUpdate', parseInt(messageId), status)
                        .catch(() => {});
                } catch (e) {
                    // Ignore errors
                }
            }
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    setupPresenceListener();
});

// Enhanced setupChatListener to include status updates
const originalSetupChatListener = setupChatListener;
setupChatListener = function(chatId) {
    originalSetupChatListener(chatId);
    // Status updates are now handled in the main listener above
};
