# Mailtrap Email Configuration Setup

## IMPORTANT: Update Your .env File

To make email verification work for **ALL accounts** and send emails to Mailtrap, you MUST update your `.env` file with these settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username_here
MAIL_PASSWORD=your_mailtrap_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@careerop.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Steps:

1. **Get Mailtrap Credentials:**
   - Go to https://mailtrap.io
   - Sign up or log in
   - Go to your Inbox → SMTP Settings
   - Copy your Username and Password

2. **Update .env file:**
   - Replace `your_mailtrap_username_here` with your actual Mailtrap username
   - Replace `your_mailtrap_password_here` with your actual Mailtrap password

3. **Clear cache:**
   ```bash
   php artisan config:clear
   ```

## How It Works:

✅ **When ANY user registers** → Verification email is automatically sent to Mailtrap
✅ **When ANY user clicks "Send Verification Email"** → Email is sent to Mailtrap
✅ **All verification emails** → Go to your Mailtrap inbox
✅ **Click verification link** → User's email gets verified

## Current Status:

- ✅ User model implements MustVerifyEmail
- ✅ Email verification enabled in Fortify
- ✅ Verification routes registered
- ⚠️ **YOU NEED TO UPDATE .env WITH MAILTRAP CREDENTIALS**
