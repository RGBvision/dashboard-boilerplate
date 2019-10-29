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
	ABS_PATH . 'modules/reports/js/reports.js',
	100
);

class ControllerReports extends Controller
{
	//-- Model
	public static $route_id;
	protected static $model;


	/*
	 |--------------------------------------------------------------------------------------
	 | Controller конструктор
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
	 | Router: reports
	 |--------------------------------------------------------------------------------------
	 | По умолчанию
	 |
	 */
	public static function index()
	{

	}

	public static function finances()
	{

		//-- Инстанс Smarty
		$Smarty = Tpl::getInstance();

		//-- Подгружаем файл переводов
		$Smarty->_load(CP_DIR . '/modules/reports/lang/' . $_SESSION['current_language'] . '.ini', 'main');
		$Smarty->_load(CP_DIR . '/modules/reports/lang/' . $_SESSION['current_language'] . '.ini', 'pages');

		//-- Информация
		$data = array(
			//-- ID навигации
			'page' => 'reports_finances',
			//-- Title
			'page_title' => $Smarty->_get('reports_finances_page_title'),
			//-- Header
			'header' => $Smarty->_get('reports_finances_page_header'),
			//-- Breadcrumbs
			'breadcrumbs' => array(
				array(
					'text' => $Smarty->_get('main_page'),
					'href' => './',
					'page' => 'reports_finances',
					'push' => 'true',
					'active' => false
				),
				array(
					'text' => $Smarty->_get('reports_finances_breadcrumb'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => true
				)
			)
		);

		//-- To Smarty
		$Smarty
			->assign('finances', self::$model->getFinances())
			->assign('data', $data)
			->assign('right_header', $Smarty->fetch('modules/reports/view/right_finances.tpl'))
			->assign('content', $Smarty->fetch('modules/reports/view/index_finances.tpl'));
	}

	public static function orders()
	{

		//-- Инстанс Smarty
		$Smarty = Tpl::getInstance();

		//-- Подгружаем файл переводов
		$Smarty->_load(CP_DIR . '/modules/reports/lang/' . $_SESSION['current_language'] . '.ini', 'main');
		$Smarty->_load(CP_DIR . '/modules/reports/lang/' . $_SESSION['current_language'] . '.ini', 'pages');

		//-- Информация
		$data = array(
			//-- ID навигации
			'page' => 'reports_orders',
			//-- Title
			'page_title' => $Smarty->_get('reports_orders_page_title'),
			//-- Header
			'header' => $Smarty->_get('reports_orders_page_header'),
			//-- Breadcrumbs
			'breadcrumbs' => array(
				array(
					'text' => $Smarty->_get('main_page'),
					'href' => './',
					'page' => 'reports_orders',
					'push' => 'true',
					'active' => false
				),
				array(
					'text' => $Smarty->_get('reports_orders_breadcrumb'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => true
				)
			)
		);

		//-- To Smarty
		$Smarty
			->assign('orders', self::$model->getOrders())
			->assign('data', $data)
			->assign('right_header', $Smarty->fetch('modules/reports/view/right_orders.tpl'))
			->assign('content', $Smarty->fetch('modules/reports/view/index_orders.tpl'));
	}

	public static function employees()
	{

		//-- Инстанс Smarty
		$Smarty = Tpl::getInstance();

		//-- Подгружаем файл переводов
		$Smarty->_load(CP_DIR . '/modules/reports/lang/' . $_SESSION['current_language'] . '.ini', 'main');
		$Smarty->_load(CP_DIR . '/modules/reports/lang/' . $_SESSION['current_language'] . '.ini', 'pages');

		//-- Информация
		$data = array(
			//-- ID навигации
			'page' => 'reports_orders',
			//-- Title
			'page_title' => $Smarty->_get('reports_employees_page_title'),
			//-- Header
			'header' => $Smarty->_get('reports_employees_page_header'),
			//-- Breadcrumbs
			'breadcrumbs' => array(
				array(
					'text' => $Smarty->_get('main_page'),
					'href' => './',
					'page' => 'reports_employees',
					'push' => 'true',
					'active' => false
				),
				array(
					'text' => $Smarty->_get('reports_employees_breadcrumb'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => true
				)
			)
		);

		//-- To Smarty
		$Smarty
			->assign('orders', self::$model->getOrders())
			->assign('data', $data)
			->assign('right_header', $Smarty->fetch('modules/reports/view/right_employees.tpl'))
			->assign('content', $Smarty->fetch('modules/reports/view/index_employees.tpl'));
	}

	public static function customers()
	{

		//-- Инстанс Smarty
		$Smarty = Tpl::getInstance();

		//-- Подгружаем файл переводов
		$Smarty->_load(CP_DIR . '/modules/reports/lang/' . $_SESSION['current_language'] . '.ini', 'main');
		$Smarty->_load(CP_DIR . '/modules/reports/lang/' . $_SESSION['current_language'] . '.ini', 'pages');

		//-- Информация
		$data = array(
			//-- ID навигации
			'page' => 'reports_customers',
			//-- Title
			'page_title' => $Smarty->_get('reports_customers_page_title'),
			//-- Header
			'header' => $Smarty->_get('reports_customers_page_header'),
			//-- Breadcrumbs
			'breadcrumbs' => array(
				array(
					'text' => $Smarty->_get('main_page'),
					'href' => './',
					'page' => 'reports_customers',
					'push' => 'true',
					'active' => false
				),
				array(
					'text' => $Smarty->_get('reports_customers_breadcrumb'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => true
				)
			)
		);

		//-- To Smarty
		$Smarty
			->assign('orders', self::$model->getOrders())
			->assign('data', $data)
			->assign('right_header', $Smarty->fetch('modules/reports/view/right_customers.tpl'))
			->assign('content', $Smarty->fetch('modules/reports/view/index_customers.tpl'));
	}

}