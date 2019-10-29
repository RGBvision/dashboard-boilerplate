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

class Request
{
	protected function __construct()
	{
		//--
	}

	public static function redirect($url, $status = 302, $delay = null): void
    {
		$url = (string)$url;
		$status = (int)$status;

		if (headers_sent()) {
			echo "<script>document.location.href='" . $url . "';</script>\n";
		} else {
			Response::setStatus($status);

			if ($delay !== null)
				sleep((int)$delay);

			self::setHeader('Location:' . $url, true, $status);

			self::shutDown();
		}
	}

	public static function setHeader($header, $replace = false, $status = null): void
    {
		header((string)$header, $replace, $status);
	}


	public static function setHeaders($headers): void
    {
		foreach ((array)$headers as $header) {
			if (!empty($header)) {
                header((string)$header);
            }
		}
	}

	public static function get($key)
	{
		return Arrays::get($_GET, $key);
	}

	public static function post($key)
	{
		return Arrays::get($_POST, $key);
	}

	public static function request($key)
	{
		return Arrays::get($_REQUEST, $key);
	}

	public static function getPath(): string
    {
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	}

	public static function isAjax(): bool
    {
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'));
	}

	public static function shutDown(): void
    {
		exit(0);
	}
}