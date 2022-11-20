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
     * Module ID
     */
    const ID = 'users';

    /**
     * Module version
     */
    const VERSION = '4.0';

    /**
     * Module release date
     */
    const DATE = '20.11.2022';

    /**
     * Module icon
     */
    const ICON = 'mdi mdi-account-multiple-outline';

    /**
     * Module permissions
     */
    const PERMISSIONS = ['users_view', 'users_add', 'users_edit', 'users_delete'];

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

        // Module permissions
        Permissions::add(static::ID, static::PERMISSIONS, static::ICON, 1050);

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load($this->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'module');

        // Add navigation entry
        Navigation::add(
            10,
            $Template->_get('users_submenu_name'),
            static::ICON,
            ABS_PATH . static::ID,
            static::ID,
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_CONTROL,
            '',
            '',
            false
        );
    }

}
