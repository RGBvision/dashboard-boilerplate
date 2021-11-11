<?php


class ModuleProfile extends Module
{

    public static $version = '1.0';

    public static $date = '01.11.2021';

    public static $_moduleName = 'profile';


    public function __construct()
    {

        parent::__construct();


        
        $_permissions = ['profile_view'];
        Permission::add('profile', $_permissions, 'mdi mdi-account-circle-outline', 10);


        
        $Template = Template::getInstance();


        
        $Template->_load(DASHBOARD_DIR . '/app/modules/profile/i18n/' . $_SESSION['current_language'] . '.ini', 'name');


        
        $Template->_load(DASHBOARD_DIR . '/app/modules/profile/i18n/' . $_SESSION['current_language'] . '.ini', 'permissions');


    }
}