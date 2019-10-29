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



class ModuleOrders extends Module
{
	public static $version = '1.0';

	public static $date = '18.12.2017';

	public static $_moduleName = 'orders';


	public function __construct()
	{
		parent::__construct();

		/*
		|--------------------------------------------------------------------------------------
		| Назначаем права
		|--------------------------------------------------------------------------------------
		|
		*/
		$_permissions = array('orders_view','orders_edit_view','orders_control','orders_add','orders_payment');
		Permission::add('orders', $_permissions, 'sli sli-content-new-notepad-checklist', 40);

		//-- Get Smarty Instance
		$Smarty = Tpl::getInstance();

		//-- Get Lang file
		$Smarty->_load(CP_DIR . '/modules/' . self::$_moduleName . '/lang/' . Session::getvar('current_language') . '.ini', 'name');

		/*
		|--------------------------------------------------------------------------------------
		| Подгружаем файл переводов (Права)
		|--------------------------------------------------------------------------------------
		|
		*/
		$Smarty->_load(CP_DIR . '/modules/' . self::$_moduleName . '/lang/' . $_SESSION['current_language'] . '.ini', 'permissions');

		//-- Add Niavigation menu
		Navigation::add(
			20,
			$Smarty->_get('orders_menu_name'),
			'sli sli-content-new-notepad-checklist',
			'orders',
			self::$_moduleName,
			Navigation::LEFT,
			Navigation::LEFT_MAIN,
			'',
			'',
			false
		);
	}
}