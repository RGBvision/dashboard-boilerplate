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

class SettingsModule extends Module
{
    //-- Версия модуля
    public static string $version = '4.0';

    //-- Дата
    public static string $date = '07.10.2022';

    //-- Системное имя модуля
    public static string $moduleName = 'settings';


    public function __construct()
    {
        //-- Родитель
        parent::__construct();


        /*
        |--------------------------------------------------------------------------------------
        | Назначаем права
        |--------------------------------------------------------------------------------------
        |
        */
        Permissions::add('settings', ['settings_view', 'settings_edit'], 'mdi mdi-cogs', 70);


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
        $Template->_load(DASHBOARD_DIR . '/app/modules/settings/i18n/' . $_SESSION['current_language'] . '.ini', 'name');


        /*
        |--------------------------------------------------------------------------------------
        | Подгружаем файл переводов (Права)
        |--------------------------------------------------------------------------------------
        |
        */
        $Template->_load(DASHBOARD_DIR . '/app/modules/settings/i18n/' . Session::getvar('current_language') . '.ini', 'permissions');


        /*
        |--------------------------------------------------------------------------------------
        | Добавляем подменю навигации
        |--------------------------------------------------------------------------------------
        |
        */
        Navigation::add(
            30,
            $Template->_get('settings_menu_name'),
            'mdi mdi-cogs',
            ABS_PATH . 'settings',
            'settings',
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_SETTINGS,
            '',
            '',
            false
        );
    }
}
