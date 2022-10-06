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

const APP_NAME = 'RGB.admin';
const APP_VERSION = '4.0';
const APP_BUILD = '4.0.0-alpha-1';

define('APP_INFO', APP_NAME . ' ' . APP_VERSION . ' &copy; <a href="https://rgbvision.net">RGBvision</a> ' . date('Y'));

const DS = DIRECTORY_SEPARATOR;

$config_defaults = [];

// Environment
$config_defaults['SYSTEM_ENVIRONMENT'] = [
    'DEFAULT' => 'public',
    'TYPE' => 'dropdown',
    'VARIANT' => 'development,demo,public',
];


$config_defaults['LOGIN_USER_IP'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

// Password pepper
$config_defaults['PWD_PEPPER'] = [
    'DEFAULT' => '',
    'TYPE' => 'readonly',
    'VARIANT' => '',
];

// Auth token lifetime
$config_defaults['AUTH_TOKEN_LIFETIME'] = [
    'DEFAULT' => 60 * 60,
    'TYPE' => 'integer',
    'VARIANT' => '',
];

// Refresh token lifetime
$config_defaults['REFRESH_TOKEN_LIFETIME'] = [
    'DEFAULT' => 60 * 60 * 24 * 7,
    'TYPE' => 'integer',
    'VARIANT' => '',
];

$config_defaults['API_URI_PREFIX'] = [
    'DEFAULT' => 'api',
    'TYPE' => 'string',
    'VARIANT' => '',
];

// Temporary directory
$config_defaults['TEMP_DIR'] = [
    'DEFAULT' => '/tmp',
    'TYPE' => 'folder',
    'VARIANT' => '',
];

$config_defaults['API_DIR'] = [
    'DEFAULT' => '/app/api',
    'TYPE' => 'folder',
    'VARIANT' => '',
];

$config_defaults['MODULES_DIR'] = [
    'DEFAULT' => '/app/modules',
    'TYPE' => 'folder',
    'VARIANT' => '',
];

$config_defaults['CLASSES_DIR'] = [
    'DEFAULT' => '/app/classes',
    'TYPE' => 'folder',
    'VARIANT' => '',
];

// Attachments directory
$config_defaults['ATTACH_DIR'] = [
    'DEFAULT' => '/tmp/attachments',
    'TYPE' => 'folder',
    'VARIANT' => '',
];

// Uploads directory
$config_defaults['UPLOAD_DIR'] = [
    'DEFAULT' => '/uploads',
    'TYPE' => 'folder',
    'VARIANT' => '',
];

// Sessions
$config_defaults['SESSION_DIR'] = [
    'DEFAULT' => '/tmp/sessions',
    'TYPE' => 'folder',
    'VARIANT' => '',
];

$config_defaults['SESSION_SAVE_HANDLER'] = [
    'DEFAULT' => 'db',
    'TYPE' => 'dropdown',
    'VARIANT' => 'db,files,native',
];

$config_defaults['SESSION_LIFETIME'] = [
    'DEFAULT' => 60 * 60 * 24 * 14,
    'TYPE' => 'integer',
    'VARIANT' => '',
];

// Cookie
$config_defaults['COOKIE_DOMAIN'] = [
    'DEFAULT' => '',
    'TYPE' => 'string',
    'VARIANT' => '',
];

$config_defaults['COOKIE_LIFETIME'] = [
    'DEFAULT' => 60 * 60 * 24 * 14,
    'TYPE' => 'integer',
    'VARIANT' => '',
];

// Smarty
$config_defaults['SMARTY_COMPILE_CHECK'] = [
    'DEFAULT' => true,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['SMARTY_USE_SUB_DIRS'] = [
    'DEFAULT' => true,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['CACHE_DOC_TPL'] = [
    'DEFAULT' => true,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['CACHE_LIFETIME'] = [
    'DEFAULT' => 60 * 60 * 24 * 14,
    'TYPE' => 'integer',
    'VARIANT' => '',
];

$config_defaults['SYSTEM_CACHE_LIFETIME'] = [
    'DEFAULT' => 0,
    'TYPE' => 'integer',
    'VARIANT' => '',
];

// Debugging
$config_defaults['PHP_DEBUGGING'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['SELF_ERROR'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['SMARTY_DEBUGGING'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['SQL_DEBUGGING'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['SQL_ERRORS_STOP'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['SEND_SQL_ERROR'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['SQL_PROFILING'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['PROFILING'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

// Output
$config_defaults['HTML_COMPRESSION'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['GZIP_COMPRESSION'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['OUTPUT_EXPIRE'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

$config_defaults['OUTPUT_EXPIRE_OFFSET'] = [
    'DEFAULT' => 60 * 60,
    'TYPE' => 'integer',
    'VARIANT' => '',
];

// Updates
$config_defaults['CHECK_VERSION'] = [
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => '',
];

// ---------------------------------------------------------------
define('DASHBOARD_CONFIG_DEFAULTS', $config_defaults);

if (file_exists(DASHBOARD_DIR . '/configs/environment.php')) {
    include(DASHBOARD_DIR . '/configs/environment.php');
}

foreach ($config_defaults as $key => $value) {
    if (!defined($key)) {
        define($key, $value['DEFAULT']);
    }
}

unset($config_defaults);

if (!defined('TIMEZONE')) {
    define('TIMEZONE', 'Europe/Moscow');
}

@date_default_timezone_set(TIMEZONE);

ini_set('arg_separator.output', '&');
ini_set('session.cache_limiter', 'none');
ini_set('session.cookie_lifetime', COOKIE_LIFETIME);
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid', 0);
ini_set('url_rewriter.tags', '');