<?php

/**
 * This file is part of the dashboard.rgbvision.net package.
 *
 * (c) Alex Graham <contact@rgbvision.net>
 *
 * @package    dashboard.rgbvision.net
 * @author     Alex Graham <contact@rgbvision.net>
 * @copyright  Copyright 2017-2022, Alex Graham
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    3.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class MailerController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {

        Dependencies::add(
            ABS_PATH . 'app/modules/mailer/js/mailer.js',
            100
        );

        //-- Get Smarty Instance
        $Template = Template::getInstance();

        //-- Get Lang file
        $Template->_load(DASHBOARD_DIR . '/app/modules/mailer/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

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

        $permission = Permissions::has('mailer_edit');

        //-- To Smarty
        $Template
            ->assign('data', $data)
            ->assign('settings', Settings::get())
            ->assign('content', $Template->fetch($this->module->path . '/view/index.tpl'));
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