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
 * @version    3.3
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ModuleEventlog extends Module
{

    /**
     * @var string Module version
     */
    public static string $version = '1.0';

    /**
     * @var string Module release date
     */
    public static string $date = '10.11.2021';

    /**
     * @var string Module system name
     */
    public static string $moduleName = 'eventlog';

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

        // Module permissions
        Permission::add('eventlog', ['eventlog_view', 'eventlog_edit', 'eventlog_delete'], 'mdi mdi-script-text-outline', 5010);

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/eventlog/i18n/' . Session::getvar('current_language') . '.ini', 'name');
        $Template->_load(DASHBOARD_DIR . '/app/modules/eventlog/i18n/' . Session::getvar('current_language') . '.ini', 'permissions');

        // Add navigation entry
        Navigation::add(
            10,
            $Template->_get('eventlog_menu_name'),
            'mdi mdi-script-text-outline',
            ABS_PATH . 'eventlog',
            'eventlog',
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_INFO,
            '',
            '',
            false
        );

    }

}