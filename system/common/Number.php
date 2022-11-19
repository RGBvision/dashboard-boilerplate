<?php

/**
 * This file is part of the dashboard.rgbvision.net package.
 *
 * (c) Alex Graham <contact@rgbvision.net>
 *
 * @package    dashboard.rgbvision.net
 * @author     Alex Graham <contact@rgbvision.net>
 * @copyright  Copyright 2017-2022, Alex Graham
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    1.1
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Number
{

    /**
     * Format size in bytes
     *
     * @param int $size
     * @param int $precision
     * @return string
     */
    public static function formatSize(int $size, int $precision = 2): string
    {
        if ($size <= 0) return '0B';
        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }


    /**
     * Format number
     *
     * @param float $number число
     * @param int $decimal количество знаков дробной части
     * @param string $after разделитель дробной части
     * @param string $thousand разделитель тысяч
     * @return string
     */
    public static function numFormat(float $number, int $decimal = 0, string $after = '.', string $thousand = '\''): string
    {
        return number_format($number, $decimal, $after, $thousand);
    }


    /**
     * Get micro time difference
     *
     * @param string $a start time in `msec sec` format
     * @param string $b end time in `msec sec` format
     * @return float
     */
    public static function microTimeDiff(string $a, string $b): float
    {
        [$a_dec, $a_sec] = explode(' ', $a);
        [$b_dec, $b_sec] = explode(' ', $b);

        return (float)$b_sec - (float)$a_sec + (float)$b_dec - (float)$a_dec;
    }

}