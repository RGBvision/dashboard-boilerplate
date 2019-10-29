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

defined('CP_DIR') || die();

define('CP_LOGIN', true);

//--- Smarty instance
$Smarty = Tpl::getInstance();

//--- Set Smarty template folder
$Smarty->__set('template_dir', CP_DIR . '/system/view/');

//--- i18n
$Smarty->_load(CP_DIR . '/system/i18n/' . Session::getvar('current_language') . '.ini');

//--- Disable caching
Request::setHeaders('Cache-Control: no-store, no-cache, must-revalidate');
Request::setHeaders('Expires: ' . date('r'));

//--- Login
if (!empty(Request::post('action')) && Request::post('action') === 'loginform') {
    Login::_login();
}

//--- Logout
if (!empty(Request::get('action')) && Request::get('action') === 'logout') {
    Login::_logout();
}

//--- Reminder
if (Request::post('action') && Request::post('action') === 'reminderform') {
    Login::_reminder();
}

//--- If Authorized (Session or Cookie)
if (Auth::authSessions() || Auth::authCookie()) {
    Request::redirect('/');
}

//--- Enable GZIP compression
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && GZIP_COMPRESSION) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}

//--- Execute hook before render
Hooks::action('admin_pre_login_render');

//--- Choose template
$Tpl_out = 'login.tpl';

//--- Pass variables to template engine
$Smarty
    ->assign('ABS_PATH', ABS_PATH)
    ->assign('loading_tpl', $Smarty->fetch('loading.tpl'))
    ->assign('footer_tpl', $Smarty->fetch('footer.tpl'));

//--- Render template
$Smarty->display($Tpl_out);
$render = ob_get_clean();

//--- Execute hook after render
Hooks::action('admin_post_login_render');

//--- Output result
echo Html::output($render);