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
 * @version    4.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class MailerModule extends Module
{
    //-- Версия модуля
    public static string $version = '4.0';

    //-- Дата
    public static string $date = '07.10.2022';

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

