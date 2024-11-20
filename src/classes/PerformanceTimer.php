<?php

namespace App\Classes;

class PerformanceTimer
{
    public static function measure(callable $callback): float
    {
        $startTime = microtime(true);
        $callback();
        $endTime = microtime(true);
        return $endTime - $startTime;
    }

    public static function format(float $timeInSeconds): string
    {
        if ($timeInSeconds < 0.001) {
            return number_format($timeInSeconds * 1000000, 2) . " μs";
        } elseif ($timeInSeconds < 1) {
            return number_format($timeInSeconds * 1000, 2) . " ms";
        } else {
            return number_format($timeInSeconds, 3) . " s";
        }
    }
}