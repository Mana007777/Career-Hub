<?php

namespace App\Support;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Carbon;

class SoraniTime
{
    public static function human(DateTimeInterface|string|null $date): string
    {
        if ($date === null) {
            return '';
        }

        if (app()->getLocale() === 'ckb') {
            return self::relative($date);
        }

        $time = $date instanceof CarbonInterface ? $date : Carbon::parse($date);

        return $time->diffForHumans();
    }

    public static function relative(DateTimeInterface|string|null $date): string
    {
        if ($date === null) {
            return '';
        }

        $time = $date instanceof CarbonInterface ? $date : Carbon::parse($date);
        $now = now();
        $isPast = $time->lte($now);

        $diffInSeconds = $time->diffInSeconds($now);

        if ($diffInSeconds < 10) {
            return 'ئێستا';
        }

        if ($diffInSeconds < 60) {
            return self::format($diffInSeconds, 'چرکە', $isPast);
        }

        $diffInMinutes = $time->diffInMinutes($now);
        if ($diffInMinutes < 60) {
            return self::format($diffInMinutes, 'خولەک', $isPast);
        }

        $diffInHours = $time->diffInHours($now);
        if ($diffInHours < 24) {
            return self::format($diffInHours, 'کاتژمێر', $isPast);
        }

        $diffInDays = $time->diffInDays($now);
        if ($diffInDays < 30) {
            return self::format($diffInDays, 'ڕۆژ', $isPast);
        }

        $diffInMonths = $time->diffInMonths($now);
        if ($diffInMonths < 12) {
            return self::format($diffInMonths, 'مانگ', $isPast);
        }

        $diffInYears = $time->diffInYears($now);

        return self::format($diffInYears, 'ساڵ', $isPast);
    }

    private static function format(int $value, string $unit, bool $isPast): string
    {
        return $isPast
            ? "{$value} {$unit} لەمەوبەر"
            : "لە ماوەی {$value} {$unit}ی داهاتوودا";
    }
}

