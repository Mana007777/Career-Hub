#!/bin/bash

# Script to update theme classes in blade files
# This adds dark: prefixes to common dark mode classes

FILES=(
    "resources/views/livewire/post.blade.php"
    "resources/views/livewire/user-profile.blade.php"
    "resources/views/livewire/post-detail.blade.php"
    "resources/views/livewire/chat-box.blade.php"
    "resources/views/livewire/chat-list.blade.php"
    "resources/views/livewire/cvs.blade.php"
)

# Note: This is a helper script. Manual updates are still needed for complex cases.
echo "Theme update script - Run manual replacements for best results"
