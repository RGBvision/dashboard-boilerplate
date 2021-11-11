<?php

/**
 * This file is part of the dashboard.rgbvision.net package.
 *
 * (c) Alex Graham <contact@rgbvision.net>
 *
 * @package    dashboard.rgbvision.net
 * @author     Alex Graham <contact@rgbvision.net>
 * @copyright  Copyright 2017-2021, Alex Graham
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    2.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Secure
{
    protected function __construct()
    {
        //--
    }

    /**
     * Get string without escaping and HTML entities
     *
     * @param string $text
     * @return string
     */
    public static function cleanOut(string $text): string
    {
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

        return stripslashes($text);
    }

    /**
     * Sanitize string
     *
     * @param string $string
     * @param bool $trim
     * @param bool $int
     * @param bool $str
     * @return string
     */
    public static function sanitize(string $string, bool $trim = false, bool $int = false, bool $str = false): string
    {
        $string = filter_var($string, FILTER_SANITIZE_STRING);
        $string = trim($string);
        $string = stripslashes($string);
        $string = strip_tags($string);
        $string = str_replace(
            [
                '‘',
                '’',
                '“',
                '”',
            ],
            [
                "'",
                "'",
                '"',
                '"',
            ],
            $string
        );

        if ($trim) {
            $string = substr($string, 0, $trim);
        }
        if ($int) {
            $string = preg_replace("/[^0-9\s]/", '', $string);
        }
        if ($str) {
            $string = preg_replace("/[^a-zA-Z\s]/", '', $string);
        }

        return $string;
    }

}