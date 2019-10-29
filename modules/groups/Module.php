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



class ModuleGroups extends Module
{
	//-- Версия модуля
	public static $version = '1.0';

	//-- Дата
	public static $date = '18.12.2017';

	//-- Системное имя модуля
	public static $_moduleName = 'groups';


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
		$_permissions = array('groups_view', 'groups_edit', 'groups_delete');
		Permission::add('groups', $_permissions, 'sli sli-users-account-group-4', 60);


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
			20,
			$Smarty->_get('groups_menu_name'),
			'sli sli-users-account-group-4',
            'groups',
			self::$_moduleName,
			Navigation::LEFT,
			Navigation::LEFT_SETTINGS,
			'',
			'',
			false
		);
	}
}