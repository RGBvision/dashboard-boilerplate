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

class Notification
{
	protected function __construct()
	{
		//--
	}

	public static function success(string $msg, array $arg = array()): void
    {
		$Smarty = Tpl::getInstance();

		$array = array(
			'success' => true,
			'header' => $Smarty->_get('message_header_success'),
			'message' => $msg,
			'theme' => 'success'
		);

		if (!empty($arg)) {
            array_merge($array, $arg);
        }

		Json::show($array, true);
	}

	public static function error(string $msg, array $arg = array()): void
    {
		$Smarty = Tpl::getInstance();

		$array = array(
			'success' => false,
			'header' => $Smarty->_get('message_header_error'),
			'message' => $msg,
			'theme' => 'danger'
		);

		if (!empty($arg)) {
            array_merge($array, $arg);
        }

		Json::show($array, true);
	}

}