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
    //ABS_PATH . 'assets/lib/clockpicker/bootstrap-clockpicker.min.css',
    //ABS_PATH . 'assets/lib/clockpicker/bootstrap-clockpicker.min.js',
    ABS_PATH . 'assets/lib/jquery-ui/jquery-ui.min.js',
    ABS_PATH . 'assets/lib/jquery-ui/jquery.ui.touch-punch.min.js',
    ABS_PATH . 'assets/lib/select2/css/select2.min.css',
    ABS_PATH . 'assets/lib/select2/css/select2-bootstrap4.min.css',
    ABS_PATH . 'assets/lib/select2/js/select2.min.js',
    ABS_PATH . 'modules/services/js/services.js'
);

foreach ($files as $i => $file) {
    Dependencies::add(
        $file,
        $i + 100
    );
}


class ControllerServices extends Controller
{
    //-- Model
    public static $route_id;
    protected static $model;


    /*
     |--------------------------------------------------------------------------------------
     | ControllerServices конструктор
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
     | Router: services
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
            'page' => 'services',
            //-- Title
            'page_title' => $Smarty->_get('services_page_title'),
            //-- Header
            'header' => $Smarty->_get('services_page_header'),
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
                    'text' => $Smarty->_get('services_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => true
                )
            )
        );

        $Smarty
            ->assign('services', self::$model->getServices())
            ->assign('data', $data)
            ->assign('right_header', $Smarty->fetch('modules/services/view/right.tpl'))
            ->assign('content', $Smarty->fetch('modules/services/view/index.tpl'));
    }

    /*
     |--------------------------------------------------------------------------------------
     | Router: services/add
     |--------------------------------------------------------------------------------------
     |
     |
     */
    public static function add()
    {
        //-- Инстанс Smarty
        $Smarty = Tpl::getInstance();

        //-- Подгружаем файл переводов
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        //-- Информация
        $data = array(
            //-- ID навигации
            'page' => 'services',
            //-- Title
            'page_title' => $Smarty->_get('services_page_add_title'),
            //-- Header
            'header' => $Smarty->_get('services_page_add_header'),
            //-- Breadcrumbs
            'breadcrumbs' => array(
                array(
                    'text' => $Smarty->_get('main_page'),
                    'href' => '/route/dashboard',
                    'page' => 'dashboard',
                    'push' => 'true',
                    'active' => true
                ),
                array(
                    'text' => $Smarty->_get('services_breadcrumb_parent'),
                    'href' => '/route/services',
                    'page' => 'services',
                    'push' => 'true',
                    'active' => true
                ),
                array(
                    'text' => $Smarty->_get('services_breadcrumb_add'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => false
                )
            )
        );

        $calculation = array();

        for ($i = 0; $i <= $_SESSION['organization_settings']['classes']; $i++) {
            $calculation[$i] = array('cost' => 0, 'time_limit' => '0', 'reward' => 0, 'measure' => 1, 'salary' => 0, 'unit' => 1);
        }

        $calculation['parametric'][0] = array('name'=> '', 'cost' => 0, 'time_limit' => '0', 'reward' => 0, 'measure' => 1, 'salary' => 0, 'unit' => 1);

        $Smarty
            ->assign('data', $data)
            ->assign('departmentOptions', array(
                    0 => 'Любой',
                    1 => 'Мойка',
                    2 => 'Сервис',
                    3 => 'Касса')
            )
            ->assign('unit_ids', SALARYUNITS)
            ->assign('service', array(
                'type' => 1,
                'max_count' => 1,
                'department' => 0,
                'calculation' => $calculation
            ))
            ->assign('consumables', self::$model->getConsumables())
            ->assign('access_edit', Permission::perm('services_edit'))
            ->assign('access_delete', Permission::perm('services_delete'))
            ->assign('access_finances', Permission::perm('services_finances'))
            ->assign('right_header', $Smarty->fetch('modules/services/view/right_edit.tpl'))
            ->assign('content', $Smarty->fetch('modules/services/view/edit.tpl'));
    }

    /*
     |--------------------------------------------------------------------------------------
     | Router: services/add
     |--------------------------------------------------------------------------------------
     |
     |
     */
    public static function edit()
    {

        $service_id = (int)Request::get('service_id');

        //-- Инстанс Smarty
        $Smarty = Tpl::getInstance();

        //-- Подгружаем файл переводов
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        //-- Информация
        $data = array(
            //-- ID навигации
            'page' => 'services',
            //-- Title
            'page_title' => $Smarty->_get('services_page_edit_title'),
            //-- Header
            'header' => $Smarty->_get('services_page_edit_header'),
            //-- Breadcrumbs
            'breadcrumbs' => array(
                array(
                    'text' => $Smarty->_get('main_page'),
                    'href' => '/route/dashboard',
                    'page' => 'dashboard',
                    'push' => 'true',
                    'active' => true
                ),
                array(
                    'text' => $Smarty->_get('services_breadcrumb_parent'),
                    'href' => '/route/services',
                    'page' => 'services',
                    'push' => 'true',
                    'active' => true
                ),
                array(
                    'text' => $Smarty->_get('services_breadcrumb_edit'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => false
                )
            )
        );

        $Smarty
            ->assign('data', $data)
            ->assign('departmentOptions', array(
                    0 => 'Любой',
                    1 => 'Мойка',
                    2 => 'Сервис',
                    3 => 'Касса')
            )
            ->assign('unit_ids', SALARYUNITS)
            ->assign('service', self::$model->getService($service_id))
            ->assign('consumables', self::$model->getConsumables())
            ->assign('access_edit', Permission::perm('services_edit'))
            ->assign('access_delete', Permission::perm('services_delete'))
            ->assign('access_finances', Permission::perm('services_finances'))
            ->assign('right_header', $Smarty->fetch('modules/services/view/right_edit.tpl'))
            ->assign('content', $Smarty->fetch('modules/services/view/edit.tpl'));
    }

    /*
     |--------------------------------------------------------------------------------------
     | Router: services/save
     |--------------------------------------------------------------------------------------
     |
     |
     */
    public static function save()
    {
        $Smarty = Tpl::getInstance();

        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->saveService();
    }

    /*
     |--------------------------------------------------------------------------------------
     | Router: services/delete
     |--------------------------------------------------------------------------------------
     |
     |
     */
    public static function delete()
    {
        self::$model->deleteService();
    }

    /*
     |--------------------------------------------------------------------------------------
     | Router: services/sort
     |--------------------------------------------------------------------------------------
     |
     |
     */
    public static function sort()
    {
        self::$model->sort();
    }

}