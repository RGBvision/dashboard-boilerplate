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

class LoginModule extends Module
{

    /**
     * Module ID
     */
    const ID = 'login';

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
    const ICON = 'mdi mdi-email-outline';

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

        // Router aliases
        Router::addAlias(ABS_PATH . 'logout', static::ID, 'logout');

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load($this->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'module');
        $Template->_load($this->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        // Add navigation entry
        Navigation::add(
            500,
            $Template->_get('login_logout'),
            'mdi mdi-logout',
            ABS_PATH . 'logout',
            'logout',
            Navigation::USER,
            1,
            '',
            '',
            false
        );

    }

}