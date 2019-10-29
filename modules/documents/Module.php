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



class ModuleDocuments extends Module
{
	//-- Версия модуля
	public static $version = '1.0';

	//-- Дата
	public static $date = '15.05.2018';

	//-- Системное имя модуля
	public static $_moduleName = 'documents';


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

		$_permissions = array('documents_view','documents_edit_view','documents_templates_view','documents_control');
		Permission::add('documents', $_permissions, 'sli sli-content-new-document-text', 50);

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
        $Smarty->_load(CP_DIR . '/modules/' . self::$_moduleName . '/lang/' . $_SESSION['current_language'] . '.ini', 'permissions');


		/*
		|--------------------------------------------------------------------------------------
		| Добавляем меню навигации
		|--------------------------------------------------------------------------------------
		|
		*/
		Navigation::add(
			30,
			$Smarty->_get('documents_menu_name'),
			'sli sli-content-new-document-text',
			'documents',
			self::$_moduleName,
			Navigation::LEFT,
			Navigation::LEFT_MAIN,
			'',
			'',
			false
		);

        Navigation::add(
            50,
            $Smarty->_get('documents_templates_menu_name'),
            'sli sli-content-new-document-layer',
            'documents/templates',
            'documents_templates',
            Navigation::LEFT,
            Navigation::LEFT_SETTINGS,
            '',
            '',
            false
        );

	}
}