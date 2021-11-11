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

    private static function minifyJavascriptCode($javascript)
    {
        $blocks = array('for', 'while', 'if', 'else');
        $javascript = preg_replace('/([-\+])\s+\+([^\s;]*)/', '$1 (+$2)', $javascript);
        // remove new line in statements
        $javascript = preg_replace('/\s+\|\|\s+/', ' || ', $javascript);
        $javascript = preg_replace('/\s+\&\&\s+/', ' && ', $javascript);
        $javascript = preg_replace('/\s*([=+-\/\*:?])\s*/', '$1 ', $javascript);
        // handle missing brackets {}
        foreach ($blocks as $block) {
            $javascript = preg_replace('/(\s*\b' . $block . '\b[^{\n]*)\n([^{\n]+)\n/i', '$1{$2}', $javascript);
        }
        // handle spaces
        $javascript = preg_replace(array("/\s*\n\s*/", "/\h+/"), array("\n", " "), $javascript); // \h+ horizontal white space
        $javascript = preg_replace(array('/([^a-z0-9\_])\h+/i', '/\h+([^a-z0-9\$\_])/i'), '$1', $javascript);
        $javascript = preg_replace('/\n?([[;{(\.+-\/\*:?&|])\n?/', '$1', $javascript);
        $javascript = preg_replace('/\n?([})\]])/', '$1', $javascript);
        $javascript = str_replace("\nelse", "else", $javascript);
        $javascript = preg_replace("/([^}])\n/", "$1;", $javascript);
        $javascript = preg_replace("/;?\n/", ";", $javascript);
        return $javascript;
    }

    private static function getNextKeyElement($javascript)
    {
        $elements = array();
        $keyMarkers = array('\'', '"', '//', '/*');
        foreach ($keyMarkers as $marker) {
            $elements[$marker] = strpos($javascript, $marker);
        }
        //regex to detect all regex
        $regex = "/[\k(](\/[\k\S]+\/)/";
        $matches = [];
        preg_match($regex, $javascript, $matches, PREG_OFFSET_CAPTURE, 1);
        if (!empty($matches)) {
            $elements[$matches[1][0]] = $matches[1][1];
        }
        $elements = array_filter($elements, static function ($k) {
            return $k !== false;
        });
        if (empty($elements)) {
            return false;
        }
        $min = min($elements);
        return array($min, array_keys($elements, $min)[0]);
    }

    private static function getNonEscapedQuoteIndex($string, $char, $start = 0): int
    {
        if (preg_match('/(\\\\*)(' . preg_quote($char, '/') . ')/', $string, $match, PREG_OFFSET_CAPTURE, $start)) {
            if (!isset($match[1][0]) || strlen($match[1][0]) % 2 === 0) {
                return $match[2][1];
            }
            return self::getNonEscapedQuoteIndex($string, $char, $match[2][1] + 1);
        }
        return -1;
    }

    private static function minifyJavascript(string $javascript): string
    {
        $buffer = '';
        while (list($idx_start, $keyElement) = self::getNextKeyElement($javascript)) {
            switch ($keyElement) {
                case '//':
                    $idx_start = strpos($javascript, '//');
                    $idx_end = strpos($javascript, "\n", $idx_start);
                    if ($idx_end !== false) {
                        $javascript = substr($javascript, 0, $idx_start) . substr($javascript, $idx_end);
                    } else {
                        $javascript = substr($javascript, 0, $idx_start);
                    }
                    break;
                case '/*':
                    $idx_start = strpos($javascript, '/*');
                    $idx_end = strpos($javascript, '*/', $idx_start) + 2;
                    $javascript = substr($javascript, 0, $idx_start) . substr($javascript, $idx_end);
                    break;
                default: // must be handle like string case
                    $idx_start = self::getNonEscapedQuoteIndex($javascript, $keyElement);
                    if (strlen($keyElement) === 1) {
                        // quote! Either ' or "
                        if ($javascript[$idx_start] === '\'') {
                            $idx_end = self::getNonEscapedQuoteIndex($javascript, '\'', $idx_start + 1) + 1;
                        } else {
                            $idx_end = self::getNonEscapedQuoteIndex($javascript, '"', $idx_start + 1) + 1;
                        }
                    } else {
                        // regex!
                        $idx_end = $idx_start + strlen($keyElement);
                    }
                    $buffer .= self::minifyJavascriptCode(substr($javascript, 0, $idx_start));
                    $quote = substr($javascript, $idx_start, ($idx_end - $idx_start));
                    $quote = str_replace("\\\n", ' ', $quote);
                    $buffer .= $quote;
                    $javascript = substr($javascript, $idx_end);
            }
        }
        $buffer .= self::minifyJavascriptCode($javascript);
        return $buffer;
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
            ''
        ];

        $data = preg_replace($search, $replace, $data);

        $data = preg_replace_callback('/(<style[^>]*>)(.*?)(<\/style>)/i', function ($match) {
            return $match[1] . (self::minimizeCSS($match[2])) . $match[3];
        }, $data);

        $data = preg_replace_callback('/(<script[^>]*>)(.*?)(<\/script>)/i', function ($match) {
            return $match[1] . (self::minifyJavascript($match[2])) . $match[3];
        }, $data);

        return $data;
    }
}