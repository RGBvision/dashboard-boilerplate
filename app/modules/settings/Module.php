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

class SettingsModule extends Module
{

    /**
     * Module ID
     */
    const ID = 'settings';

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
    const ICON = 'mdi mdi-cogs';

    /**
     * Module permissions
     */
    const PERMISSIONS = ['settings_view', 'settings_edit'];

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        Permissions::add(static::ID, static::PERMISSIONS, static::ICON, 70);

        $Template = Template::getInstance();

        $Template->_load($this->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'module');

        Navigation::add(
            30,
            $Template->_get('settings_menu_name'),
            static::ICON,
            ABS_PATH . static::ID,
            static::ID,
            Navigation::SIDEBAR,
            Navigation::SIDEBAR_SETTINGS,
            '',
            '',
            false
        );
    }
}
