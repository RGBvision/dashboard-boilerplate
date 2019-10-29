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

define('START_CP', microtime());
define('START_MEMORY', memory_get_usage());

//--- Initialization
require_once __DIR__ . '/system/init.php';

//--- Login / logout
if (Request::getPath() === '/login') {
    include CP_DIR . '/system/login.php';
    exit;
}

//--- Check user authorization
Auth::authCheck();

//--- Load user settings if authorized or redirect to Login page
if (!Auth::authCheckPermission()) {
    Request::redirect('/login');
} else {
    Settings::loadUserSettings();
}

//--- Execute hook after initialization
Hooks::action('admin_after_init');

//--- Set permissions
$_permissions = array('admin_panel', 'admin_menu');
Permission::add('admin', $_permissions, 'sli sli-lock-lock-shield', 0);

//--- Smarty instance
$Smarty = Tpl::getInstance();

//--- Set Smarty template folder
$Smarty->__set('template_dir', CP_DIR . '/system/view/');

//--- i18n
$Smarty->_load(CP_DIR . '/system/i18n/' . Session::getvar('current_language') . '.ini');

//--- Set default page if request has no route
if (Request::getPath() === '/') {
    Request::redirect('/dashboard');
}

//--- Router initialization
Router::init(Request::getPath());

//--- Check user permissions to view the module
Permission::checkAccess(Router::getId() . '_view');

//--- Show "No permission" message if access denied
if (defined('NO_PERMISSION')) {
    Router::reinit('/errors/denied');
}

//--- Search for requested module
Router::execute();

//--- Disable caching
Request::setHeaders('Cache-Control: no-store, no-cache, must-revalidate');
Request::setHeaders('Expires: ' . date('r'));

//--- Enable GZIP compression
if (GZIP_COMPRESSION && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}

//--- Execute hook before render
Hooks::action('admin_pre_render');

//--- Choose template
$Tpl_out = (Request::request('only') && (int)Request::request('only') === 1)
    ? 'only.tpl'
    : 'index.tpl';

//--- Applying dependencies
$dependencies = Dependencies::get();

//--- Pass variables to template engine
$Smarty
    ->assign('ABS_PATH', ABS_PATH)
    ->assign('show_navigation', Permission::check('admin_menu'))
    ->assign('_is_ajax', Request::isAjax())
    ->assign('dependencies', $dependencies)
    ->assign('left_headers', Navigation::$left_headers)
    ->assign('left_menu_items', Navigation::show(1))
    ->assign('header_tpl', $Smarty->fetch('header.tpl'))
    ->assign('user_tpl', $Smarty->fetch('user.tpl'))
    ->assign('right_header_tpl', $Smarty->fetch('right_header.tpl'))
    ->assign('breadcrumb_tpl', $Smarty->fetch('breadcrumbs.tpl'))
    ->assign('loading_tpl', $Smarty->fetch('loading.tpl'))
    ->assign('left_menu_tpl', $Smarty->fetch('left_menu.tpl'))
    ->assign('scripts_tpl', $Smarty->fetch('scripts.tpl'))
    ->assign('footer_tpl', $Smarty->fetch('footer.tpl'))
    ->assign('debug_data', true)
    ->assign('debugs', $Smarty->fetch('debugs.tpl'));

//--- Render template
$Smarty->display($Tpl_out);
$render = ob_get_clean();

//--- Debug information
if (defined('PROFILING') && (PROFILING === true)) {
    $render .= Debug::getStats(1, 1, 1);
}

//--- Execute hook after render
Hooks::action('admin_post_render');

//--- Output result
echo Html::output($render);