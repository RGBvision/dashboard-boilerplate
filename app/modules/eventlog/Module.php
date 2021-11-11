<?php


class ModuleEventlog extends Module
{

    public static $version = '1.0';

    public static $date = '07.11.2021';

    public static $_moduleName = 'eventlog';


    public function __construct()
    {

        parent::__construct();

        $_permissions = ['eventlog_view', 'eventlog_edit', 'eventlog_delete'];
        Permission::add('eventlog', $_permissions, 'mdi mdi-script-text-outline', 5010);

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/eventlog/i18n/' . Session::getvar('current_language') . '.ini', 'name');

        $Template->_load(DASHBOARD_DIR . '/app/modules/eventlog/i18n/' . Session::getvar('current_language') . '.ini', 'permissions');

        Navigation::add(
            10,
            $Template->_get('eventlog_menu_name'),
            'mdi mdi-script-text-outline',
            'eventlog',
            'eventlog',
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_INFO,
            '',
            '',
            false
        );
    }

}