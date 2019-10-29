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



class ModuleDatabase extends Module
{
	//-- Версия модуля
	public static $version = '1.0';

	//-- Дата
	public static $date = '18.12.2017';

	//-- Системное имя модуля
	public static $_moduleName = 'database';


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
		$_permissions = array('database_view', 'database_edit');
		Permission::add('database', $_permissions, 'sli sli-server-server-3', 80);

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
		| Добавляем меню навигации
		|--------------------------------------------------------------------------------------
		|
		*/
		Navigation::add(
			100,
			$Smarty->_get('database_menu_name'),
			'sli sli-server-server-3',
			'',
			'database_menu',
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
			10,
			$Smarty->_get('database_submenu_name'),
			'sli sli-server-server-flash',
			'database',
			self::$_moduleName,
			Navigation::LEFT,
			Navigation::LEFT_SETTINGS,
			'database_menu',
			'',
			false
		);
	}
}