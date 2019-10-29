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




	class ModuleServices extends Module
	{
		//-- Версия модуля
		public static $version = '1.0';

		//-- Дата
		public static $date = '15.05.2018';

		//-- Системное имя модуля
		public static $_moduleName = 'services';


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
			$_permissions = array('services_view', 'services_edit', 'services_delete');
			Permission::add('services', $_permissions, 'sli sli-shipping-delivery-box-handle-1', 30);


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
                30,
                $Smarty->_get('services_submenu_name'),
                'sli sli-shipping-delivery-box-handle-1',
                'services',
                'services',
                Navigation::LEFT,
                Navigation::LEFT_SETTINGS,
                '',
                '',
                false
            );
		}
	}