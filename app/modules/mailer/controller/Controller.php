<?php

/**
 * This source file is part of the AVE.cms. More information,
 * documentation and tutorials can be found at http://www.ave-cms.ru
 *
 * @package      AVE.cms
 * @file         admin/modules/mailer/controller/controller.php
 * @author       AVE.cms <support@ave-cms.ru>
 * @copyright    2007-2017 (c) AVE.cms
 * @link         http://www.ave-cms.ru
 * @version      4.0
 * @since        $date$
 * @license      license GPL v.2 http://www.ave-cms.ru/license.txt
 */


class ControllerMailer extends Controller
{

    /*
     |--------------------------------------------------------------------------------------
     | ControllerMailer конструктор
     |--------------------------------------------------------------------------------------
     |
     */
    public function __construct()
    {
        parent::__construct();
    }


    /*
     |--------------------------------------------------------------------------------------
     | Router: mailer
     |--------------------------------------------------------------------------------------
     | По умолчанию
     |
     */
    public static function index()
    {

        Dependencies::add(
            ABS_PATH . 'modules/mailer/js/mailer.js',
            100
        );

        //-- Get Smarty Instance
        $Template = Template::getInstance();

        //-- Get Lang file
        $Template->_load(DASHBOARD_DIR . '/app/modules/mailer/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        //-- Data page
        $data = [
            //-- Navigation
            'page' => 'mailer',
            //-- Title
            'page_title' => $Template->_get('mailer_page_title'),
            //-- Header
            'header' => $Template->_get('mailer_page_header'),
            //-- Breadcrumbs
            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => './',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('mailer_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $permission = Permission::perm('mailer_edit');

        //-- To Smarty
        $Template
            ->assign('data', $data)
            ->assign('settings', Settings::get())
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/mailer/view/index.tpl'));
    }


    /*
     |--------------------------------------------------------------------------------------
     | Router: mailer/save
     |--------------------------------------------------------------------------------------
     | Запуск тестирования системы
     |
     */
    public static function save()
    {
        self::$model->saveMailer();
    }
}