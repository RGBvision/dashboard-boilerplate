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
	ABS_PATH . 'assets/lib/chartJs/Chart.min.css',
	100
);
Dependencies::add(
	ABS_PATH . 'assets/lib/chartJs/Chart.min.js',
	110
);
Dependencies::add(
	ABS_PATH . 'modules/analytics/js/analytics.js',
	120
);

class ControllerAnalytics extends Controller
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
	 | Router: analytics
	 |--------------------------------------------------------------------------------------
	 | По умолчанию
	 |
	 */
	public static function index()
	{

	}

	/*
	 |--------------------------------------------------------------------------------------
	 | Router: analytics/finances
	 |--------------------------------------------------------------------------------------
	 | Финансы
	 |
	 */
	public static function finances()
	{

		//-- Инстанс Smarty
		$Smarty = Tpl::getInstance();

		//-- Подгружаем файл переводов
		$Smarty->_load(CP_DIR . '/modules/analytics/lang/' . $_SESSION['current_language'] . '.ini', 'main');
		$Smarty->_load(CP_DIR . '/modules/analytics/lang/' . $_SESSION['current_language'] . '.ini', 'pages');

		//-- Информация
		$data = array(
			//-- ID навигации
			'page' => 'analytics_finances',
			//-- Title
			'page_title' => $Smarty->_get('analytics_finances_page_title'),
			//-- Header
			'header' => $Smarty->_get('analytics_finances_page_header'),
			//-- Breadcrumbs
			'breadcrumbs' => array(
				array(
					'text' => $Smarty->_get('main_page'),
					'href' => './',
					'page' => 'analytics_finances',
					'push' => 'true',
					'active' => false
				),
				array(
					'text' => $Smarty->_get('analytics_finances_breadcrumb'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => true
				)
			)
		);

		//-- To Smarty
		$Smarty
			->assign('access', Permission::perm('analytics_abs_values'))
			->assign('finances_settings_data', self::$model->analyticsSettings())
			->assign('finances_settings_tpl', $Smarty->fetch('modules/analytics/view/finances_settings.tpl'))
			->assign('interval_income', self::$model->getIntervalFinances())
			->assign('annual_income', self::$model->getAnnualFinances())
			->assign('daily_average', self::$model->getDailyAverageFinances())
			->assign('hourly_average', self::$model->getHourlyAverageFinances())
			->assign('data', $data)
			->assign('right_header', $Smarty->fetch('modules/analytics/view/right_finances.tpl'))
			->assign('content', $Smarty->fetch('modules/analytics/view/index_finances.tpl'));
	}

	/*
	 |--------------------------------------------------------------------------------------
	 | Router: analytics/orders
	 |--------------------------------------------------------------------------------------
	 | Заказы
	 |
	 */
	public static function orders()
	{

		//-- Инстанс Smarty
		$Smarty = Tpl::getInstance();

		//-- Подгружаем файл переводов
		$Smarty->_load(CP_DIR . '/modules/analytics/lang/' . $_SESSION['current_language'] . '.ini', 'main');
		$Smarty->_load(CP_DIR . '/modules/analytics/lang/' . $_SESSION['current_language'] . '.ini', 'pages');

		//-- Информация
		$data = array(
			//-- ID навигации
			'page' => 'analytics_orders',
			//-- Title
			'page_title' => $Smarty->_get('analytics_orders_page_title'),
			//-- Header
			'header' => $Smarty->_get('analytics_orders_page_header'),
			//-- Breadcrumbs
			'breadcrumbs' => array(
				array(
					'text' => $Smarty->_get('main_page'),
					'href' => './',
					'page' => 'analytics_orders',
					'push' => 'true',
					'active' => false
				),
				array(
					'text' => $Smarty->_get('analytics_orders_breadcrumb'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => true
				)
			)
		);

		//-- To Smarty
		$Smarty
			->assign('access', Permission::perm('analytics_abs_values'))
			->assign('orders_settings_data', self::$model->analyticsSettings())
			->assign('orders_settings_tpl', $Smarty->fetch('modules/analytics/view/orders_settings.tpl'))
            ->assign('interval_income', self::$model->getIntervalOrders())
			->assign('annual_income', self::$model->getAnnualOrders())
			->assign('daily_average', self::$model->getDailyAverageOrders())
			->assign('hourly_average', self::$model->getHourlyAverageOrders())
			->assign('data', $data)
			->assign('right_header', $Smarty->fetch('modules/analytics/view/right_orders.tpl'))
			->assign('content', $Smarty->fetch('modules/analytics/view/index_orders.tpl'));
	}

	/*
	 |--------------------------------------------------------------------------------------
	 | Router: analytics/employees
	 |--------------------------------------------------------------------------------------
	 | Сотрудники
	 |
	 */
	public static function employees()
	{

		//-- Инстанс Smarty
		$Smarty = Tpl::getInstance();

		//-- Подгружаем файл переводов
		$Smarty->_load(CP_DIR . '/modules/analytics/lang/' . $_SESSION['current_language'] . '.ini', 'main');
		$Smarty->_load(CP_DIR . '/modules/analytics/lang/' . $_SESSION['current_language'] . '.ini', 'pages');

		//-- Информация
		$data = array(
			//-- ID навигации
			'page' => 'analytics_employees',
			//-- Title
			'page_title' => $Smarty->_get('analytics_employees_page_title'),
			//-- Header
			'header' => $Smarty->_get('analytics_employees_page_header'),
			//-- Breadcrumbs
			'breadcrumbs' => array(
				array(
					'text' => $Smarty->_get('main_page'),
					'href' => './',
					'page' => 'analytics_employees',
					'push' => 'true',
					'active' => false
				),
				array(
					'text' => $Smarty->_get('analytics_employees_breadcrumb'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => true
				)
			)
		);

		//-- To Smarty
		$Smarty
			->assign('access', Permission::perm('analytics_abs_values'))
			->assign('orders_settings_data', self::$model->analyticsSettings())
            ->assign('interval_data', self::$model->getIntervalEmployees())
			->assign('data', $data)
			->assign('right_header', $Smarty->fetch('modules/analytics/view/right_employees.tpl'))
			->assign('content', $Smarty->fetch('modules/analytics/view/index_employees.tpl'));
	}

	public static function test()
	{
		Router::model()->test();
	}
}