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


	
	class Controller404errors extends Controller
	{
		//-- Model
		public static $route_id;
		protected static $model;
	
	
		/*
		 |--------------------------------------------------------------------------------------
		 | Controller404errors конструктор
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
		 | Router: 404errors
		 |--------------------------------------------------------------------------------------
		 | По умолчанию
		 |
		 */
		public static function index()
		{
			//-- Инстанс Smarty
			$Smarty = Tpl::getInstance();
	
			//-- Подгружаем файл переводов
			$Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'main');
	
			//-- Информация
			$data = array (
				//-- ID навигации
				'page' => '404errors',
				//-- Title
				'page_title' => $Smarty->_get('404errors_page_title'),
				//-- Header
				'header' => $Smarty->_get('404errors_page_header'),
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
						'text'      => $Smarty->_get('404errors_breadcrumbs'),
						'href'      => '',
						'page'      => '',
						'push'      => '',
						'active'    => true
					)
				)
			);
	
			$Smarty
				->assign('data', $data)
				->assign('right_header', $Smarty->fetch('modules/404errors/view/right.tpl'))
				->assign('content', $Smarty->fetch('modules/404errors/view/index.tpl'));
		}
	}