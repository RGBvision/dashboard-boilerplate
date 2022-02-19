<?php


class ModuleRoutes extends Module
{
    // Версия модуля
    public static string $version = '1.0';

    // Дата
    public static string $date = '22.11.2021';

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