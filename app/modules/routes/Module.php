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

class RoutesModule extends Module
{
    // Версия модуля
    public static string $version = '4.0';

    // Дата
    public static string $date = '07.10.2022';

    // Системное имя модуля
    public static string $moduleName = 'routes';


    public function __construct()
    {
        
        // Родитель
        parent::__construct();

        // Назначаем права
        Permission::add('routes', ['routes_view', 'routes_edit', 'routes_delete'], 'mdi mdi-routes', 10);

        // Инстанс Smarty
        $Template = Template::getInstance();

        // Подгружаем файл переводов
        $Template->_load(DASHBOARD_DIR . '/app/modules/routes/i18n/' . Session::getvar('current_language') . '.ini', 'name');

        // Подгружаем файл переводов (Права)
        $Template->_load(DASHBOARD_DIR . '/app/modules/routes/i18n/' . Session::getvar('current_language') . '.ini', 'permissions');

        // Добавляем меню навигации
        Navigation::add(
            40,
            $Template->_get('routes_menu_name'),
            'mdi mdi-routes',
            'routes',
            'routes',
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_SETTINGS,
            '',
            '',
            false
        );
    }

}