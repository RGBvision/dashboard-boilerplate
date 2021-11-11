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
 * @version    1.1
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Locales
{

    /**
     * Set locale params
     */
    public static function set(string $locale): void
    {
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