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

class Cookie
{
	protected function __construct()
	{
		//---
	}

	public static function set($key, $value, $expire = 86400, $domain = '', $path = '/', $secure = false, $httpOnly = false): bool
    {
		$key = (string)$key;
		$path = (string)$path;
		$domain = (string)$domain;
		$secure = (bool)$secure;
		$httpOnly = (bool)$httpOnly;

		return setcookie($key, $value, $expire, $path, $domain, $secure, $httpOnly);
	}


	public static function get($key)
	{
		$key = (string)$key;

		if (!isset($_COOKIE[$key])) {
            return false;
        }

		$value = (get_magic_quotes_gpc())
			? stripslashes($_COOKIE[$key])
			: $_COOKIE[$key];

		return $value;
	}


	public static function delete($key): void
    {
		unset($_COOKIE[$key]);
	}


	public static function setDomain($cookie_domain = ''): void
    {
        if (empty($cookie_domain)) {
            if (defined('COOKIE_DOMAIN') && COOKIE_DOMAIN !== '') {
                $cookie_domain = COOKIE_DOMAIN;
            } elseif (!empty($_SERVER['HTTP_HOST'])) {
                $cookie_domain = htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES);
            }
        }

		//--- Delete leading www and port number
		$cookie_domain = ltrim($cookie_domain, '.');

		if (strpos($cookie_domain, 'www.') === 0) {
            $cookie_domain = substr($cookie_domain, 4);
        }

		$cookie_domain = '.' . explode(':', $cookie_domain)[0];

		// According to RFC 2109, domain name for a cookie must be a second or more level.
		// So, don't set cookie_domain for 'localhost' or IP address.
		if (!IP::isValid($cookie_domain) && count(explode('.', $cookie_domain)) > 2) {
            ini_set('session.cookie_domain', $cookie_domain);
        }

		Core::$cookie_domain = $cookie_domain;

		ini_set('session.cookie_path', ABS_PATH);
	}
}