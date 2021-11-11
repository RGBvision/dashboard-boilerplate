<?php


class ModuleGroups extends Module
{

    public static $version = '1.0';

    public static $date = '01.11.2021';

    public static $_moduleName = 'groups';


    public function __construct()
    {

        parent::__construct();


        
        $_permissions = array('groups_view', 'groups_edit', 'groups_delete');
        Permission::add('groups', $_permissions, 'mdi mdi-key-chain', 4010);


        
        $Template = Template::getInstance();


        
        $Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'name');


        
        $Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'permissions');


        
        Navigation::add(
            10,
            $Template->_get('groups_menu_name'),
            'mdi mdi-key-chain',
            'groups',
            self::$_moduleName,
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_SETTINGS,
            '',
            '',
            false
        );
    }
}