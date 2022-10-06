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

class ProfileModule extends Module
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
    public static string $moduleName = 'profile';

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

        // Router aliases
        Router::addAlias(ABS_PATH . 'profile/set/(.*)/(.*)', self::$moduleName, 'settings_set');

        // Module permissions
        Permission::add('profile', ['profile_view'], 'mdi mdi-account-circle-outline', 10);

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/profile/i18n/' . Session::getvar('current_language') . '.ini', 'name');

        // Add navigation entry
        Navigation::add(
            10,
            $Template->_get('profile_menu_name'),
            'mdi mdi-account-circle-outline',
            ABS_PATH . 'profile',
            'profile',
            Navigation::USER,
            1,
            '',
            '',
            false
        );

    }

}