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

class UsersModule extends Module
{

    /**
     * @var string Module version
     */
    public static string $version = '4.0';

    /**
     * @var string Module release date
     */
    public static string $date = '07.10.2022';

    /**
     * @var string Module system name
     */
    public static string $moduleName = 'users';

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

        // Module permissions
        Permission::add('users', ['users_view', 'users_add', 'users_edit', 'users_delete'], 'mdi mdi-account-multiple-outline', 50);

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/users/i18n/' . Session::getvar('current_language') . '.ini', 'name');
        $Template->_load(DASHBOARD_DIR . '/app/modules/users/i18n/' . Session::getvar('current_language') . '.ini', 'permissions');

        // Add navigation entry
        Navigation::add(
            10,
            $Template->_get('users_submenu_name'),
            'mdi mdi-account-multiple-outline',
            ABS_PATH . 'users',
            'users',
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_CONTROL,
            '',
            '',
            false
        );
    }

}
