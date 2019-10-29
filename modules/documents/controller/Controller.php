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
	ABS_PATH . 'modules/documents/js/documents.js'
);

foreach ($files as $i => $file) {
	Dependencies::add(
		$file,
		$i + 100
	);
}

	class ControllerDocuments extends Controller
	{
		//-- Model
		public static $route_id;
		protected static $model;


		/*
		 |--------------------------------------------------------------------------------------
		 | ControllerDocuments конструктор
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
		 | Router: documents
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

			//-- Информация
			$data = array (
				//-- ID навигации
				'page' => 'documents',
				//-- Title
				'page_title' => $Smarty->_get('documents_page_title'),
				//-- Header
				'header' => $Smarty->_get('documents_page_header'),
				//-- Breadcrumbs
				'breadcrumbs' => array (
					array (
						'text'      => $Smarty->_get('main_page'),
						'href'      => './',
						'page'      => 'dashboard',
						'push'      => 'true',
						'active'    => false
					),
					array(
						'text'      => $Smarty->_get('documents_breadcrumb'),
						'href'      => '',
						'page'      => '',
						'push'      => '',
						'active'    => true
					)
				)
			);

			$Smarty
				->assign('data', $data)
				->assign('access', Permission::perm('documents_control'))
				->assign('documents', self::$model->getDocuments())
				->assign('right_header', $Smarty->fetch('modules/documents/view/right.tpl'))
				->assign('content', $Smarty->fetch('modules/documents/view/index.tpl'));
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
                'page_title' => $Smarty->_get('document_page_title'),
                //-- Header
                'header' => $Smarty->_get('document_page_header'),
                //-- Breadcrumbs
                'breadcrumbs' => array (
                    array (
                        'text'      => $Smarty->_get('main_page'),
                        'href'      => './',
                        'page'      => 'dashboard',
                        'push'      => 'true',
                        'active'    => false
                    ),
                    array(
                        'text'      => $Smarty->_get('documents_breadcrumb'),
                        'href'      => '',
                        'page'      => '',
                        'push'      => '',
                        'active'    => true
                    )
                )
            );

            //-- To Smarty
            $Smarty
                ->assign('data', $data)
                ->assign('_is_ajax', Request::isAjax())
                ->assign('access', Permission::perm('orders_control'))
                ->assign('doc_data', self::$model->getDocument(Request::request('id')))
                ->assign('right_header', $Smarty->fetch('modules/' . self::$route_id . '/view/right_show.tpl'))
                ->assign('content', $Smarty->fetch('modules/' . self::$route_id . '/view/show.tpl'));
        }

        public static function templates()
        {
            //-- Инстанс Smarty
            $Smarty = Tpl::getInstance();

            //-- Подгружаем файл переводов
            $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'main');

            //-- Информация
            $data = array (
                //-- ID навигации
                'page' => 'documents_templates',
                //-- Title
                'page_title' => $Smarty->_get('documents_templates_page_title'),
                //-- Header
                'header' => $Smarty->_get('documents_templates_page_header'),
                //-- Breadcrumbs
                'breadcrumbs' => array (
                    array (
                        'text'      => $Smarty->_get('main_page'),
                        'href'      => './',
                        'page'      => 'dashboard',
                        'push'      => 'true',
                        'active'    => false
                    ),
                    array(
                        'text'      => $Smarty->_get('documents_templates_breadcrumb'),
                        'href'      => '',
                        'page'      => '',
                        'push'      => '',
                        'active'    => true
                    )
                )
            );

            $Smarty
                ->assign('data', $data)
                ->assign('access', Permission::perm('documents_control'))
                ->assign('templates', self::$model->getTemplates())
                ->assign('right_header', $Smarty->fetch('modules/documents/view/templates_right.tpl'))
                ->assign('content', $Smarty->fetch('modules/documents/view/templates.tpl'));
        }

        public static function tpladd()
        {
            //-- Инстанс Smarty
            $Smarty = Tpl::getInstance();

            //-- Подгружаем файл переводов
            $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'main');

            //-- Информация
            $data = array (
                //-- ID навигации
                'page' => 'documents_templates',
                //-- Title
                'page_title' => $Smarty->_get('documents_template_edit_page_title'),
                //-- Header
                'header' => $Smarty->_get('documents_template_edit_page_header'),
                //-- Breadcrumbs
                'breadcrumbs' => array (
                    array (
                        'text'      => $Smarty->_get('main_page'),
                        'href'      => './',
                        'page'      => 'dashboard',
                        'push'      => 'true',
                        'active'    => false
                    ),
                    array(
                        'text'      => $Smarty->_get('documents_template_edit_breadcrumb'),
                        'href'      => '',
                        'page'      => '',
                        'push'      => '',
                        'active'    => true
                    )
                )
            );

            $Smarty
                ->assign('data', $data)
                ->assign('template', array('template' => ''))
                ->assign('action', 'add')
                ->assign('department_types', array(
                        0 => 'Заказ любой',
                        1 => 'Заказ на мойке',
                        2 => 'Заказ в сервисе',
                        3 => 'Заказ на кассе')
                )
                ->assign('show_types', array(
                        0 => 'Не выводить',
                        2 => 'Администратору',
                        3 => 'Кассиру')
                )
                ->assign('right_header', $Smarty->fetch('modules/documents/view/template_edit_right.tpl'))
                ->assign('content', $Smarty->fetch('modules/documents/view/template_edit.tpl'));
        }

        public static function tpledit()
        {
            //-- Инстанс Smarty
            $Smarty = Tpl::getInstance();

            //-- Подгружаем файл переводов
            $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'main');

            //-- Информация
            $data = array (
                //-- ID навигации
                'page' => 'documents_templates',
                //-- Title
                'page_title' => $Smarty->_get('documents_template_edit_page_title'),
                //-- Header
                'header' => $Smarty->_get('documents_template_edit_page_header'),
                //-- Breadcrumbs
                'breadcrumbs' => array (
                    array (
                        'text'      => $Smarty->_get('main_page'),
                        'href'      => './',
                        'page'      => 'dashboard',
                        'push'      => 'true',
                        'active'    => false
                    ),
                    array(
                        'text'      => $Smarty->_get('documents_template_edit_breadcrumb'),
                        'href'      => '',
                        'page'      => '',
                        'push'      => '',
                        'active'    => true
                    )
                )
            );

            $Smarty
                ->assign('data', $data)
                ->assign('template', self::$model->getTemplate())
                ->assign('action', 'edit')
                ->assign('department_types', array(
                        0 => 'Заказ любой',
                        1 => 'Заказ на мойке',
                        2 => 'Заказ в сервисе',
                        3 => 'Заказ на кассе')
                )
                ->assign('show_types', array(
                        0 => 'Не выводить',
                        2 => 'Администратору',
                        3 => 'Кассиру')
                )
                ->assign('right_header', $Smarty->fetch('modules/documents/view/template_edit_right.tpl'))
                ->assign('content', $Smarty->fetch('modules/documents/view/template_edit.tpl'));
        }

        public static function tplsave()
        {
            $Smarty = Tpl::getInstance();

            $Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

            self::$model->saveTemplate();
        }

	}