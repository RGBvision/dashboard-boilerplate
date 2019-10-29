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



$files = array(
    ABS_PATH . 'assets/momentJs/moment.min.js',
    ABS_PATH . 'assets/chartJs/Chart.bundle.min.js',
    ABS_PATH . 'assets/fullcalendar/fullcalendar.min.css',
    ABS_PATH . 'assets/fullcalendar/fullcalendar.min.js',
    ABS_PATH . 'assets/fullcalendar/locale-all.js',
	ABS_PATH . 'modules/dashboard/js/dashboard.js',
);

foreach ($files as $i => $file) {
    Dependencies::add(
        $file,
        $i + 100
    );
}

class ControllerDashboard extends Controller
{
	//-- Model
	public static $route_id;
	protected static $model;


	/*
	 |--------------------------------------------------------------------------------------
	 | ControllerBenchmark конструктор
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
	 | Router: dashboard
	 |--------------------------------------------------------------------------------------
	 | По умолчанию
	 |
	 */
	public static function index()
	{
		//-- Инстанс Smarty
		$Smarty = Tpl::getInstance();

		//-- Подгружаем файл переводов
		$Smarty->_load(CP_DIR . '/modules/dashboard/lang/' . $_SESSION['current_language'] . '.ini', 'main');

		//-- Информация
		$data = array(
			//-- ID навигации
			'page' => 'dashboard',
			//-- Title
			'page_title' => $Smarty->_get('dashboard_page_title'),
			//-- Header
			'header' => $Smarty->_get('dashboard_page_header'),
			//-- Breadcrumbs
			'breadcrumbs' => array(
				array(
					'text' => $Smarty->_get('main_page'),
					'href' => './',
					'page' => 'dashboard',
					'push' => 'true',
					'active' => false
				),
				array(
					'text' => $Smarty->_get('dashboard_breadcrumb'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => true
				)
			)
		);

		//-- To Smarty
		$Smarty
			->assign('data', $data)
            ->assign('_is_ajax', Request::isAjax())
            ->assign('orgdata', self::$model->index())
            ->assign('stats', self::$model->getIncome())
            ->assign('active_orders', self::$model->getActiveOrders())
            ->assign('services', self::$model->getServices())
            ->assign('payment_tpl', $Smarty->fetch('modules/dashboard/view/payment.tpl'))
			->assign('right_header', $Smarty->fetch('modules/dashboard/view/right.tpl'))
			->assign('active_orders_content', $Smarty->fetch('modules/dashboard/view/activeorders.tpl'))
			->assign('content', $Smarty->fetch('modules/dashboard/view/index.tpl'));
	}

	public static function activeorders()
	{
        //-- Инстанс Smarty
        $Smarty = Tpl::getInstance();

        //-- Подгружаем файл переводов
        $Smarty->_load(CP_DIR . '/modules/dashboard/lang/' . $_SESSION['current_language'] . '.ini', 'main');

        //-- Информация
        $data = array(
            //-- ID навигации
            'page' => 'dashboard',
            //-- Title
            'page_title' => $Smarty->_get('dashboard_page_title'),
            //-- Header
            'header' => $Smarty->_get('dashboard_page_header'),
            //-- Breadcrumbs
            'breadcrumbs' => array(
                array(
                    'text' => $Smarty->_get('main_page'),
                    'href' => './',
                    'page' => 'dashboard',
                    'push' => 'true',
                    'active' => false
                ),
                array(
                    'text' => $Smarty->_get('dashboard_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => true
                )
            )
        );

        //-- To Smarty
        $Smarty
            ->assign('data', $data)
            ->assign('_is_ajax', Request::isAjax())
            ->assign('orgdata', self::$model->index())
            ->assign('active_orders', self::$model->getActiveOrders())
            ->assign('services', self::$model->getServices())
            ->assign('payment_tpl', $Smarty->fetch('modules/dashboard/view/payment.tpl'))
            ->assign('right_header', $Smarty->fetch('modules/dashboard/view/right.tpl'))
            ->assign('active_orders_content', $Smarty->fetch('modules/dashboard/view/activeorders.tpl'))
            ->assign('content', $Smarty->fetch('modules/dashboard/view/index.tpl'));
	}

    public static function recognize()
    {
        self::$model->recognize();
    }

    public static function getcustomer()
    {
        self::$model->getcustomer();
    }

    public static function getevents()
    {
        self::$model->getevents();
    }

	public static function test()
	{
		Router::model()->test();
	}
}