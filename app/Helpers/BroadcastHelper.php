<?php

namespace App\Helpers;

use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Support\Facades\Log;

class BroadcastHelper
{
   
    public static function safeBroadcast($event): bool
    {
        try {
            broadcast($event);
            return true;
        } catch (BroadcastException $e) {
            Log::warning('Broadcasting failed: ' . $e->getMessage(), [
                'event' => get_class($event),
                'broadcast_driver' => config('broadcasting.default'),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::warning('Broadcasting error: ' . $e->getMessage(), [
                'event' => get_class($event),
                'broadcast_driver' => config('broadcasting.default'),
            ]);
            return false;
        }
    }
}
