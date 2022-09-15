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
 * @version    3.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

// Dashboard root directory
define('DASHBOARD_DIR', str_replace("\\", '/', dirname(__DIR__)));

// Check PHP version
if (PHP_VERSION_ID < 80101) {
    exit ('This application require PHP 8.1.1 or higher.');
}

// DB connection config
if (!file_exists(DASHBOARD_DIR . '/configs/db.config.php') || !@filesize(DASHBOARD_DIR . '/configs/db.config.php')) {
    header('Location:install/index.php');
    exit;
}

// Core class
include DASHBOARD_DIR . '/system/Core.php';

// Core initialization
Core::init();

// Restore user authorization
Auth::authRestore();

// Load user settings if authorized
if (defined('UID')) {
    Settings::loadUserSettings(UID);
}

// System i18n
i18n::init(DASHBOARD_DIR . '/system/i18n/', Session::getvar('current_language'));

// App modules
Loader::addModules(DASHBOARD_DIR . '/app/modules/');

// App classes
Loader::addDirectory(DASHBOARD_DIR . '/app/classes/');