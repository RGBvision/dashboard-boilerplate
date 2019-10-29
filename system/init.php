<?php

/**
 * This file is part of the RGB.dashboard package.
 *
 * (c) Alexey Graham <contact@rgbvision.net>
 *
 * @package    RGB.dashboard
 * @author     Alexey Graham <contact@rgbvision.net>
 * @copyright  2017-2019 RGBvision
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    1.7
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

//--- Define the root folder of the system
define('CP_DIR', str_replace("\\", '/', dirname(__DIR__)));

//--- PHP version requirements
if (PHP_VERSION_ID < 70100) {
    exit ('RGB.dashboard require PHP 7.1 or higher.');
}

//--- Load DB config
if (!file_exists(CP_DIR . '/configs/db.config.php') || !@filesize(CP_DIR . '/configs/db.config.php')) {
	header('Location:install/index.php');
	exit;
}

//--- Include system core class
include CP_DIR . '/system/Core.php';

//--- Start system
Core::init();

//--- Restore auth if user logged in
Auth::authRestore();

//--- i18n
i18n::init('/system/i18n/', Session::getvar('current_language'));

//--- Load core components
Load::addDirectory(CP_DIR . '/system/core/');

//--- Load modules
Load::addModules(CP_DIR);