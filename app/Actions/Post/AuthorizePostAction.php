<?php

namespace App\Actions\Post;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class AuthorizePostAction
{
    public function canEdit(Post $post): bool
    {
        
        if (Auth::check() && Auth::user()->isAdmin()) {
            return true;
        }
        return $post->user_id === Auth::id();
    }

    public function canDelete(Post $post): bool
    {
        // Allow admins or post owners
        if (Auth::check() && Auth::user()->isAdmin()) {
            return true;
        }
        return $post->user_id === Auth::id();
    }
}
