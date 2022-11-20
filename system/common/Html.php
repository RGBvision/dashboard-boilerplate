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
 * @version    4.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Html
{

    /**
     * Display minified and GZipped HTML
     *
     * @param string $data data to display
     */
    public static function output(string $data): void
    {
        $headers = [];

        $Gzip = str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');

        if (HTML_COMPRESSION) {
            $data = self::minifyHtml($data);
        }

        // Use GZIP compression if accepted
        if ($Gzip && GZIP_COMPRESSION) {
            ob_start('ob_gzhandler');
            $data = gzencode($data, 9);
            $headers[] = 'Content-Encoding: gzip';
        } else {
            ob_start();
        }

        $headers[] = 'Content-Type: text/html; charset=utf-8';
        $headers[] = 'Cache-Control: must-revalidate';

        if (OUTPUT_EXPIRE && !defined('NO_CACHE')) {
            $headers[] = 'Expires: ' . gmdate('r', time() + OUTPUT_EXPIRE_OFFSET);
        } else {
            // Disable browser caching
            $headers[] = 'Cache-Control: private, no-store, no-cache, must-revalidate';
            $headers[] = 'Expires: ' . gmdate('r');
        }

        $headers[] = 'Content-Length: ' . strlen($data);
        $headers[] = 'Vary: Accept-Encoding';

        Response::setHeaders($headers);

        echo $data;

        $output = ob_get_clean();

        echo $output;
    }

    private static function minimizeCSS(string $css): string
    {
        $css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css); // negative look ahead
        $css = preg_replace('/\s{2,}/', ' ', $css);
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        $css = preg_replace('/;}/', '}', $css);
        return $css;
    }

    // ToDo: refactor JS minification
    private static function minimizeJS(string $source): string
    {

        $res = [];

        //a list of characters that don't need spaces around them
        $NO_SPACE_NEAR = ' +=-*/%&|^!~?:;,.<>(){}[]';

        //loop through each line of the source, removing comments and unnecessary whitespace
        $lines = explode(PHP_EOL, $source);

        //keep track of whether we're in a string or not
        $in_string = false;

        //keep track of whether we're in a comment or not
        $multiline_comment = false;

        foreach ($lines as $line) {

            //remove whitespace from the start and end of the line
            $line = trim($line);

            //skip blank lines
            if ($line == '') continue;

            //remove "use strict" statements
            if (!$in_string && str_starts_with($line, '"use strict"')) continue;

            //loop through the current line
            $string_len = strlen($line);

            for ($position = 0; $position < $string_len; $position++) {
                //if currently in a string, check if the string ended (making sure to ignore escaped quotes)
                if ($in_string && $line[$position] === $in_string && ($position < 1 || $line[$position - 1] !== '\\')) {
                    $in_string = false;
                } else if ($multiline_comment) {
                    //check if this is the end of a multiline comment
                    if ($position > 0 && $line[$position] === "/" && $line[$position - 1] === "*") {
                        $multiline_comment = false;
                    }
                    continue;
                } //check everything else
                else if (!$in_string) {

                    //check if this is the start of a string
                    if ($line[$position] == '"' || $line[$position] == "'" || $line[$position] == '`') {
                        //record the type of string
                        $in_string = $line[$position];
                    } //check if this is the start of a single-line comment
                    else if ($position < $string_len - 1 && $line[$position] == '/' && $line[$position + 1] == '/') {
                        //this is the start of a single line comment, so skip the rest of the line
                        //break;
                    } //check if this is the start of a multiline comment
                    else if ($position < $string_len - 1 && $line[$position] == '/' && $line[$position + 1] == '*') {
                        $multiline_comment = true;
                        continue;
                    } else if (
                        $line[$position] == ' ' && (
                            //if this is not the first character, check if the character before it requires a space
                            ($position > 0 && str_contains($NO_SPACE_NEAR, $line[$position - 1]))
                            //if this is not the last character, check if the character after it requires a space
                            || ($position < $string_len - 1 && str_contains($NO_SPACE_NEAR, $line[$position + 1]))
                        )
                    ) {
                        //there is no need for this space, so keep going
                        continue;
                    }
                }

                //print the current character and continue
                $res[] = $line[$position];

            }

            //if this is a multi-line string, preserve the line break
            if ($in_string) {
                $res[] = PHP_EOL;
            }
        }

        return implode('', $res);

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

        $data = preg_replace_callback('/(<script[^>]*>)(.*?)(<\/script>)/is', function ($match) {
            return $match[1] . self::minimizeJS($match[2]) . $match[3];
        }, $data);

        $data = preg_replace_callback('/(<style[^>]*>)(.*?)(<\/style>)/is', function ($match) {
            return $match[1] . (self::minimizeCSS($match[2])) . $match[3];
        }, $data);
        
        $data = preg_replace($search, $replace, $data);

        return $data;
    }
}