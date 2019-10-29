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



class ModuleReports extends Module
{
	//-- Версия модуля
	public static $version = '1.0';

	//-- Дата
	public static $date = '06.01.2018';

	//-- Системное имя модуля
	public static $_moduleName = 'reports';


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
		$_permissions = array('reports_view', 'reports_finances_view', 'reports_orders_view', 'reports_employees_view', 'reports_customers_view');
		Permission::add('reports', $_permissions, 'sli sli-content-clipboard-2', 20);


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
		$Smarty->_load(CP_DIR . '/modules/reports/lang/' . $_SESSION['current_language'] . '.ini', 'name');


		/*
		|--------------------------------------------------------------------------------------
		| Подгружаем файл переводов (Права)
		|--------------------------------------------------------------------------------------
		|
		*/
		$Smarty->_load(CP_DIR . '/modules/reports/lang/' . $_SESSION['current_language'] . '.ini', 'permissions');


		/*
		|--------------------------------------------------------------------------------------
		| Добавляем меню навигации
		|--------------------------------------------------------------------------------------
		|
		*/
		Navigation::add(
			30,
			$Smarty->_get('reports_menu_name'),
			'sli sli-content-clipboard-2',
			'',
			'reports_menu',
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
			$Smarty->_get('reports_finances_submenu_name'),
			'sli sli-money-coin-bank-note',
			'reports/finances',
			'reports_finances',
			Navigation::LEFT,
			Navigation::LEFT_CONTROL,
			'reports_menu',
			'',
			false
		);
		Navigation::add(
			20,
			$Smarty->_get('reports_orders_submenu_name'),
			'sli sli-basic-file-check-2',
			'reports/orders',
			'reports_orders',
			Navigation::LEFT,
			Navigation::LEFT_CONTROL,
			'reports_menu',
			'',
			false
		);
		Navigation::add(
			30,
			$Smarty->_get('reports_employees_submenu_name'),
			'sli sli-users-account-group-5',
			'reports/employees',
			'reports_employees',
			Navigation::LEFT,
			Navigation::LEFT_CONTROL,
			'reports_menu',
			'',
			false
		);
		Navigation::add(
			40,
			$Smarty->_get('reports_customers_submenu_name'),
			'sli sli-users-business-male-shopping-bag',
			'reports/customers',
			'reports_customers',
			Navigation::LEFT,
			Navigation::LEFT_CONTROL,
			'reports_menu',
			'',
			false
		);
	}
}