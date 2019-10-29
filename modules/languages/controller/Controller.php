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
		ABS_PATH . 'modules/languages/js/languages.js',
		100
	);

	class ControllerLanguages extends Controller
	{
		//-- Model
		public static $model;


		/*
		 |--------------------------------------------------------------------------------------
		 | ControllerLanguages конструктор
		 |--------------------------------------------------------------------------------------
		 | Сразу назначаем Model из Router
		 |
		 */
		public function __construct()
		{
			self::$model = Router::model();
		}


		/*
		 |--------------------------------------------------------------------------------------
		 | Router: languages
		 |--------------------------------------------------------------------------------------
		 | По умолчанию
		 |
		 */
		public static function index()
		{
			//-- Get Smarty Instance
			$Smarty = Tpl::getInstance();

			//-- Get Lang file
			$Smarty->_load(CP_DIR . '/modules/languages/lang/' . $_SESSION['current_language'] . '.ini', 'main');

			//-- Data page
			$data = array (
				//-- Navigation
				'page' => 'languages',
				//-- Title
				'page_title' => $Smarty->_get('languages_page_title'),
				//-- Header
				'header' => $Smarty->_get('languages_page_header'),
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
						'text'      => $Smarty->_get('settings_menu_name'),
						'href'      => './index.php?route=settings',
						'page'      => 'settings',
						'push'      => 'true',
						'active'    => true
					),
					array(
						'text'      => $Smarty->_get('languages_breadcrumb'),
						'href'      => '',
						'page'      => '',
						'push'      => '',
						'active'    => true
					)
				)
			);

			//-- To Smarty
			$Smarty
				->assign('configs', CP_CONFIG_DEFAULTS)
				->assign('data', $data)
				->assign('right_header', $Smarty->fetch('modules/languages/view/right.tpl'))
				->assign('content', $Smarty->fetch('modules/languages/view/index.tpl'));
		}


		/*
		 |--------------------------------------------------------------------------------------
		 | Router: languages/save
		 |--------------------------------------------------------------------------------------
		 |
		 |
		 */
		public static function save()
		{
			Debug::_echo($_REQUEST, true);
		}
	}