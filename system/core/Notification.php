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
 * @version    2.4
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 2.0
 */

class Notification
{
	protected function __construct()
	{
		//--
	}


    /**
     * Display success notification
     *
     * @param string $msg message
     * @param array $arg additional parameters
     */
    public static function success(string $msg, array $arg = []): void
    {
		$Template = Template::getInstance();

		$array = array(
			'success' => true,
			'header' => $Template->_get('message_header_success'),
			'message' => $msg,
			'theme' => 'success'
		);

		if (!empty($arg)) {
            $array = array_merge($array, $arg);
        }

		Json::output($array, true);
	}


    /**
     * Display error notification
     *
     * @param string $msg message
     * @param array $arg additional parameters
     */

	public static function error(string $msg, array $arg = []): void
    {
		$Template = Template::getInstance();

		$array = array(
			'success' => false,
			'header' => $Template->_get('message_header_error'),
			'message' => $msg,
			'theme' => 'danger'
		);

		if (!empty($arg)) {
            $array = array_merge($array, $arg);
        }

		Json::output($array, true);
	}

}