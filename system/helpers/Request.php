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
 * @version    2.6
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Request
{
    protected function __construct()
    {
        //--
    }

    /**
     * Redirect
     *
     * @param string $url destination URL
     * @param int $status HTTP status code
     * @param int|null $delay delay before redirect
     */
    public static function redirect(string $url, int $status = 302, ?int $delay = null): void
    {

        if (headers_sent()) {

            if ($delay !== null) {
                echo "<script>setTimeout(() => { document.location.href='" . $url . "'; }, " . $delay * 1000 . ");</script>\n";
            } else {
                echo "<script>document.location.href='" . $url . "';</script>\n";
            }
        } else {
            Response::setStatus($status);

            if ($delay !== null) {
                sleep($delay);
            }

            Response::setHeader('Location:' . $url, true, $status);

            Response::shutDown();
        }
    }

    /**
     * Get GET value
     *
     * @param string $key parameter key or path
     * @return array|mixed|null
     */
    public static function get(string $key)
    {
        return Arrays::get($_GET, $key);
    }

    /**
     * Get POST value
     *
     * @param string $key parameter key or path
     * @return array|mixed|null
     */
    public static function post(string $key)
    {
        return Arrays::get($_POST, $key);
    }

    /**
     * Get GET or POST value
     *
     * @param string $key parameter key or path
     * @return array|mixed|null
     */
    public static function request(string $key)
    {
        return Arrays::get($_REQUEST, $key);
    }

    /**
     * Get Referrer
     *
     * @return array|mixed|null
     */
    public static function referrer(): string
    {
        return Arrays::get($_SERVER, 'HTTP_REFERER') ?? ABS_PATH;
    }

    /**
     * Save uploaded file into temporary directory
     *
     * @param string $key file key or path
     * @param array|null $allowed_mime_types allowed file types
     * @param int|null $max_size max allowed file size
     * @return string|null saves file path
     */
    public static function file(string $key, ?array $allowed_mime_types = null, ?int $max_size = 100): ?string
    {

        try {

            $file_info = Arrays::get($_FILES, $key);

            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (!isset($file_info['error']) || is_array($file_info['error'])) {
                return null;
            }

            // Check $_FILES[$key]['error'] value.
            switch ($file_info['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                default:
                    return null;
            }

            // You should also check filesize here.
            if ($file_info['size'] > ($max_size * 1024 * 1024)) { // 100 MB
                return null;
            }

            // DO NOT TRUST $_FILES[$key]['mime'] VALUE!
            // Check MIME Type by yourself.
            if ($allowed_mime_types && !(in_array(File::mime($file_info['tmp_name'], false), $allowed_mime_types))) {
                return null;
            }

            // You should name it uniquely.
            // DO NOT USE $_FILES[$key]['name'] WITHOUT ANY VALIDATION!
            // Obtain safe unique name from its binary data.

            $tmp_name = DASHBOARD_DIR . TEMP_DIR . '/uploads/' . sha1_file($file_info['tmp_name']);

            if (!move_uploaded_file($file_info['tmp_name'], $tmp_name)) {
                return null;
            }

            return $tmp_name;

        } catch (RuntimeException $e) {
            return null;
        }
    }

    /**
     * Get request path
     *
     * @return string
     */
    public static function getPath(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Check if AJAX request
     *
     * @return bool
     */
    public static function isAjax(): bool
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'));
    }
}