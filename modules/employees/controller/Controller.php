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
    ABS_PATH . 'assets/lib/smartcrop/smartcrop.js',
    ABS_PATH . 'assets/lib/summernote_new/summernote-bs4.css',
    ABS_PATH . 'assets/lib/summernote_new/summernote-bs4.min.js',
    ABS_PATH . 'assets/lib/summernote_new/lang/summernote-ru-RU.js',
    ABS_PATH . 'assets/lib/jquery-ui/jquery-ui.min.js',
    ABS_PATH . 'assets/lib/jquery-ui/jquery.ui.touch-punch.min.js',
    ABS_PATH . 'modules/employees/js/employees.js'
);

foreach ($files as $i => $file) {
    Dependencies::add(
        $file,
        $i + 100
    );
}


class ControllerEmployees extends Controller
{
    //-- Model
    public static $route_id;
    protected static $model;


    /*
     |--------------------------------------------------------------------------------------
     | ControllerEmployees конструктор
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
     | Router: employees
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
            'page' => 'employees',
            //-- Title
            'page_title' => $Smarty->_get('employees_page_title'),
            //-- Header
            'header' => $Smarty->_get('employees_page_header'),
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
                    'text' => $Smarty->_get('employees_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => true
                )
            )
        );

        $Smarty
            ->assign('employees', self::$model->getEmployees())
            ->assign('data', $data)
            ->assign('can_add_employee', self::$model->canAddEmployee())
            ->assign('right_header', $Smarty->fetch('modules/employees/view/right.tpl'))
            ->assign('content', $Smarty->fetch('modules/employees/view/index.tpl'));
    }

    /*
	 |--------------------------------------------------------------------------------------
	 | Router: employees/edit
	 |--------------------------------------------------------------------------------------
	 |
	 |
	 */
    public static function edit()
    {

        Dependencies::add('https://unpkg.com/opencv.js@1.2.1/opencv.js', 300, 'async onload="openCvReady()"');

        $employee_id = (int)Request::get('employee_id');

        //-- Инстанс Smarty
        $Smarty = Tpl::getInstance();

        //-- Подгружаем файл переводов
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        //-- Информация
        $data = array(
            //-- ID навигации
            'page' => 'employees',
            //-- Title
            'page_title' => $Smarty->_get('employees_page_edit_title'),
            //-- Header
            'header' => $Smarty->_get('employees_page_edit_header'),
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
                    'text' => $Smarty->_get('employees_breadcrumb_parent'),
                    'href' => '/route/employees',
                    'page' => 'employees',
                    'push' => 'true',
                    'active' => true
                ),
                array(
                    'text' => $Smarty->_get('employees_breadcrumb_edit'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => false
                )
            )
        );

        $Smarty
            ->assign('data', $data)
            ->assign('operand_ids', SALARYOPERANDS)
            ->assign('unit_ids', SALARYUNITS)
            ->assign('employee', self::$model->getEmployee($employee_id))
            ->assign('departments', self::$model->getDepartments())
            ->assign('action', 'save')
            ->assign('right_header', $Smarty->fetch('modules/employees/view/right_edit.tpl'))
            ->assign('content', $Smarty->fetch('modules/employees/view/edit.tpl'));
    }

    /*
	 |--------------------------------------------------------------------------------------
	 | Router: employees/edit
	 |--------------------------------------------------------------------------------------
	 |
	 |
	 */
    public static function add()
    {

        Dependencies::add('https://unpkg.com/opencv.js@1.2.1/opencv.js', 130, 'async onload="openCvReady()"');

        //-- Инстанс Smarty
        $Smarty = Tpl::getInstance();

        //-- Подгружаем файл переводов
        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        //-- Информация
        $data = array(
            //-- ID навигации
            'page' => 'employees',
            //-- Title
            'page_title' => $Smarty->_get('employees_page_add_title'),
            //-- Header
            'header' => $Smarty->_get('employees_page_add_header'),
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
                    'text' => $Smarty->_get('employees_breadcrumb_parent'),
                    'href' => '/route/employees',
                    'page' => 'employees',
                    'push' => 'true',
                    'active' => true
                ),
                array(
                    'text' => $Smarty->_get('employees_breadcrumb_add'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => false
                )
            )
        );

        $Smarty
            ->assign('data', $data)
            ->assign('operand_ids', SALARYOPERANDS)
            ->assign('unit_ids', SALARYUNITS)
            ->assign('employee', array('editable' => self::$model->canAddEmployee(), 'salary' => null))
            ->assign('departments', self::$model->getDepartments())
            ->assign('action', 'add')
            ->assign('right_header', $Smarty->fetch('modules/employees/view/right_edit.tpl'))
            ->assign('content', $Smarty->fetch('modules/employees/view/edit.tpl'));
    }

    /*
	 |--------------------------------------------------------------------------------------
	 | Router: employees/save
	 |--------------------------------------------------------------------------------------
	 |
	 |
	 */
    public static function save()
    {
        $Smarty = Tpl::getInstance();

        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->saveEmployee();
    }

    /*
	 |--------------------------------------------------------------------------------------
	 | Router: employees/delete
	 |--------------------------------------------------------------------------------------
	 |
	 |
	 */
    public static function delete()
    {
        $Smarty = Tpl::getInstance();

        $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->deleteEmployee();
    }
}