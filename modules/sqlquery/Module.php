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


	
	
	class ModuleSqlquery extends Module
	{
		//-- Версия модуля
		public static $version = '1.0';
	
		//-- Дата
		public static $date = '18.12.2017';
	
		//-- Системное имя модуля
		public static $_moduleName = 'sqlquery';
	
	
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
			$Smarty->_load(CP_DIR . '/modules/' . self::$_moduleName . '/lang/' . Session::getvar('current_language') . '.ini', 'name');
	
	
			/*
			|--------------------------------------------------------------------------------------
			| Добавляем подменю навигации
			|--------------------------------------------------------------------------------------
			|
			*/
			Navigation::add(
				20,
				$Smarty->_get('sqlquery_menu_name'),
				'sli sli-server-server-network-computer-1',
				self::$_moduleName,
				self::$_moduleName,
				Navigation::LEFT,
				Navigation::LEFT_SETTINGS,
				'database_menu',
				'',
				false
			);
		}
	}