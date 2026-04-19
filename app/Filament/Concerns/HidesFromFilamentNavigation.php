<?php

namespace App\Filament\Concerns;

/**
 * Keeps CRUD routes registered but removes the resource from the sidebar.
 * Use for advanced / rare admin tools that would clutter the main navigation.
 */
trait HidesFromFilamentNavigation
{
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
