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




	class ModuleSettings extends Module
	{
		//-- Версия модуля
		public static $version = '1.0';

		//-- Дата
		public static $date = '18.12.2017';

		//-- Системное имя модуля
		public static $_moduleName = 'settings';


		public function __construct()
		{
			//-- Родитель
			parent::__construct();


			/*
			|--------------------------------------------------------------------------------------
			| Инстанс Smarty
			|--------------------------------------------------------------------------------------
			|
			*/
			$Smarty = Tpl::getInstance();


			/*
			|--------------------------------------------------------------------------------------
			| Подгружаем файл переводов
			|--------------------------------------------------------------------------------------
			|
			*/
			$Smarty->_load(CP_DIR . '/modules/' . self::$_moduleName . '/lang/' . $_SESSION['current_language'] . '.ini', 'name');


			/*
			|--------------------------------------------------------------------------------------
			| Добавляем меню навигации
			|--------------------------------------------------------------------------------------
			|
			*/
			Navigation::add(
				30,
				$Smarty->_get('settings_menu_name'),
				'sli sli-settings-cog-double-2',
				'',
				'settings_menu',
				Navigation::LEFT,
				Navigation::LEFT_SETTINGS,
				'',
				'',
				false
			);


			/*
			|--------------------------------------------------------------------------------------
			| Добавляем подменю навигации
			|--------------------------------------------------------------------------------------
			|
			*/
			Navigation::add(
				40,
				$Smarty->_get('settings_submenu_name'),
				'sli sli-settings-cog',
				self::$_moduleName,
				self::$_moduleName,
				Navigation::LEFT,
				Navigation::LEFT_SETTINGS,
				'settings_menu',
				'',
				false
			);
		}
	}

