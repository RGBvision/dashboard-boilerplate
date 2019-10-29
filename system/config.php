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

define('APP_NAME', 'RGB.dashboard');
define('APP_VERSION', '1.7');
define('APP_BUILD', '1.7.4712');
define('APP_INFO', APP_NAME. ' ' . APP_VERSION . ' &copy; 2017-' . date('Y'));

define('DS', DIRECTORY_SEPARATOR);

$config_defaults = array();

// ---------------------------------------------------------------
// Environment
// ---------------------------------------------------------------
$config_defaults['CP_ENVIRONMENT'] = array(
	'DEFAULT' => 'public',
	'TYPE' => 'dropdown',
	'VARIANT' => 'development,demo,public'
);

$config_defaults['LOGIN_USER_IP'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['MEMORY_LIMIT_PANIC'] = array(
	'DEFAULT' => -1,
	'TYPE' => 'integer',
	'VARIANT' => ''
);

// ---------------------------------------------------------------
// Temporary directory
// ---------------------------------------------------------------
$config_defaults['TEMP_DIR'] = array(
	'DEFAULT' => '/tmp',
	'TYPE' => 'folder',
	'VARIANT' => ''
);

// ---------------------------------------------------------------
// Attachments directory
// ---------------------------------------------------------------
$config_defaults['ATTACH_DIR'] = array(
	'DEFAULT' => '/tmp/attachments',
	'TYPE' => 'folder',
	'VARIANT' => ''
);

// ---------------------------------------------------------------
// Uploads directory
// ---------------------------------------------------------------
$config_defaults['UPLOAD_DIR'] = array(
	'DEFAULT' => '/uploads',
	'TYPE' => 'folder',
	'VARIANT' => ''
);

// ---------------------------------------------------------------
// Sessions
// ---------------------------------------------------------------
$config_defaults['SESSION_DIR'] = array(
	'DEFAULT' => '/tmp/sessions',
	'TYPE' => 'folder',
	'VARIANT' => ''
);

$config_defaults['SESSION_SAVE_HANDLER'] = array(
	'DEFAULT' => 'db',
	'TYPE' => 'dropdown',
	'VARIANT' => 'db,files,memcached,memcache,redis,native'
);

$config_defaults['SESSION_LIFETIME'] = array(
	'DEFAULT' => 60 * 60 * 24 * 14,
	'TYPE' => 'integer',
	'VARIANT' => ''
);


// ---------------------------------------------------------------
// Cookie
// ---------------------------------------------------------------
$config_defaults['COOKIE_DOMAIN'] = array(
	'DEFAULT' => '',
	'TYPE' => 'string',
	'VARIANT' => ''
);

$config_defaults['COOKIE_LIFETIME'] = array(
	'DEFAULT' => 60 * 60 * 24 * 14,
	'TYPE' => 'integer',
	'VARIANT' => ''
);

// ---------------------------------------------------------------
// Smarty
// ---------------------------------------------------------------
$config_defaults['SMARTY_COMPILE_CHECK'] = array(
	'DEFAULT' => true,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['SMARTY_USE_SUB_DIRS'] = array(
	'DEFAULT' => true,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['CACHE_DOC_TPL'] = array(
	'DEFAULT' => true,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['CACHE_LIFETIME'] = array(
	'DEFAULT' => 60 * 60 * 24 * 14,
	'TYPE' => 'integer',
	'VARIANT' => ''
);

$config_defaults['SYSTEM_CACHE_LIFETIME'] = array(
	'DEFAULT' => 0,
	'TYPE' => 'integer',
	'VARIANT' => ''
);

// ---------------------------------------------------------------
// Debugging
// ---------------------------------------------------------------
$config_defaults['PHP_DEBUGGING'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['SELF_ERROR'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['SMARTY_DEBUGGING'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['SQL_DEBUGGING'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['SQL_ERRORS_STOP'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['SEND_SQL_ERROR'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['SQL_PROFILING'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['PROFILING'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);


// ---------------------------------------------------------------
// Compress
// ---------------------------------------------------------------
$config_defaults['HTML_COMPRESSION'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['GZIP_COMPRESSION'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['OUTPUT_EXPIRE'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

$config_defaults['OUTPUT_EXPIRE_OFFSET'] = array(
	'DEFAULT' => 60 * 60,
	'TYPE' => 'integer',
	'VARIANT' => ''
);

$config_defaults['USE_MEMCACHED'] = array(
    'DEFAULT' => false,
    'TYPE' => 'bool',
    'VARIANT' => ''
);

$config_defaults['MEMCACHED_SERVER'] = array(
	'DEFAULT' => 'localhost',
	'TYPE' => 'string',
	'VARIANT' => ''
);

$config_defaults['MEMCACHED_PORT'] = array(
	'DEFAULT' => '11211',
	'TYPE' => 'string',
	'VARIANT' => ''
);

$config_defaults['CHECK_VERSION'] = array(
	'DEFAULT' => false,
	'TYPE' => 'bool',
	'VARIANT' => ''
);

define('CP_CONFIG_DEFAULTS', $config_defaults);

if (file_exists(CP_DIR . '/configs/environment.php')) {
    include(CP_DIR . '/configs/environment.php');
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

ini_set('arg_separator.output', '&amp;');
ini_set('session.cache_limiter', 'none');
ini_set('session.cookie_lifetime', COOKIE_LIFETIME);
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid', 0);
ini_set('url_rewriter.tags', '');