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

class IP
{

    /**
     * Get user IP address
     *
     * @return string
     */
    public static function getIp(): string
    {
		$ip = '';
		$ip_array = [];

		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_array[] = trim(strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ','));
        }

		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_array[] = $_SERVER['HTTP_CLIENT_IP'];
        }

		if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip_array[] = $_SERVER['REMOTE_ADDR'];
        }

		if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip_array[] = $_SERVER['HTTP_X_REAL_IP'];
        }

		foreach ($ip_array as $ip_item) {
			if (self::isValid($ip_item)) {
				$ip = (string)$ip_item;
				break;
			}
		}

		return $ip;
	}


    /**
     * Check if valid IP
     *
     * @param mixed $ip
     * @return bool
     */
    public static function isValid($ip = null): bool
    {
        return (bool)filter_var($ip, FILTER_VALIDATE_IP);
    }
}