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



	Dependencies::add(
		ABS_PATH . 'modules/constants/js/constants.js',
		100
	);

	class ControllerConstants extends Controller
	{
		//-- Model
		public static $route_id;
		protected static $model;


		/*
		 |--------------------------------------------------------------------------------------
		 | ControllerConstants конструктор
		 |--------------------------------------------------------------------------------------
		 | Сразу назначаем Model из Router
		 |
		 */
		public function __construct()
		{
			self::$route_id = Router::getId();
			self::$model = Router::model();
		}


		/*
		 |--------------------------------------------------------------------------------------
		 | Router: constants
		 |--------------------------------------------------------------------------------------
		 | По умолчанию
		 |
		 */
		public static function index()
		{
			//-- Get Smarty Instance
			$Smarty = Tpl::getInstance();

			//-- Get Lang file
			$Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . $_SESSION['current_language'] . '.ini', 'pages');

			//-- Data page
			$data = array (
				//-- Navigation
				'page' => 'constants',
				//-- Title
				'page_title' => $Smarty->_get('constants_page_title'),
				//-- Header
				'header' => $Smarty->_get('constants_page_header'),
				//-- Breadcrumbs
				'breadcrumbs' => array (
					array (
						'text'      => $Smarty->_get('main_page'),
						'href'      => './',
						'page'      => 'dashboard',
						'push'      => 'true',
						'active'    => true
					),
					array(
						'text'      => $Smarty->_get('settings_menu_name'),
						'href'      => './index.php?route=settings',
						'page'      => 'settings',
						'push'      => 'true',
						'active'    => true
					),
					array(
						'text'      => $Smarty->_get('constants_breadcrumb'),
						'href'      => '',
						'page'      => '',
						'push'      => '',
						'active'    => false
					)
				)
			);

			$permission = Permission::perm('constants_edit');

			//-- To Smarty
			$Smarty
				->assign('configs', self::$model->getConstants())
				->assign('permission', $permission)
				->assign('data', $data)
				->assign('right_header', $Smarty->fetch('modules/constants/view/right.tpl'))
				->assign('content', $Smarty->fetch('modules/constants/view/index.tpl'));
		}


		/*
		 |--------------------------------------------------------------------------------------
		 | Router: constants/save
		 |--------------------------------------------------------------------------------------
		 |
		 |
		 */
		public static function save()
		{
			$Smarty = Tpl::getInstance();

			$Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

			self::$model->saveConstants();
		}
	}