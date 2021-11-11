<?php


class ModuleDashboard extends Module
{

    public static $version = '1.0';

    public static $date = '01.11.2021';

    public static $_moduleName = 'dashboard';


    public function __construct()
    {

        parent::__construct();

        $_permissions = ['dashboard_view'];
        Permission::add('dashboard', $_permissions, 'mdi mdi-view-dashboard', 10);

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/dashboard/i18n/' . Session::getvar('current_language') . '.ini', 'name');

        $Template->_load(DASHBOARD_DIR . '/app/modules/dashboard/i18n/' . Session::getvar('current_language') . '.ini', 'permissions');

        Navigation::add(
            10,
            $Template->_get('dashboard_menu_name'),
            'mdi mdi-view-dashboard',
            'dashboard',
            'dashboard',
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_MAIN,
            '',
            '',
            false
        );
    }

}