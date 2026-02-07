<?php

namespace App\Helpers;

use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Support\Facades\Log;

class BroadcastHelper
{
    /**
     * Safely broadcast an event, catching and logging any errors
     * This prevents broadcasting errors from breaking the application
     */
    public static function safeBroadcast($event): bool
    {
        try {
            broadcast($event);
            return true;
        } catch (BroadcastException $e) {
            // Log the error but don't throw it
            Log::warning('Broadcasting failed: ' . $e->getMessage(), [
                'event' => get_class($event),
                'broadcast_driver' => config('broadcasting.default'),
            ]);
            return false;
        } catch (\Exception $e) {
            // Catch any other exceptions
            Log::warning('Broadcasting error: ' . $e->getMessage(), [
                'event' => get_class($event),
                'broadcast_driver' => config('broadcasting.default'),
            ]);
            return false;
        }
    }
}
