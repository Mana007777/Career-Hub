# Laravel Reverb Setup Guide

## Installation Steps

1. **Install Composer Dependencies:**
   ```bash
   composer require laravel/reverb
   ```

2. **Install NPM Dependencies:**
   ```bash
   npm install laravel-echo pusher-js
   ```

3. **Publish Reverb Configuration (if needed):**
   ```bash
   php artisan vendor:publish --tag=reverb-config
   ```

4. **Add to .env file:**
   ```env
   BROADCAST_CONNECTION=reverb
   
   REVERB_APP_ID=your-app-id
   REVERB_APP_KEY=your-app-key
   REVERB_APP_SECRET=your-app-secret
   REVERB_HOST=127.0.0.1
   REVERB_PORT=8080
   REVERB_SCHEME=http
   ```

5. **Add to .env file (for frontend):**
   ```env
   VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
   VITE_REVERB_HOST="${REVERB_HOST}"
   VITE_REVERB_PORT="${REVERB_PORT}"
   VITE_REVERB_SCHEME="${REVERB_SCHEME}"
   ```

6. **Start Reverb Server:**
   ```bash
   php artisan reverb:start
   ```

7. **Build Assets:**
   ```bash
   npm run build
   ```

## Running in Development

You'll need to run three processes:

1. **Laravel Application:**
   ```bash
   php artisan serve
   ```

2. **Reverb Server:**
   ```bash
   php artisan reverb:start
   ```

3. **Vite (for assets):**
   ```bash
   npm run dev
   ```

Or use the dev script:
```bash
composer run dev
```

Then add Reverb to your concurrently script in `composer.json`:
```json
"php artisan reverb:start"
```

## How It Works

- When a user clicks the chat icon, the chat box opens
- Laravel Echo connects to Reverb via WebSocket
- Messages are broadcast in real-time using Laravel Broadcasting
- The `MessageSent` event broadcasts to the `chat.{chatId}` channel
- All participants in the chat receive the message instantly

## Security

- Channel authorization is handled in `routes/channels.php`
- Only users who are participants in a chat can listen to that chat's channel
- CSRF tokens are included in all Echo requests
