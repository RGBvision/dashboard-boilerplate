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

class IP
{
	public static $ip;
	public static $charset;

	public function __construct($options = null)
	{

		if (!isset($options['ip']) || !self::isValid($options['ip'])) {
            self::$ip = self::getIp();
        } elseif (self::isValid($options['ip'])) {
            self::$ip = $options['ip'];
        }

		if (isset($options['charset']) && $options['charset'] && $options['charset'] !== 'windows-1251') {
            self::$charset = $options['charset'];
        }
	}

	//--- Get ip address from $_SERVER by priority
	public static function getIp(): string
    {
		$ip = '';
		$ip_array = array();

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

	//--- Validate IP address
	public static function isValid($ip = null): bool
    {
        return ($ip === '::1') || preg_match("#^([\d]{1,3})\.([\d]{1,3})\.([\d]{1,3})\.([\d]{1,3})$#", $ip);
    }
}