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

class Dependencies
{
	public static $files = array();

	function __construct()
	{
		//
	}

	public static function add($file, $priority = 10, $params = '')
	{
		self::$files[] = array(
			'file' => (string)$file,
			'priority' => (int)$priority,
            'params' => (string)$params
		);
	}

	public static function get()
	{
		return Arrays::multiSort(self::$files, 'priority');
	}
}