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
 * @version    2.8
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

// ToDo: refactor JS minification

class Html
{

    /**
     * Display minified and GZipped HTML
     *
     * @param string $data data to display
     * @return string
     */
    public static function output(string $data): string
    {
        $headers = [];

        $Gzip = strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false;

        if (HTML_COMPRESSION) {
            $data = self::minifyHtml($data);
        }

        if ($Gzip && GZIP_COMPRESSION) {
            $data = gzencode($data, 9);
            $headers[] = 'Content-Encoding: gzip';
        }

        $headers[] = 'Content-Type: text/html; charset=utf-8';
        $headers[] = 'Cache-Control: must-revalidate';
        if (OUTPUT_EXPIRE) {
            $headers[] = 'Expires: ' . gmdate('r', time() + OUTPUT_EXPIRE_OFFSET);
        }
        $headers[] = 'Content-Length: ' . strlen($data);
        $headers[] = 'Vary: Accept-Encoding';

        Response::setHeaders($headers);

        unset($headers);

        return $data;
    }

    private static function minimizeCSS(string $css): string
    {
        $css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css); // negative look ahead
        $css = preg_replace('/\s{2,}/', ' ', $css);
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        $css = preg_replace('/;}/', '}', $css);
        return $css;
    }

    // Minify HTML
    public static function minifyHtml(string $data): string
    {

        $search = [
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        ];

        $replace = [
            '>',
            '<',
            '\\1',
            '',
        ];

        $data = preg_replace($search, $replace, $data);

        $data = preg_replace_callback('/(<style[^>]*>)(.*?)(<\/style>)/i', function ($match) {
            return $match[1] . (self::minimizeCSS($match[2])) . $match[3];
        }, $data);

        return $data;
    }
}