<?php

/**
 * This source file is part of the AVE.cms. More information,
 * documentation and tutorials can be found at http://www.ave-cms.ru
 *
 * @package      AVE.cms
 * @file         admin/modules/mailer/module.php
 * @author       AVE.cms <support@ave-cms.ru>
 * @copyright    2007-2017 (c) AVE.cms
 * @link         http://www.ave-cms.ru
 * @version      4.0
 * @since        $date$
 * @license      license GPL v.2 http://www.ave-cms.ru/license.txt
 */


class ModuleMailer extends Module
{
    //-- Версия модуля
    public static string $version = '1.0';

    //-- Дата
    public static string $date = '18.12.2017';

    //-- Системное имя модуля
    public static string $moduleName = 'mailer';


    public function __construct()
    {
        //-- Родитель
        parent::__construct();


        /*
        |--------------------------------------------------------------------------------------
        | Инстанс Smarty
        |--------------------------------------------------------------------------------------
        |
        */
        $Template = Template::getInstance();


        /*
        |--------------------------------------------------------------------------------------
        | Подгружаем файл переводов
        |--------------------------------------------------------------------------------------
        |
        */
        $Template->_load(DASHBOARD_DIR . '/app/modules/mailer/i18n/' . Session::getvar('current_language') . '.ini', 'name');


        /*
        |--------------------------------------------------------------------------------------
        | Добавляем подменю навигации
        |--------------------------------------------------------------------------------------
        |
        */
        Navigation::add(
            20,
            $Template->_get('mailer_menu_name'),
            'mdi mdi-email-outline',
            ABS_PATH . 'mailer',
            self::$moduleName,
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_SETTINGS,
            '',
            '',
            false
        );
    }
}

