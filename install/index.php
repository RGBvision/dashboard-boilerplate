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
 * @since      File available since Release 3.0
 */

// Check PHP version
if (PHP_VERSION_ID < 80101) {
    exit ('This application require PHP 8.1.1 or higher.');
}

// Dashboard root directory
define('DASHBOARD_DIR', str_replace("\\", '/', dirname(__DIR__)));

// Define ABS_PATH with system files absolute path.
$abs_path = dirname(
    ((strpos($_SERVER['PHP_SELF'], $_SERVER['SCRIPT_NAME']) === false) && (PHP_SAPI === 'cgi'))
        ? $_SERVER['PHP_SELF']
        : $_SERVER['SCRIPT_NAME']);

if (defined('DASHBOARD_DIR')) {
    $abs_path = dirname($abs_path);
}

define('ABS_PATH', rtrim(str_replace("\\", "/", $abs_path), '/') . '/');

// Core class
include DASHBOARD_DIR . '/system/Core.php';

require_once DASHBOARD_DIR . '/system/loader/Loader.php';

Loader::init();

// EasyDB
Loader::addDirectory(DASHBOARD_DIR . '/libraries/db/');

// SMARTY
require_once(DASHBOARD_DIR . '/libraries/Smarty/bootstrap.php');

// Drivers
Loader::addClasses([
    'DB' => DASHBOARD_DIR . '/system/drivers/db/DB.php',
    'i18n' => DASHBOARD_DIR . '/system/drivers/language/i18n.php',
    'Template' => DASHBOARD_DIR . '/system/drivers/template/Template.php',
]);

// Core classes
Loader::addDirectory(DASHBOARD_DIR . '/system/core/');

// Common classes
Loader::addDirectory(DASHBOARD_DIR . '/system/common/');

// Installer i18n
i18n::init(DASHBOARD_DIR . '/install/i18n/', Core::getBrowserLang());

if (!Dir::create(DASHBOARD_DIR . '/install/tmp/')) {
    exit ('Error: Can\'t create temp directory "' . DASHBOARD_DIR . '/install/tmp/".');
}

const TEMP_DIR = '/install/tmp';
const SESSION_DIR = '/install/tmp/sessions';
const SMARTY_COMPILE_CHECK = true;
const SMARTY_USE_SUB_DIRS = true;
const SMARTY_DEBUGGING = false;

// Minimum system requirements
const PHP_version = '8.1.1';
const MySQL_version = '5.7.0';
const Data_limit = '2'; // Mb
const TIME_limit = '30'; // Sec
const DISC_space = '75'; // Mb
const RAM_space = '32M'; // Mb

// Template engine instance
$Template = Template::getInstance();

// Installer i18n
$Template->_load(DASHBOARD_DIR . '/install/i18n/' . i18n::$active_language . '.ini');

$Template->assign('current_language', i18n::$active_language);
$Template->display(DASHBOARD_DIR . '/install/view/index.tpl');

if (!empty($_POST['action']) && $_POST['action'] === 'install') {

    $config = [
        'dbengine' => $_POST['dbengine'],
        'dbhost' => $_POST['dbhost'],
        'dbuser' => $_POST['dbuser'],
        'dbpass' => $_POST['dbpass'],
        'dbname' => $_POST['dbname'],
        'dbport' => (int)$_POST['dbport'],
    ];

    // Подключение к БД
    DB::init($config);

    // Если подключение прошло успешно, то можно создать таблицу и записать конфигурацию. В противном случае пользователь получит 503 ошибку.

    $query = '';

    switch ($config['dbengine']) {
        case "mysql":
            $query = File::getContents(__DIR__ . '/mysql.sql');
            break;
        case "postgresql":
            $query = File::getContents(__DIR__ . '/pgsql.sql');
            break;
    }

    if ($query) {
        DB::query($query);

        // Создание файла конфигурации подключения к БД
        File::putContents(__DIR__ . '/../configs/db.config.php', '<?php' . PHP_EOL . PHP_EOL . '$config = ' . var_export($config, true) . ';' . PHP_EOL);

        // Запуск приложения
        header('Location: /');
    }
}
