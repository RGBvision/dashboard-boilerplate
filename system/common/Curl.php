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
 * @since      File available since Release 2.2
 */

class Curl
{

    /**
     * Perform GET request
     *
     * @param string $url request URL
     * @param array $headers request headers
     * @param array $params request parameters
     * @return string
     */
    public static function get(string $url, array $headers = [], array $params = []): string
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . ($params ? '?' . http_build_query($params) : ''));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response ?: '';
    }

    /**
     * Perform POST request
     *
     * @param string $url request URL
     * @param array $headers request headers
     * @param string $params request parameters
     * @return string
     */
    private static function _post(string $url, array $headers = [], string $params = ''): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response ?: '';
    }

    /**
     * Perform POST request
     *
     * @param string $url request URL
     * @param array $headers request headers
     * @param array $params request parameters
     * @return string
     */
    public static function post(string $url, array $headers = [], array $params = []): string
    {
        return self::_post($url, $headers, http_build_query($params));
    }

    /**
     * Perform GET request
     *
     * @param string $url request URL
     * @param array $headers request headers
     * @param array $params request parameters
     * @return mixed
     */
    public static function getJson(string $url, array $headers = [], array $params = []): mixed
    {
        return Json::decode(self::get($url, [...$headers, 'Content-Type: application/json'], $params));
    }

    /**
     * Perform POST request
     *
     * @param string $url request URL
     * @param array $headers request headers
     * @param array $params request parameters
     * @return mixed
     */
    public static function postJson(string $url, array $headers = [], array $params = []): mixed
    {
        return Json::decode(self::_post($url, [...$headers, 'Content-Type: application/json'], Json::encode($params)));
    }

}
