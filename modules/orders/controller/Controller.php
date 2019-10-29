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
    ABS_PATH . 'assets/lib/summernote_new/summernote-bs4.css',
    ABS_PATH . 'assets/lib/summernote_new/summernote-bs4.min.js',
    ABS_PATH . 'assets/lib/summernote_new/lang/summernote-ru-RU.js',
    ABS_PATH . 'assets/lib/jquery-print/jQuery.print.min.js',
	ABS_PATH . 'modules/orders/js/orders.js'
);

foreach ($files as $i => $file) {
	Dependencies::add(
		$file,
		$i + 100
	);
}

class ControllerOrders extends Controller
{
	//-- Model
	public static $route_id;
	public static $model;

	/*
	 |--------------------------------------------------------------------------------------
	 | ControllerPages конструктор
	 |--------------------------------------------------------------------------------------
	 | Сразу назначаем Model из Router
	 |
	 */
	public function __construct()
	{
		self::$route_id = Router::getId();
		self::$model = Router::model();
	}


	//-- Main
	public static function index()
	{
		//-- Get Smarty Instance
		$Smarty = Tpl::getInstance();

		//-- Get Lang file
		$Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'main');

		//-- Data page
		$data = array(
			//-- Navigation
			'page' => self::$route_id,
			//-- Title
			'page_title' => $Smarty->_get('orders_page_title'),
			//-- Header
			'header' => $Smarty->_get('orders_page_header'),
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
					'text' => $Smarty->_get('orders_breadcrumb'),
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
			->assign('access', Permission::perm('orders_control'))
			->assign('orders', self::$model->getOrders())
			->assign('right_header', $Smarty->fetch('modules/' . self::$route_id . '/view/right.tpl'))
			->assign('content', $Smarty->fetch('modules/' . self::$route_id . '/view/index.tpl'));
	}

    public static function show()
    {

        //-- Get Smarty Instance
        $Smarty = Tpl::getInstance();

        //-- Get Lang file
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'main');

        //-- Data page
        $data = array(
            //-- Navigation
            'page' => self::$route_id,
            //-- Title
            'page_title' => $Smarty->_get('orders_edit_page_title'),
            //-- Header
            'header' => $Smarty->_get('orders_edit_page_header'),
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
                    'text' => $Smarty->_get('orders_edit_breadcrumb'),
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
            ->assign('access', Permission::perm('orders_control'))
            ->assign('order_data', self::$model->getOrderDetails(Request::request('order_id'), true))
            ->assign('order_details', $Smarty->fetch('modules/' . self::$route_id . '/view/details.tpl'))
            ->assign('right_header', $Smarty->fetch('modules/' . self::$route_id . '/view/right_show.tpl'))
            ->assign('content', $Smarty->fetch('modules/' . self::$route_id . '/view/show.tpl'));
    }

	//-- Edit Order
	public static function edit()
	{
		//-- Get Smarty Instance
		$Smarty = Tpl::getInstance();

		//-- Get Lang file
		$Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'main');

		//-- Data page
		$data = array(
			//-- Navigation
			'page' => self::$route_id,
			//-- Title
			'page_title' => $Smarty->_get('orders_edit_page_title'),
			//-- Header
			'header' => $Smarty->_get('orders_edit_page_header'),
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
					'text' => $Smarty->_get('orders_edit_breadcrumb'),
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
			->assign('access', Permission::perm('orders_control'))
			->assign('order_data', self::$model->getOrderDetails())
			->assign('order_details', $Smarty->fetch('modules/' . self::$route_id . '/view/details.tpl'))
			->assign('right_header', $Smarty->fetch('modules/' . self::$route_id . '/view/right_edit.tpl'))
			->assign('content', $Smarty->fetch('modules/' . self::$route_id . '/view/edit.tpl'));
	}

    //-- Edit Order
    public static function beforepay()
    {
        //-- Get Smarty Instance
        $Smarty = Tpl::getInstance();

        //-- Get Lang file
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'main');

        //-- Data page
        $data = array(
            //-- Navigation
            'page' => self::$route_id,
            //-- Title
            'page_title' => $Smarty->_get('orders_edit_page_title'),
            //-- Header
            'header' => $Smarty->_get('orders_edit_page_header'),
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
                    'text' => $Smarty->_get('orders_edit_breadcrumb'),
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
            ->assign('order_data', self::$model->getOrderDetails())
            ->assign('order_details', $Smarty->fetch('modules/' . self::$route_id . '/view/details.tpl'))
            ->assign('right_header', $Smarty->fetch('modules/' . self::$route_id . '/view/right_edit.tpl'))
            ->assign('content', $Smarty->fetch('modules/' . self::$route_id . '/view/edit.tpl'));
    }

    /*
     |--------------------------------------------------------------------------------------
     | Router: orders/add
     |--------------------------------------------------------------------------------------
     |
     |
     */
    public static function add()
    {
        $Smarty = Tpl::getInstance();

        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->addOrder();
    }

    /*
     |--------------------------------------------------------------------------------------
     | Router: orders/close
     |--------------------------------------------------------------------------------------
     |
     |
     */
    public static function close()
    {
        $Smarty = Tpl::getInstance();

        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->closeOrder();
    }

    /*
     |--------------------------------------------------------------------------------------
     | Router: orders/pay
     |--------------------------------------------------------------------------------------
     |
     |
     */
    public static function pay()
    {
        $Smarty = Tpl::getInstance();

        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->payOrder();
    }
}