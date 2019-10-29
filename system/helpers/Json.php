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

class Json
{

	protected function __construct()
	{
		// Nothing here
	}

	public static function encode(array $array)
	{
		$json = json_encode($array);

		if ($json === false) {
            $json = json_encode(array('jsonError', json_last_error_msg()));
        }

		if ($json === false) {
            $json = '{"jsonError": "unknown"}';
        }

		return $json;
	}

	public static function decode($array, $object = false)
	{
		return json_decode($array, $object);
	}

	public static function show(array $array, $shutdown = false): void
    {
		$headers = array(
			'Pragma: no-cache',
			'Cache-Control: private, no-cache',
			'Content-Disposition: inline; filename="files.json"',
			'Vary: Accept',
			'Content-type: application/json; charset=utf-8'
		);

		Request::setHeaders($headers);

		$json = self::encode($array);

		echo $json;

		if ($shutdown) {
            Request::shutDown();
        }
	}
}