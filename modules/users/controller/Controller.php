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



Dependencies::add(ABS_PATH . 'assets/lib/smartcrop/smartcrop.js', 110);
Dependencies::add(ABS_PATH . 'assets/lib/summernote_new/summernote-bs4.css', 170);
Dependencies::add(ABS_PATH . 'assets/lib/summernote_new/summernote-bs4.min.js', 180);
Dependencies::add(ABS_PATH . 'assets/lib/summernote_new/lang/summernote-ru-RU.js', 190);
Dependencies::add(ABS_PATH . 'modules/users/js/users.js', 200);

class ControllerUsers extends Controller
{
    //-- Model
    public static $route_id;
    protected static $model;


    /*
     |--------------------------------------------------------------------------------------
     | ControllerUsers конструктор
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
     | Router: users
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
            'page' => 'users',
            //-- Title
            'page_title' => $Smarty->_get('users_page_title'),
            //-- Header
            'header' => $Smarty->_get('users_page_header'),
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
                    'text' => $Smarty->_get('users_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => true
                )
            )
        );

        $Smarty
            ->assign('users', self::$model->getUsers(1))
            ->assign('data', $data)
            ->assign('can_add_user', self::$model->canAddUser())
            ->assign('right_header', $Smarty->fetch('modules/users/view/right.tpl'))
            ->assign('content', $Smarty->fetch('modules/users/view/index.tpl'));
    }

    /*
	 |--------------------------------------------------------------------------------------
	 | Router: users/edit
	 |--------------------------------------------------------------------------------------
	 |
	 |
	 */
    public static function edit()
    {

        Dependencies::add('https://unpkg.com/opencv.js@1.2.1/opencv.js', 300, 'async onload="openCvReady()"');

        $user_id = (int)Request::get('user_id');

        //-- Инстанс Smarty
        $Smarty = Tpl::getInstance();

        //-- Подгружаем файл переводов
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        //-- Информация
        $data = array(
            //-- ID навигации
            'page' => 'users',
            //-- Title
            'page_title' => $Smarty->_get('users_page_edit_title'),
            //-- Header
            'header' => $Smarty->_get('users_page_edit_header'),
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
                    'text' => $Smarty->_get('users_breadcrumb_parent'),
                    'href' => '/route/users',
                    'page' => 'users',
                    'push' => 'true',
                    'active' => true
                ),
                array(
                    'text' => $Smarty->_get('users_breadcrumb_edit'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => false
                )
            )
        );

        $Smarty
            ->assign('data', $data)
            ->assign('user', self::$model->getUser($user_id))
            ->assign('groups', self::$model->getGroups(1))
            ->assign('employees', self::$model->getEmployees())
            ->assign('action', 'save')
            ->assign('right_header', $Smarty->fetch('modules/users/view/right_edit.tpl'))
            ->assign('content', $Smarty->fetch('modules/users/view/edit.tpl'));
    }

    /*
	 |--------------------------------------------------------------------------------------
	 | Router: users/edit
	 |--------------------------------------------------------------------------------------
	 |
	 |
	 */
    public static function add()
    {

        Dependencies::add('https://unpkg.com/opencv.js@1.2.1/opencv.js', 130, 'async onload="openCvReady()"');

        $user_id = (int)Request::get('user_id');

        //-- Инстанс Smarty
        $Smarty = Tpl::getInstance();

        //-- Подгружаем файл переводов
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        //-- Информация
        $data = array(
            //-- ID навигации
            'page' => 'users',
            //-- Title
            'page_title' => $Smarty->_get('users_page_add_title'),
            //-- Header
            'header' => $Smarty->_get('users_page_add_header'),
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
                    'text' => $Smarty->_get('users_breadcrumb_parent'),
                    'href' => '/route/users',
                    'page' => 'users',
                    'push' => 'true',
                    'active' => true
                ),
                array(
                    'text' => $Smarty->_get('users_breadcrumb_add'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => false
                )
            )
        );

        $Smarty
            ->assign('data', $data)
            ->assign('user', array('editable' => self::$model->canAddUser()))
            ->assign('groups', self::$model->getGroups(1))
            ->assign('employees', self::$model->getEmployees())
            ->assign('action', 'add')
            ->assign('right_header', $Smarty->fetch('modules/users/view/right_edit.tpl'))
            ->assign('content', $Smarty->fetch('modules/users/view/edit.tpl'));
    }

    /*
	 |--------------------------------------------------------------------------------------
	 | Router: users/save
	 |--------------------------------------------------------------------------------------
	 |
	 |
	 */
    public static function save()
    {
        $Smarty = Tpl::getInstance();

        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->saveUser();
    }

    /*
	 |--------------------------------------------------------------------------------------
	 | Router: users/delete
	 |--------------------------------------------------------------------------------------
	 |
	 |
	 */
    public static function delete()
    {
        $Smarty = Tpl::getInstance();

        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->deleteUser();
    }

    public static function checkphone()
    {
        $Smarty = Tpl::getInstance();

        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->checkUserPhone();
    }

}