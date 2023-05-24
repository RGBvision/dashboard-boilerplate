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

define('START_CP', microtime());
define('START_MEMORY', memory_get_usage());

// System initialisation
require_once __DIR__ . '/system/init.php';

// Restore user authorization
Auth::authRestore();

// Load user settings if authorized
if (defined('USERID')) {
    Settings::loadUserSettings(USERID);
}

// Run hook after system initialised
Hooks::action('system_after_init');

// API call handler
if (($path = Request::getPath()) && (str_starts_with($path, ABS_PATH . API_URI_PREFIX . '/'))) {
    ApiRouter::init('API', DASHBOARD_DIR . API_DIR . '/API.php', str_replace(ABS_PATH . API_URI_PREFIX . '/', '', $path));
    ApiRouter::response(ApiRouter::execute());
}

// Template engine instance
$Template = Template::getInstance();

$Template
    ->assign('DASHBOARD_DIR', DASHBOARD_DIR)
    ->assign('ABS_PATH', ABS_PATH)
    ->assign('API_PATH', ABS_PATH . API_URI_PREFIX)
    ->assign('APP_NAME', APP_NAME)
    ->assign('APP_BUILD', APP_BUILD);

// Define default template directory if not set
if (!defined('TPL_DIR')) {
    define('TPL_DIR', 'default');
}

// Set template directory
$Template->__set('template_dir', DASHBOARD_DIR . '/app/templates/' . TPL_DIR . '/');

// Global i18n
$Template->_load(DASHBOARD_DIR . '/system/i18n/' . Session::getvar('current_language') . '.ini');

// Execute router
Router::execute(Request::getPath());

// Display «No permission» page if user has no permission to access requested URL
if (defined('NO_PERMISSION')) {
    Request::redirect('/errors/denied');
}

// Run hook before render
Hooks::action('system_pre_render');

// Set base template file
$base_template = defined('CONTENT_ONLY') ? 'content_only.tpl' : 'index.tpl';

// Push all data to template engine
$Template

    ->assign('dependencies', Dependencies::get())
    ->assign('injections', Injections::get())

    ->assign('accept_lang', Session::getvar('accept_langs'))
    ->assign('current_language', Session::getvar('current_language'))
    ->assign('sidebar_rubrics', Navigation::RUBRICS)
    ->assign('sidebar_menu_items', Navigation::get(Navigation::SIDEBAR))
    ->assign('user_menu_items', Navigation::get(Navigation::USER))

    ->assign('styles_tpl', $Template->fetch('styles.tpl'))
    ->assign('sidebar_tpl', $Template->fetch('sidebar.tpl'))
    ->assign('user_tpl', $Template->fetch('user.tpl'))
    ->assign('header_tpl', $Template->fetch('header.tpl'))
    ->assign('header_addons_tpl', $Template->fetch('header_addons.tpl'))
    ->assign('breadcrumbs_tpl', $Template->fetch('breadcrumbs.tpl'))
    ->assign('footer_tpl', $Template->fetch('footer.tpl'))
    ->assign('scripts_tpl', $Template->fetch('scripts.tpl'));

// Render
$render = $Template->fetch($base_template);

// Run hook after render
Hooks::action('system_post_render');

// Display result
Html::output($render);