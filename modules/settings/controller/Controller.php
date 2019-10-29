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




	class ControllerSettings extends Controller
	{
		//-- Model
		public static $route_id;
		protected static $model;


		/*
		 |--------------------------------------------------------------------------------------
		 | ControllerSettings конструктор
		 |--------------------------------------------------------------------------------------
		 |
		 */
		public function __construct()
		{
			self::$route_id = Router::getId();
			self::$model = Router::model();
		}


		/*
		 |--------------------------------------------------------------------------------------
		 | Router: settings
		 |--------------------------------------------------------------------------------------
		 | По умолчанию
		 |
		 */
		public static function index()
		{

			Dependencies::add(
				ABS_PATH . 'modules/settings/js/settings.js',
				100
			);

			//-- Get Smarty Instance
			$Smarty = Tpl::getInstance();

			//-- Get Lang file
			$Smarty->_load(CP_DIR . '/modules/settings/lang/' . $_SESSION['current_language'] . '.ini', 'pages');

			//-- Data page
			$data = array (
				//-- Navigation
				'page' => 'settings',
				//-- Title
				'page_title' => $Smarty->_get('settings_page_title'),
				//-- Header
				'header' => $Smarty->_get('settings_page_header'),
				//-- Breadcrumbs
				'breadcrumbs' => array (
					array (
						'text'      => $Smarty->_get('main_page'),
						'href'      => './',
						'page'      => 'dashboard',
						'push'      => 'true',
						'active'    => false
					),
					array(
						'text'      => $Smarty->_get('settings_breadcrumb'),
						'href'      => '',
						'page'      => '',
						'push'      => '',
						'active'    => true
					)
				)
			);

			$date_formats = array(
				'%d.%m.%Y',
				'%d %B %Y',
				'%A, %d.%m.%Y',
				'%A, %d %B %Y'
			);

			$time_formats = array(
				'%d.%m.%Y, %H:%M',
				'%d %B %Y, %H:%M',
				'%A, %d.%m.%Y (%H:%M)',
				'%A, %d %B %Y (%H:%M)'
			);

			$permission = Permission::perm('settings_edit');

			//-- To Smarty
			$Smarty
				->assign('data', $data)
				->assign('date_formats', $date_formats)
				->assign('time_formats', $time_formats)
				->assign('settings', Settings::get())
				->assign('permission', $permission)
				->assign('right_header', $Smarty->fetch('modules/settings/view/right.tpl'))
				->assign('content', $Smarty->fetch('modules/settings/view/index.tpl'));
		}


		/*
		 |--------------------------------------------------------------------------------------
		 | Router: settings/save
		 |--------------------------------------------------------------------------------------
		 | Запуск тестирования системы
		 |
		 */
		public static function save()
		{
			self::$model->saveSettings();
		}
	}