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



	class ModuleCustomers extends Module
	{
		//-- Версия модуля
		public static $version = '1.0';

		//-- Дата
		public static $date = '15.05.2018';

		//-- Системное имя модуля
		public static $_moduleName = 'customers';


		public function __construct()
		{
			//-- Родитель
			parent::__construct();


			/*
			|--------------------------------------------------------------------------------------
			| Назначаем права
			|--------------------------------------------------------------------------------------
			|
			*/
			$_permissions = array('customers_view', 'customers_edit', 'customers_delete');
			Permission::add('customers', $_permissions, 'sli sli-users-business-male-shopping-bag', 30);


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
			| Подгружаем файл переводов (Права)
			|--------------------------------------------------------------------------------------
			|
			*/
			$Smarty->_load(CP_DIR . '/modules/' . self::$_moduleName . '/lang/' . Session::getvar('current_language') . '.ini', 'permissions');

			/*
			|--------------------------------------------------------------------------------------
			| Добавляем подменю навигации
			|--------------------------------------------------------------------------------------
			|
			*/
			Navigation::add(
				40,
				$Smarty->_get('customers_submenu_name'),
				'sli sli-users-business-male-shopping-bag',
				'customers',
				self::$_moduleName,
				Navigation::LEFT,
				Navigation::LEFT_MAIN,
				'',
				'',
				false
			);
		}
	}