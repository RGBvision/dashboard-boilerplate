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
 * @version    3.2
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ModuleDashboard extends Module
{

    /**
     * @var string Module version
     */
    public static string $version = '3.2';

    /**
     * @var string Module release date
     */
    public static string $date = '01.11.2021';

    /**
     * @var string Module system name
     */
    public static string $moduleName = 'dashboard';

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

        // Module permissions
        Permission::add('dashboard', ['dashboard_view', 'dashboard_backup_db', 'dashboard_clear_cache'], 'mdi mdi-view-dashboard', 1);

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/dashboard/i18n/' . Session::getvar('current_language') . '.ini', 'name');
        $Template->_load(DASHBOARD_DIR . '/app/modules/dashboard/i18n/' . Session::getvar('current_language') . '.ini', 'permissions');

        // Add navigation entry
        Navigation::add(
            10,
            $Template->_get('dashboard_menu_name'),
            'mdi mdi-view-dashboard',
            ABS_PATH . 'dashboard',
            'dashboard',
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_MAIN,
            '',
            '',
            false
        );

    }

}