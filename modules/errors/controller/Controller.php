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


class ControllerErrors extends Controller
{
    //-- Model
    public static $route_id;
    protected static $model;

    /*
     |--------------------------------------------------------------------------------------
     | ControllerErrors конструктор
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
        $Smarty->_load(CP_DIR . '/modules/errors/lang/' . $_SESSION['current_language'] . '.ini', 'main');

        $_header = $Smarty->_get('header_404');
        $_message = $Smarty->_get('message_404');

        //-- Data page
        $data = array(
            //-- Navigation
            'page' => 'errors',
            //-- Title
            'page_title' => $Smarty->_get('404_page_title'),
            //-- Header
            'header' => $Smarty->_get('404_page_title'),
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
                    'text' => $Smarty->_get('404_breadcrumb'),
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
            ->assign('_header', $_header)
            ->assign('_message', $_message)
            ->assign('right_header', $Smarty->fetch('modules/errors/view/right.tpl'))
            ->assign('content', $Smarty->fetch('modules/errors/view/index.tpl'));
    }


    //-- Main
    public static function model()
    {
        //-- Get Smarty Instance
        $Smarty = Tpl::getInstance();

        //-- Get Lang file
        $Smarty->_load(CP_DIR . '/modules/errors/lang/' . $_SESSION['current_language'] . '.ini', 'main');

        $request = Request::get('model');

        $_header = $Smarty->_get('header_model');
        $_message = sprintf($Smarty->_get('message_model'), $request);

        if (Request::isAjax())
            self::$model->message($_header, $_message);

        //-- Data page
        $data = array(
            //-- Navigation
            'page' => 'errors',
            //-- Title
            'page_title' => $Smarty->_get('error_page_title'),
            //-- Header
            'header' => $Smarty->_get('error_page_title'),
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
                    'text' => $Smarty->_get('error_page_title'),
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
            ->assign('_header', $_header)
            ->assign('_message', $_message)
            ->assign('right_header', $Smarty->fetch('modules/errors/view/right.tpl'))
            ->assign('content', $Smarty->fetch('modules/errors/view/index.tpl'));
    }


    //-- Main
    public static function controller()
    {
        //-- Get Smarty Instance
        $Smarty = Tpl::getInstance();

        //-- Get Lang file
        $Smarty->_load(CP_DIR . '/modules/errors/lang/' . $_SESSION['current_language'] . '.ini', 'main');

        $request = Request::get('controller');

        $_header = $Smarty->_get('header_controller');
        $_message = sprintf($Smarty->_get('message_controller'), $request);

        if (!Request::isPjax() AND Request::isAjax())
            self::$model->message($_header, $_message);

        //-- Data page
        $data = array(
            //-- Navigation
            'page' => 'errors',
            //-- Title
            'page_title' => $Smarty->_get('error_page_title'),
            //-- Header
            'header' => $Smarty->_get('error_page_title'),
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
                    'text' => $Smarty->_get('error_page_title'),
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
            ->assign('_header', $_header)
            ->assign('_message', $_message)
            ->assign('right_header', $Smarty->fetch('modules/errors/view/right.tpl'))
            ->assign('content', $Smarty->fetch('modules/errors/view/index.tpl'));
    }


    //-- Main
    public static function method()
    {
        //-- Get Smarty Instance
        $Smarty = Tpl::getInstance();

        //-- Get Lang file
        $Smarty->_load(CP_DIR . '/modules/errors/lang/' . $_SESSION['current_language'] . '.ini', 'main');

        $request = Request::get('method');
        $parts = array();

        if (isset($request))
            $parts = explode('/', preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$request));

        $_header = $Smarty->_get('header_method');
        $_message = sprintf($Smarty->_get('message_method'), $parts[0], $parts[1]);

        if (Request::isPjax() == false AND Request::isAjax())
            self::$model->message($_header, $_message);

        //-- Data page
        $data = array(
            //-- Navigation
            'page' => 'errors',
            //-- Title
            'page_title' => $Smarty->_get('error_page_title'),
            //-- Header
            'header' => $Smarty->_get('error_page_title'),
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
                    'text' => $Smarty->_get('error_page_title'),
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
            ->assign('_header', $_header)
            ->assign('_message', $_message)
            ->assign('right_header', $Smarty->fetch('modules/errors/view/right.tpl'))
            ->assign('content', $Smarty->fetch('modules/errors/view/index.tpl'));
    }


    //-- Denied
    public static function denied()
    {
        //-- Get Smarty Instance
        $Smarty = Tpl::getInstance();

        //-- Get Lang file
        $Smarty->_load(CP_DIR . '/modules/errors/lang/' . $_SESSION['current_language'] . '.ini', 'main');

        $_header = $Smarty->_get('header_denied');
        $_message = $Smarty->_get('message_denied');

        if (!Request::isPjax() AND Request::isAjax())
            self::$model->message($_header, $_message);

        //-- Data page
        $data = array(
            //-- Navigation
            'page' => 'errors',
            //-- Title
            'page_title' => $Smarty->_get('error_page_title'),
            //-- Header
            'header' => $Smarty->_get('error_page_title'),
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
                    'text' => $Smarty->_get('error_page_title'),
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
            ->assign('_header', $_header)
            ->assign('_message', $_message)
            ->assign('right_header', $Smarty->fetch('modules/errors/view/right.tpl'))
            ->assign('content', $Smarty->fetch('modules/errors/view/index.tpl'));
    }
}