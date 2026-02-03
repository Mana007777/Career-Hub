# Mailpit Email Configuration Setup

## Mailpit Configuration for .env File

Update your `.env` file with these Mailpit settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@careerop.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## How to Use Mailpit:

1. **Install Mailpit** (if not already installed):
   ```bash
   # On Linux/Mac
   curl -sL https://github.com/axllent/mailpit/releases/latest/download/mailpit-linux-amd64.tar.gz | tar xz
   sudo mv mailpit /usr/local/bin/
   
   # Or using package manager
   # Ubuntu/Debian
   wget -O- https://apt.gophers.dev/apt/gophers.dev-archive.key | sudo apt-key add -
   echo deb https://apt.gophers.dev/ stable main | sudo tee /etc/apt/sources.list.d/gophers.dev.list
   sudo apt update && sudo apt install mailpit
   ```

2. **Start Mailpit:**
   ```bash
   mailpit
   ```
   This will start Mailpit on:
   - SMTP: `127.0.0.1:1025` (for sending emails)
   - Web UI: `http://localhost:8025` (to view emails)

3. **Update .env file** with the settings above

4. **Clear config cache:**
   ```bash
   php artisan config:clear
   ```

5. **Test it:**
   - Click "Send Verification Email" on your profile
   - Go to `http://localhost:8025` to see the email in Mailpit

## Benefits of Mailpit:

✅ **Local** - Runs on your machine, no external service needed
✅ **Fast** - No network latency
✅ **Simple** - No authentication required
✅ **Free** - No API keys or limits
✅ **Web UI** - Beautiful interface to view all emails
