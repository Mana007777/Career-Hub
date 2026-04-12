<?php

namespace App\Support;

final class PageTitle
{
    /**
     * Browser title: "Segment | App" when segment is non-empty, otherwise app name only.
     */
    public static function format(?string $segment = null): string
    {
        $app = (string) config('app.name', 'Laravel');
        $segment = $segment === null ? '' : trim($segment);

        return $segment === '' ? $app : "{$segment} | {$app}";
    }
}
