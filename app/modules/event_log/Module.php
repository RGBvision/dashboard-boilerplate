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
     * Module ID
     */
    const ID = 'event_log';

    /**
     * Module version
     */
    const VERSION = '4.0';

    /**
     * Module release date
     */
    const DATE = '07.10.2022';

    /**
     * Module icon
     */
    const ICON = 'mdi mdi-script-text-outline';

    /**
     * Module permissions
     */
    const PERMISSIONS = ['event_log_view', 'event_log_edit', 'event_log_delete'];

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

        // Module permissions
        Permission::add(static::ID, static::PERMISSIONS, static::ICON, 5010);

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load($this->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'module');

        // Add navigation entry
        Navigation::add(
            10,
            $Template->_get('event_log_menu_name'),
            static::ICON,
            ABS_PATH . static::ID,
            static::ID,
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_INFO,
            '',
            '',
            false
        );

    }

}