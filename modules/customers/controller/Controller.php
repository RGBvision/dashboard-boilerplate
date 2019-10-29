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
	ABS_PATH . 'modules/customers/js/customers.js'
);

foreach ($files as $i => $file) {
	Dependencies::add(
		$file,
		$i + 100
	);
}


class ControllerCustomers extends Controller
{
	//-- Model
	public static $route_id;
	protected static $model;


	/*
	 |--------------------------------------------------------------------------------------
	 | ControllerCustomers конструктор
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
	 | Router: customers
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
		$Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

		//-- Информация
		$data = array(
			//-- ID навигации
			'page' => 'customers',
			//-- Title
			'page_title' => $Smarty->_get('customers_page_title'),
			//-- Header
			'header' => $Smarty->_get('customers_page_header'),
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
					'text' => $Smarty->_get('customers_breadcrumb'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => true
				)
			)
		);

		$Smarty
			->assign('customers', self::$model->getCustomers())
			->assign('data', $data)
			->assign('right_header', $Smarty->fetch('modules/customers/view/right.tpl'))
			->assign('content', $Smarty->fetch('modules/customers/view/index.tpl'));
	}

    public static function update()
    {
        self::$model->update();
    }

    public static function edit()
    {
        //-- Инстанс Smarty
        $Smarty = Tpl::getInstance();

        //-- Подгружаем файл переводов
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'main');
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        //-- Информация
        $data = array(
            //-- ID навигации
            'page' => 'customers',
            //-- Title
            'page_title' => $Smarty->_get('customers_page_title'),
            //-- Header
            'header' => $Smarty->_get('customers_page_header'),
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
                    'text' => $Smarty->_get('customers_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => true
                )
            )
        );

        $Smarty
            ->assign('customers', self::$model->getCustomers())
            ->assign('data', $data)
            ->assign('right_header', $Smarty->fetch('modules/customers/view/right.tpl'))
            ->assign('content', $Smarty->fetch('modules/customers/view/index.tpl'));
    }
}