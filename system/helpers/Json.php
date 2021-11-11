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
 * @version    2.7
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 2.0
 */

class Json
{

	protected function __construct()
	{
		//---
	}

    /**
     * Convert array to JSON
     *
     * @param array $array
     * @param int $flags
     * @return string
     */
    public static function encode(array $array, int $flags = JSON_UNESCAPED_UNICODE): string
	{
		$json = json_encode($array, $flags); // JSON_UNESCAPED_UNICODE

		if ($json === false) {
            $json = json_encode(['jsonError', json_last_error_msg()]);
        }

		if ($json === false) {
            $json = '{"jsonError": "unknown"}';
        }

		return $json;
	}

    /**
     * Convert JSON to array or object
     *
     * @param string $string JSON string
     * @param bool $object to object flag
     * @return mixed
     */
    public static function decode(string $string, bool $object = false)
	{
		return json_decode($string, !$object);
	}

    /**
     * Output JSON
     *
     * @param array $array data to output
     * @param bool $shutdown shutdown after output
     */
    public static function show(array $array, bool $shutdown = false): void
    {
		$headers = array(
			'Pragma: no-cache',
			'Cache-Control: private, no-cache',
			'Content-Disposition: inline; filename="response.json"',
			'Vary: Accept',
			'Content-type: application/json; charset=utf-8',
            'Expires: ' . gmdate('r', time() + (OUTPUT_EXPIRE ? OUTPUT_EXPIRE_OFFSET : 0))
		);

		Response::setHeaders($headers);

		$json = self::encode($array, 0);

		echo $json;

		if ($shutdown) {
            Response::shutDown();
        }
	}
}