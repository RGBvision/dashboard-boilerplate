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

class Cookie
{

    /**
     * Set cookie value
     *
     * @param string $key key
     * @param string $value value
     * @param int $expire cookie lifetime
     * @param string $domain cookie domain
     * @param string $path path
     * @param bool $secure should only be transmitted over a secure HTTPS connection
     * @param bool $httpOnly should be accessible only through the HTTP protocol
     * @return bool
     */
    public static function set(string $key, string $value, int $expire = 86400, string $domain = '', string $path = '/', bool $secure = false, bool $httpOnly = false): bool
    {
		return setcookie($key, $value, $expire, $path, $domain, $secure, $httpOnly);
	}


    /**
     * Get cookie value
     *
     * @param string $key ключ
     * @return mixed
     */
    public static function get(string $key): mixed
    {
        return $_COOKIE[$key] ?? false;
    }


    /**
     * Delete cookie
     *
     * @param string $key ключ
     */
    public static function delete(string $key): void
    {
		unset($_COOKIE[$key]);
	}


    /**
     * Set cookies domain
     *
     * @param string $cookie_domain domain
     */
    public static function setDomain(string $cookie_domain = ''): void
    {
        if (empty($cookie_domain)) {
            if (defined('COOKIE_DOMAIN') && COOKIE_DOMAIN) {
                $cookie_domain = COOKIE_DOMAIN;
            } elseif ($_SERVER['HTTP_HOST']) {
                $cookie_domain = htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES);
            }
        }

		// Cleanup domain name
		$cookie_domain = ltrim(parse_url($cookie_domain, PHP_URL_HOST) ?? '', '.');

		if (str_starts_with($cookie_domain, 'www.')) {
            $cookie_domain = substr($cookie_domain, 4);
        }

		// According to RFC 2109, the domain for cookies must be level 2 or higher.
		// Therefore, you cannot set a cookie_domain for 'localhost' or IP address.
		if (!IP::isValid($cookie_domain) && count(explode('.', $cookie_domain)) > 2) {
            ini_set('session.cookie_domain', $cookie_domain);
        }

		Core::$cookie_domain = $cookie_domain;

		ini_set('session.cookie_path', ABS_PATH);
	}
}