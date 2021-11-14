<?php

/**
 * This file is part of the dashboard.rgbvision.net package.
 *
 * (c) Alex Graham <contact@rgbvision.net>
 *
 * @package    dashboard.rgbvision.net
 * @author     Alex Graham <contact@rgbvision.net>
 * @copyright  Copyright 2017-2021, Alex Graham
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    3.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ModuleGroups extends Module
{

    public static string $version = '3.0';

    public static string $date = '01.11.2021';

    public static string $moduleName = 'groups';


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
            ABS_PATH . 'groups',
            'groups',
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_SETTINGS,
            '',
            '',
            false
        );
    }
}