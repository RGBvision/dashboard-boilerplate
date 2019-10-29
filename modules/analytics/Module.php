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



class ModuleAnalytics extends Module
{
	//-- Версия модуля
	public static $version = '1.0';

	//-- Дата
	public static $date = '18.12.2017';

	//-- Системное имя модуля
	public static $_moduleName = 'analytics';


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
		$_permissions = array('analytics_view', 'analytics_finances_view', 'analytics_orders_view', 'analytics_employees_view', 'analytics_abs_values');
		Permission::add('analytics', $_permissions, 'sli sli-business-graph-pie-2', 20);


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
		$Smarty->_load(CP_DIR . '/modules/analytics/lang/' . $_SESSION['current_language'] . '.ini', 'name');


		/*
		|--------------------------------------------------------------------------------------
		| Подгружаем файл переводов (Права)
		|--------------------------------------------------------------------------------------
		|
		*/
		$Smarty->_load(CP_DIR . '/modules/analytics/lang/' . $_SESSION['current_language'] . '.ini', 'permissions');


		/*
		|--------------------------------------------------------------------------------------
		| Добавляем меню навигации
		|--------------------------------------------------------------------------------------
		|
		*/
		Navigation::add(
			20,
			$Smarty->_get('analytics_menu_name'),
			'sli sli-business-graph-pie-2',
			'',
			'analytics_menu',
			Navigation::LEFT,
			Navigation::LEFT_CONTROL,
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
			10,
			$Smarty->_get('analytics_finances_submenu_name'),
			'sli sli-money-coin-bank-note',
			'analytics/finances',
			'analytics_finances',
			Navigation::LEFT,
			Navigation::LEFT_CONTROL,
			'analytics_menu',
			'',
			false
		);

		Navigation::add(
			20,
			$Smarty->_get('analytics_orders_submenu_name'),
			'sli sli-basic-file-check-2',
			'analytics/orders',
			'analytics_orders',
			Navigation::LEFT,
			Navigation::LEFT_CONTROL,
			'analytics_menu',
			'',
			false
		);

		Navigation::add(
			30,
			$Smarty->_get('analytics_employees_submenu_name'),
			'sli sli-users-account-group-5',
			'analytics/employees',
			'analytics_employees',
			Navigation::LEFT,
			Navigation::LEFT_CONTROL,
			'analytics_menu',
			'',
			false
		);
	}
}