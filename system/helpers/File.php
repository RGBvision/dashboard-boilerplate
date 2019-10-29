<?php

/**
 * This file is part of the RGB.dashboard package.
 *
 * (c) Alexey Graham <contact@rgbvision.net>
 *
 * @package    RGB.dashboard
 * @author     Alexey Graham <contact@rgbvision.net>
 * @copyright  2017-2019 RGBvision
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    1.7
 * @link       https://dashboard.rgbvision.net
 * @since      Class available since Release 1.0
 */

class File
{
    private static $mime_types = array(
        'aac' => 'audio/aac',
        'atom' => 'application/atom+xml',
        'avi' => 'video/avi',
        'bmp' => 'image/x-ms-bmp',
        'c' => 'text/x-c',
        'class' => 'application/octet-stream',
        'css' => 'text/css',
        'csv' => 'text/csv',
        'deb' => 'application/x-deb',
        'dll' => 'application/x-msdownload',
        'dmg' => 'application/x-apple-diskimage',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'exe' => 'application/octet-stream',
        'flv' => 'video/x-flv',
        'gif' => 'image/gif',
        'gz' => 'application/x-gzip',
        'h' => 'text/x-c',
        'htm' => 'text/html',
        'html' => 'text/html',
        'ini' => 'text/plain',
        'jar' => 'application/java-archive',
        'java' => 'text/x-java',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'js' => 'text/javascript',
        'json' => 'application/json',
        'mid' => 'audio/midi',
        'midi' => 'audio/midi',
        'mka' => 'audio/x-matroska',
        'mkv' => 'video/x-matroska',
        'mp3' => 'audio/mpeg',
        'mp4' => 'application/mp4',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ogg' => 'audio/ogg',
        'pdf' => 'application/pdf',
        'php' => 'text/x-php',
        'png' => 'image/png',
        'psd' => 'image/vnd.adobe.photoshop',
        'py' => 'application/x-python',
        'ra' => 'audio/vnd.rn-realaudio',
        'ram' => 'audio/vnd.rn-realaudio',
        'rar' => 'application/x-rar-compressed',
        'rss' => 'application/rss+xml',
        'safariextz' => 'application/x-safari-extension',
        'sh' => 'text/x-shellscript',
        'shtml' => 'text/html',
        'swf' => 'application/x-shockwave-flash',
        'tar' => 'application/x-tar',
        'tif' => 'image/tiff',
        'tiff' => 'image/tiff',
        'torrent' => 'application/x-bittorrent',
        'txt' => 'text/plain',
        'wav' => 'audio/wav',
        'webp' => 'image/webp',
        'wma' => 'audio/x-ms-wma',
        'xls' => 'application/vnd.ms-excel',
        'xml' => 'text/xml',
        'zip' => 'application/zip',
    );

    protected function __construct()
    {
        //
    }

    public static function exists(string $filename)
    {
        return (file_exists($filename) && is_file($filename));
    }

    public static function delete($filename): void
    {
        if (is_array($filename)) {
            foreach ($filename as $file) {
                @unlink((string)$file);
            }
        } else {
            @unlink((string)$filename);
        }

    }

    public static function rename(string $from, string $to): bool
    {

        if (!self::exists($to)) {
            return rename($from, $to);
        }

        return false;
    }

    public static function copy(string $from, string $to): bool
    {

        if (!self::exists($from) || self::exists($to)) {
            return false;
        }

        return copy($from, $to);
    }

    public static function ext(string $filename): string
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }


    public static function name(string $filename): string
    {
        return pathinfo($filename, PATHINFO_FILENAME);
    }

    public static function getContent(string $filename): string
    {
        if (self::exists($filename)) {
            return file_get_contents($filename);
        }
        return '';
    }

    public static function putContent($filename, $content, $create_file = true, $append = false, $chmod = 0666)
    {
        $filename = (string)$filename;
        $content = (string)$content;
        $create_file = (bool)$create_file;
        $append = (bool)$append;

        if (!$create_file && File::exists($filename)) {
            throw new RuntimeException(vsprintf("%s(): The file '{$filename}' doesn't exist", array(__METHOD__)));
        }

        Dir::create(dirname($filename));

        $handler = ($append)
            ? @fopen($filename, 'ab')
            : @fopen($filename, 'wb');

        if ($handler === false) {
            throw new RuntimeException(vsprintf("%s(): The file '{$filename}' could not be created.", array(__METHOD__)));
        }

        $level = error_reporting();

        error_reporting(0);

        $write = fwrite($handler, $content);

        if ($write === false) {
            throw new RuntimeException(vsprintf("%s(): The file '{$filename}' could not be created.", array(__METHOD__)));
        }

        fclose($handler);

        chmod($filename, $chmod);

        error_reporting($level);

        return true;
    }

    public static function lastChange($filename)
    {
        $filename = (string)$filename;

        if (self::exists($filename)) {
            return filemtime($filename);
        }

        return false;
    }

    public static function lastAccess($filename)
    {
        $filename = (string)$filename;

        if (self::exists($filename)) {
            return fileatime($filename);
        }

        return false;
    }

    public static function mime(string $file, bool $guess = true)
    {

        if (function_exists('finfo_open')) {
            $info = finfo_open(FILEINFO_MIME_TYPE);

            $mime = finfo_file($info, $file);

            finfo_close($info);

            return $mime;

        }

        if ($guess === true) {
            $mime_types = self::$mime_types;

            $extension = pathinfo($file, PATHINFO_EXTENSION);

            return $mime_types[$extension] ?? null;
        }

        return null;
    }

    public static function download($file, $content_type = null, $filename = null, $kbps = 0): void
    {
        $file = (string)$file;
        $content_type = ($content_type === null) ? null : (string)$content_type;
        $filename = ($filename === null) ? null : (string)$filename;
        $kbps = (int)$kbps;

        if (file_exists($file) === false || is_readable($file) === false) {
            throw new RuntimeException(vsprintf("%s(): Failed to open stream.", array(__METHOD__)));
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if ($content_type === null) {
            $content_type = self::mime($file);
        }

        if ($filename === null) {
            $filename = basename($file);
        }

        header('Content-type: ' . $content_type);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($file));

        @set_time_limit(0);

        if ($kbps === 0) {
            readfile($file);
        } else {
            $handle = fopen($file, 'rb');

            while (!feof($handle) && !connection_aborted()) {
                $s = microtime(true);

                echo fread($handle, round($kbps * 1024));

                if (($wait = 1e6 - (microtime(true) - $s)) > 0)
                    usleep($wait);
            }

            fclose($handle);
        }
        exit();
    }

    public static function display(string $file, ?string $content_type = null, ?string $filename = null): void
    {

        if (file_exists($file) === false || is_readable($file) === false) {
            throw new RuntimeException(vsprintf("%s(): Failed to open stream.", array(__METHOD__)));
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if ($content_type === null) {
            $content_type = self::mime($file);
        }

        if ($filename === null) {
            $filename = basename($file);
        }

        header('Content-type: ' . $content_type);
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($file));

        readfile($file);

        exit();
    }

    public static function writable(string $file): bool
    {

        if (!file_exists($file)) {
            throw new RuntimeException(vsprintf("%s(): The file '{$file}' doesn't exist", array(__METHOD__)));
        }

        $perms = fileperms($file);

        return (is_writable($file) || ($perms & 0x0080) || ($perms & 0x0010) || ($perms & 0x0002));
    }

}