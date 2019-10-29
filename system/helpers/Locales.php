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

class Locales
{

	public static function set(): void
    {
		$acp_language = Session::checkvar('current_language')
			? Session::getvar('current_language')
			: 'en';

		$locale = strtolower(defined('CP_ACCESS')
			? $acp_language
			: Session::getvar('current_language'));

		switch ($locale) {
			case 'ru':
				@setlocale(LC_ALL, 'ru_RU.UTF-8', 'rus_RUS.UTF-8', 'russian');
				@setlocale(LC_NUMERIC, 'C');
				break;

			default:
				@setlocale(LC_ALL, 'en_US.UTF-8', 'en_US.UTF-8', 'english');
                @setlocale(LC_NUMERIC, 'C');
				break;
		}
	}
}