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

class EventLogModule extends Module
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
    public static string $moduleName = 'event_log';

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

        // Module permissions
        Permission::add('event_log', ['event_log_view', 'event_log_edit', 'event_log_delete'], 'mdi mdi-script-text-outline', 5010);

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/event_log/i18n/' . Session::getvar('current_language') . '.ini', 'name');
        $Template->_load(DASHBOARD_DIR . '/app/modules/event_log/i18n/' . Session::getvar('current_language') . '.ini', 'permissions');

        // Add navigation entry
        Navigation::add(
            10,
            $Template->_get('event_log_menu_name'),
            'mdi mdi-script-text-outline',
            ABS_PATH . 'event_log',
            'event_log',
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_INFO,
            '',
            '',
            false
        );

    }

}