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




	class ModuleStore extends Module
	{
		//-- Версия модуля
		public static $version = '1.0';

		//-- Дата
		public static $date = '15.05.2018';

		//-- Системное имя модуля
		public static $_moduleName = 'store';


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
			$_permissions = array('store_view', 'store_edit_view', 'store_goods_view', 'store_consumables_view', 'store_edit', 'store_delete');
			Permission::add('store', $_permissions, 'sli sli-shipping-delivery-box-modular-belt', 30);


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
                50,
                $Smarty->_get('store_edit_submenu_name'),
                'sli sli-shipping-delivery-box-storehouse',
                '',
                'store_edit',
                Navigation::LEFT,
                Navigation::LEFT_MAIN,
                '',
                '',
                false
            );
/*
            Navigation::add(
                10,
                $Smarty->_get('store_edit_goods_submenu_name'),
                'sli sli-shipping-delivery-box-1',
                'index.php?route=store',
                'store_goods',
                Navigation::LEFT,
                Navigation::LEFT_MAIN,
                'store_edit',
                '',
                false
            );
*/
            Navigation::add(
                20,
                $Smarty->_get('store_edit_consumables_submenu_name'),
                'sli sli-transportation-oil-jerry-can',
                'store/consumables',
                'store_consumables',
                Navigation::LEFT,
                Navigation::LEFT_MAIN,
                'store_edit',
                '',
                false
            );
/*
            Navigation::add(
                20,
                $Smarty->_get('store_edit_storehouse_submenu_name'),
                'sli sli-shipping-delivery-box-modular-belt',
                'index.php?route=store/storehouse',
                'store_storehouse',
                Navigation::LEFT,
                Navigation::LEFT_MAIN,
                'store_edit',
                '',
                false
            );
*/
		}
	}