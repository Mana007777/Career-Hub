import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configure Laravel Echo with Reverb
window.Pusher = Pusher;

// Only initialize Echo if Reverb is configured
if (import.meta.env.VITE_REVERB_APP_KEY) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST || '127.0.0.1',
        wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
        wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME || 'http') === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            },
        },
    });
    
    // Log connection status
    const pusher = window.Echo.connector.pusher;
    
    pusher.connection.bind('connected', () => {
        console.log('✅ Echo connected to Reverb');
        console.log('📍 Connection state:', pusher.connection.state);
    });
    
    pusher.connection.bind('disconnected', () => {
        console.log('❌ Echo disconnected from Reverb');
    });
    
    pusher.connection.bind('error', (error) => {
        console.error('❌ Echo connection error:', error);
    });
    
    pusher.connection.bind('state_change', (states) => {
        console.log('🔄 Connection state changed:', states.previous, '→', states.current);
    });
} else {
    console.warn('⚠️ Reverb not configured. Set VITE_REVERB_APP_KEY in .env');
}
