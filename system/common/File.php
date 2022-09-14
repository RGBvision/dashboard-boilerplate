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
 * @version    3.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class File
{
    private static array $mime_types = [
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
    ];

    /**
     * Check if file exists
     *
     * @param string $path path to file
     * @return bool
     */
    public static function exists(string $path): bool
    {
        return (file_exists($path) && is_file($path));
    }

    /**
     * Delete file(s)
     *
     * @param array|string $path path(s) to file(s)
     */
    public static function delete(array|string $path): void
    {
        if (is_array($path)) {
            foreach ($path as $file) {
                @unlink((string)$file);
            }
        } else {
            @unlink((string)$path);
        }

    }

    /**
     * Rename file
     *
     * @param string $from old name
     * @param string $to new name
     * @return bool
     */
    public static function rename(string $from, string $to): bool
    {

        if (!self::exists($to)) {
            return rename($from, $to);
        }

        return false;
    }

    /**
     * Copies file
     *
     * @param string $from path to source file
     * @param string $to destination file
     * @return bool
     */
    public static function copy(string $from, string $to): bool
    {

        if (!self::exists($from) || self::exists($to)) {
            return false;
        }

        return copy($from, $to);
    }

    /**
     * Get file extension
     *
     * @param string $path file path
     * @return string
     */
    public static function ext(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Get file name
     *
     * @param string $path file path
     * @return string
     */
    public static function name(string $path): string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * Get file basename
     *
     * @param string $path file path
     * @return string
     */
    public static function basename(string $path): string
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    /**
     * Get file dir
     *
     * @param string $path file path
     * @return string
     */
    public static function path(string $path): string
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    /**
     * Get file size
     *
     * @param string $path file path
     * @return int
     */
    public static function size(string $path): int
    {
        if (self::exists($path)) {
            return (int)filesize($path);
        }
        return 0;
    }

    /**
     * Get file contents
     *
     * @param string $path file path
     * @return string
     */
    public static function getContents(string $path): string
    {
        if (self::exists($path)) {
            return file_get_contents($path);
        }
        return '';
    }

    /**
     * Write string to file
     *
     * @param string $path file path
     * @param string $content content to write
     * @param bool $create_file create file if not exists
     * @param bool $append append content if file exists
     * @param int $chmod set file mode
     * @return bool
     */
    public static function putContents(string $path, string $content, bool $create_file = true, bool $append = false, int $chmod = 0666): bool
    {
        if (!$create_file && !File::exists($path)) {
            throw new RuntimeException(vsprintf("%s(): The file '$path' doesn't exist", [__METHOD__]));
        }

        Dir::create(dirname($path));

        $handler = ($append)
            ? @fopen($path, 'ab')
            : @fopen($path, 'wb');

        if ($handler === false) {
            throw new RuntimeException(vsprintf("%s(): The file '$path' could not be created.", [__METHOD__]));
        }

        $level = error_reporting();

        error_reporting(0);

        $write = fwrite($handler, $content);

        if ($write === false) {
            throw new RuntimeException(vsprintf("%s(): The file '$path' could not be created.", [__METHOD__]));
        }

        fclose($handler);

        chmod($path, $chmod);

        error_reporting($level);

        return true;
    }


    /**
     * Get file modification time
     *
     * @param string $path path to file
     * @return bool|int
     */
    public static function lastChange(string $path): bool|int
    {
        if (self::exists($path)) {
            return filemtime($path);
        }

        return false;
    }


    /**
     * Get last access time of file
     *
     * @param string $path path to file
     * @return bool|int
     */
    public static function lastAccess(string $path): bool|int
    {
        if (self::exists($path)) {
            return fileatime($path);
        }

        return false;
    }

    /**
     * Get file MIME type
     *
     * @param string $path path to file
     * @param bool $guess as fallback get MIME by extension
     * @return string|null
     */
    public static function mime(string $path, bool $guess = true): ?string
    {

        if (function_exists('finfo_open')) {
            $info = finfo_open(FILEINFO_MIME_TYPE);

            $mime = finfo_file($info, $path);

            finfo_close($info);

            return $mime;

        }

        if ($guess === true) {
            $mime_types = self::$mime_types;

            $extension = pathinfo($path, PATHINFO_EXTENSION);

            return $mime_types[$extension] ?? null;
        }

        return null;
    }

    /**
     * Get extension by MIME type
     *
     * @param string $mime
     * @return string
     */
    public static function extByMime(string $mime): string
    {
        return array_search($mime, self::$mime_types) ?: '';
    }

    /**
     * @param string $file
     * @param string|null $filename
     * @return string|null
     */
    private static function checkFile(string $file, ?string $filename): ?string
    {
        if (file_exists($file) === false || is_readable($file) === false) {
            throw new RuntimeException(vsprintf("%s(): Failed to open stream.", [__METHOD__]));
        }

        if ($filename === null) {
            $filename = basename($file);
        }

        return $filename;
    }

    /**
     * Output file for download to browser
     *
     * @param string $path path to file
     * @param string|null $content_type file content type
     * @param string|null $filename displayed file name
     * @param int $kbps limit download speed
     */
    public static function download(string $path, ?string $content_type = null, ?string $filename = null, int $kbps = 0): never
    {
        $filename = self::checkFile($path, $filename);

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if ($content_type === null) {
            $content_type = self::mime($path);
        }

        header('Content-type: ' . $content_type);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));

        @set_time_limit(0);

        if ($kbps === 0) {
            readfile($path);
        } else {
            $handle = fopen($path, 'rb');

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

    /**
     * Display file in browser
     *
     * @param string $path path to file
     * @param string|null $content_type file content type
     * @param string|null $filename displayed file name
     */
    public static function display(string $path, ?string $content_type = null, ?string $filename = null): never
    {

        $filename = self::checkFile($path, $filename);

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if ($content_type === null) {
            $content_type = self::mime($path);
        }

        header('Content-type: ' . $content_type);
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));

        readfile($path);

        exit();
    }

    /**
     * Output file from URL for download to browser
     *
     * @param string $url file URL
     * @param string $content_type file content type
     * @param string $filename displayed file name
     */
    public static function download_url(string $url, string $content_type, string $filename): never
    {

        $content = @file_get_contents($url);

        if ($content !== false) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            header('Content-type: ' . $content_type);
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . strlen($content));

            echo $content;
        }

        exit();
    }

    /**
     * Output string for download as file to browser
     *
     * @param string $content data to download
     * @param string $content_type file content type
     * @param string $filename displayed file name
     */
    public static function download_string(string $content, string $content_type, string $filename): never
    {

        if (!empty($content)) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            header('Content-type: ' . $content_type);
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . strlen($content));

            echo $content;
        }

        exit();
    }

    /**
     * Output string as file to browser
     *
     * @param string $content data to download
     * @param string $content_type file content type
     * @param string $filename displayed file name
     */
    public static function display_string(string $content, string $content_type, string $filename): never
    {

        if (!empty($content)) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            header('Content-type: ' . $content_type);
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Content-Length: ' . strlen($content));

            echo $content;
        }

        exit();
    }

    /**
     * Check if file writable
     *
     * @param string $path path to file
     * @return bool
     */
    public static function writable(string $path): bool
    {

        if (!file_exists($path)) {
            throw new RuntimeException(vsprintf("%s(): The file '{$path}' doesn't exist", [__METHOD__]));
        }

        $perms = fileperms($path);

        return (is_writable($path) || ($perms & 0x0080) || ($perms & 0x0010) || ($perms & 0x0002));
    }

    /**
     * Upload file from form
     *
     * @param string $key file key from form
     * @param string $target destination file
     * @param array|null $allowed_mime_types allowed mime types for upload
     * @param bool $rewrite Overwrite target file if already exists. If set to `false` and the file exists, function will not load the file and returns `false`.
     * @return bool
     */
    public static function upload(string $key, string $target, ?array $allowed_mime_types = null, bool $rewrite = false): bool
    {

        if (
            ($uploaded_file = Request::file($key, $allowed_mime_types)) &&
            ($dir = pathinfo($target, PATHINFO_DIRNAME)) &&
            (Dir::create($dir))
        ) {

            if (self::exists($target) && !$rewrite) {
                self::delete($uploaded_file);
                return false;
            }

            self::delete($target);
            self::rename($uploaded_file, $target);

            return self::exists($target);

        }

        return false;
    }

    /**
     * Find files by mask
     *
     * @param string $mask mask
     * @return array|null
     */
    public static function find(string $mask): ?array
    {
        $res = [];

        foreach (glob($mask) as $filename) {
            if (self::exists($filename)) {
                $res[] = $filename;
            }
        }

        return $res ?: null;

    }

}