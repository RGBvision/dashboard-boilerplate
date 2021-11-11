<?php


class ModuleUsers extends Module
{

    public static $version = '1.0';

    public static $date = '01.11.2021';

    public static $_moduleName = 'users';


    public function __construct()
    {

        parent::__construct();


        
        $_permissions = array('users_view', 'users_add', 'users_edit', 'users_delete');
        Permission::add('users', $_permissions, 'mdi mdi-account-multiple-outline', 50);


        
        $Template = Template::getInstance();


        
        $Template->_load(DASHBOARD_DIR . '/app/modules/users/i18n/' . Session::getvar('current_language') . '.ini', 'name');


        
        $Template->_load(DASHBOARD_DIR . '/app/modules/users/i18n/' . Session::getvar('current_language') . '.ini', 'permissions');

        
        Navigation::add(
            10,
            $Template->_get('users_submenu_name'),
            'mdi mdi-account-multiple-outline',
            'users',
            self::$_moduleName,
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_CONTROL,
            '',
            '',
            false
        );
    }

}
