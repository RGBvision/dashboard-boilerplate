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
    ABS_PATH . 'assets/lib/ion-range-sliders/ion.rangeSlider.min.css',
    100
);
Dependencies::add(
    ABS_PATH . 'assets/lib/ion-range-sliders/ion.rangeSlider.min.js',
    120
);
Dependencies::add(
    ABS_PATH . 'modules/company/js/company.js',
    130
);

class ControllerCompany extends Controller
{
    //-- Model
    public static $route_id;
    protected static $model;


    /*
     |--------------------------------------------------------------------------------------
     | ControllerCompany конструктор
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
     | Router: company
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
        $data = array(
            //-- Navigation
            'page' => 'company',
            //-- Title
            'page_title' => $Smarty->_get('company_page_title'),
            //-- Header
            'header' => $Smarty->_get('company_page_header'),
            //-- Breadcrumbs
            'breadcrumbs' => array(
                array(
                    'text' => $Smarty->_get('main_page'),
                    'href' => './',
                    'page' => 'dashboard',
                    'push' => 'true',
                    'active' => true
                ),
                array(
                    'text' => $Smarty->_get('company_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => false
                )
            )
        );

        $permission = Permission::perm('company_edit');

        //-- To Smarty
        $Smarty
            ->assign('company', self::$model->getCompany())
            ->assign('company_id', ORGID)
            ->assign('permission', $permission)
            ->assign('data', $data)
            ->assign('right_header', $Smarty->fetch('modules/company/view/right.tpl'))
            ->assign('content', $Smarty->fetch('modules/company/view/index.tpl'));
    }


    /*
     |--------------------------------------------------------------------------------------
     | Router: company/save
     |--------------------------------------------------------------------------------------
     |
     |
     */
    public static function save()
    {
        $Smarty = Tpl::getInstance();

        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->saveCompany();
    }
}