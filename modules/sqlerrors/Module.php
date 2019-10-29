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


	
	
	class ModuleSqlerrors extends Module
	{
		//-- Версия модуля
		public static $version = '1.0';
	
		//-- Дата
		public static $date = '18.12.2017';
	
		//-- Системное имя модуля
		public static $_moduleName = 'sqlerrors';
	
	
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
				$Smarty->_get('sqlerrors_menu_name'),
				'sli sli-server-server-warning-1',
				self::$_moduleName,
				self::$_moduleName,
				Navigation::LEFT,
				Navigation::LEFT_CONTROL,
				'logs',
				'',
				false
			);
		}
	}