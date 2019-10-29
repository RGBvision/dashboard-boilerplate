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
    ABS_PATH . 'modules/store/js/store.js'
);

foreach ($files as $i => $file) {
    Dependencies::add(
        $file,
        $i + 100
    );
}


class ControllerStore extends Controller
{
    //-- Model
    public static $route_id;
    protected static $model;


    /*
     |--------------------------------------------------------------------------------------
     | ControllerStore конструктор
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
     | Router: store
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
            'page' => 'store_goods',
            //-- Title
            'page_title' => $Smarty->_get('store_page_title'),
            //-- Header
            'header' => $Smarty->_get('store_page_header'),
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
                    'text' => $Smarty->_get('store_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => true
                )
            )
        );

        $Smarty
            ->assign('store', self::$model->getStore())
            ->assign('data', $data)
            ->assign('right_header', $Smarty->fetch('modules/store/view/right.tpl'))
            ->assign('content', $Smarty->fetch('modules/store/view/index.tpl'));
    }

    public static function consumables()
    {
//-- Инстанс Smarty
        $Smarty = Tpl::getInstance();

        //-- Подгружаем файл переводов
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'main');
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        //-- Информация
        $data = array(
            //-- ID навигации
            'page' => 'store_consumables',
            //-- Title
            'page_title' => $Smarty->_get('store_consumables_page_title'),
            //-- Header
            'header' => $Smarty->_get('store_page_header'),
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
                    'text' => $Smarty->_get('store_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => true
                )
            )
        );

        $departments = array(
            0 => 'Любой',
            1 => 'Мойка',
            2 => 'Сервис',
            3 => 'Касса'
        );

        $Smarty
            ->assign('store', self::$model->getConsumables())
            ->assign('data', $data)
            ->assign('departments', $departments)
            ->assign('right_header', $Smarty->fetch('modules/store/view/right.tpl'))
            ->assign('content', $Smarty->fetch('modules/store/view/index_consumables.tpl'));
    }

    public static function add()
    {
//-- Инстанс Smarty
        $Smarty = Tpl::getInstance();

        //-- Подгружаем файл переводов
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'main');
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        //-- Информация
        $data = array(
            //-- ID навигации
            'page' => 'store_consumables',
            //-- Title
            'page_title' => $Smarty->_get('store_add_page_title'),
            //-- Header
            'header' => $Smarty->_get('store_add_page_title'),
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
                    'text' => $Smarty->_get('store_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => true
                )
            )
        );

        $departments = array(
            0 => 'Любой',
            1 => 'Мойка',
            2 => 'Сервис',
            3 => 'Касса'
        );

        $Smarty
            ->assign('data', $data)
            ->assign('departments', $departments)
            ->assign('right_header', $Smarty->fetch('modules/store/view/right_edit.tpl'))
            ->assign('good', array());;

        if (Request::get('type') == 'consumable') {
            $Smarty->assign('content', $Smarty->fetch('modules/store/view/edit_consumable.tpl'));
        }
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
            'page' => 'store_consumables',
            //-- Title
            'page_title' => $Smarty->_get('store_edit_page_title'),
            //-- Header
            'header' => $Smarty->_get('store_edit_page_title'),
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
                    'text' => $Smarty->_get('store_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => true
                )
            )
        );

        $departments = array(
            0 => 'Любой',
            1 => 'Мойка',
            2 => 'Сервис',
            3 => 'Касса'
        );

        $Smarty
            ->assign('data', $data)
            ->assign('departments', $departments)
            ->assign('right_header', $Smarty->fetch('modules/store/view/right_edit.tpl'))
            ->assign('good', self::$model->getGood());

        if (Request::get('type') == 'consumable') {
            $Smarty->assign('content', $Smarty->fetch('modules/store/view/edit_consumable.tpl'));
        }
    }

    public static function save()
    {
        $Smarty = Tpl::getInstance();

        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->saveGood();
    }

}