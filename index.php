<?php

/**
 * This file is part of the dashboard.rgbvision.net package.
 *
 * (c) Alex Graham <contact@rgbvision.net>
 *
 * @package    dashboard.rgbvision.net
 * @author     Alex Graham <contact@rgbvision.net>
 * @copyright  Copyright 2017-2021, Alex Graham
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    3.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

define('START_CP', microtime());
define('START_MEMORY', memory_get_usage());

// System initialisation
require_once __DIR__ . '/system/init.php';

// Run hook after system initialised
Hooks::action('system_after_init');

// API call handler
if (($path = Request::getPath()) && (strpos($path, ABS_PATH . 'api/') === 0)) {
    ApiRouter::init('API', DASHBOARD_DIR . '/app/api/API.php', str_replace(ABS_PATH . 'api/', '', $path));
    ApiRouter::response(ApiRouter::execute());
}

// Template engine instance
$Template = Template::getInstance();

// Define default template directory if not set
if (!defined('TPL_DIR')) {
    define('TPL_DIR', 'default');
}

// Set template directory
$Template->__set('template_dir', DASHBOARD_DIR . '/app/templates/' . TPL_DIR . '/');

// Global i18n
$Template->_load(DASHBOARD_DIR . '/system/i18n/' . Session::getvar('current_language') . '.ini');

// Execute router
Router::execute(preg_replace('/[^a-zA-Z0-9_\/]/', '', Request::getPath()));

// Display «No permission» page if user has no permission to access requested page
if (defined('NO_PERMISSION')) {
    Request::redirect('/errors/denied');
}

// Disable browser caching
Response::setHeader('Cache-Control: no-store, no-cache, must-revalidate');
Response::setHeader('Expires: ' . gmdate('r'));

// Use GZIP compression if accepted
if (GZIP_COMPRESSION && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}

// Run hook before render
Hooks::action('system_pre_render');

// Set base template file
$base_template = (Request::request('only') && (int)Request::request('only') === 1) ? 'only.tpl' : 'index.tpl';

// Push all data to template engine
$Template

    ->assign('APP_NAME', APP_NAME)
    ->assign('APP_BUILD', APP_BUILD)

    ->assign('_is_ajax', Request::isAjax())

    ->assign('dependencies', Dependencies::get())
    ->assign('injections', Injections::get())

    ->assign('accept_lang', Session::getvar('accept_langs'))
    ->assign('current_language', Session::getvar('current_language'))
    ->assign('sidebar_headers', Navigation::$sidebar_headers)
    ->assign('sidebar_menu_items', Navigation::get(Navigation::SIDEBAR))
    ->assign('user_menu_items', Navigation::get(Navigation::USER))

    ->assign('styles_tpl', $Template->fetch('styles.tpl'))
    ->assign('sidebar_tpl', $Template->fetch('sidebar.tpl'))
    ->assign('user_tpl', $Template->fetch('user.tpl'))
    ->assign('header_tpl', $Template->fetch('header.tpl'))
    ->assign('header_addons_tpl', $Template->fetch('header_addons.tpl'))
    ->assign('breadcrumb_tpl', $Template->fetch('breadcrumbs.tpl'))
    ->assign('footer_tpl', $Template->fetch('footer.tpl'))
    ->assign('scripts_tpl', $Template->fetch('scripts.tpl'));

// Render
$Template->display($base_template);
$render = ob_get_clean();

// Run hook after render
Hooks::action('system_post_render');

// Display result
echo Html::output($render);