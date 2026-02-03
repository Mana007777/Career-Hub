#!/bin/bash

echo "=== Fixing Mailpit Configuration ==="
echo ""

# Check current .env settings
echo "Current .env mail settings:"
grep "^MAIL_" .env | head -10
echo ""

# Update .env file
echo "Updating .env file for Mailpit..."
sed -i 's/^MAIL_MAILER=.*/MAIL_MAILER=smtp/' .env
sed -i 's/^MAIL_PORT=.*/MAIL_PORT=1025/' .env
sed -i 's/^MAIL_HOST=.*/MAIL_HOST=127.0.0.1/' .env

# Add MAIL_ENCRYPTION if it doesn't exist
if ! grep -q "^MAIL_ENCRYPTION" .env; then
    echo "MAIL_ENCRYPTION=null" >> .env
fi

echo ""
echo "Updated .env settings:"
grep "^MAIL_" .env | head -10
echo ""

# Clear config cache
echo "Clearing config cache..."
php artisan config:clear

echo ""
echo "✅ Configuration updated!"
echo ""
echo "Now test by:"
echo "1. Click 'Send Verification Email' on your profile"
echo "2. Check Mailpit at http://localhost:8025"
echo ""
