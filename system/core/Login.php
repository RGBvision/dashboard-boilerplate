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
 * @since      Class available since Release 1.0
 */

class Login
{
    public static function _login()
    {
        $Smarty = Tpl::getInstance();

        $user_login = Request::post('user_login');
        $user_password = Request::post('user_password');
        $keep_in = Request::post('keep_in') ? (int)Request::post('keep_in') : 0;

        if (!empty($user_login)
            AND !empty($user_password)
        ) {

            $login_res = Auth::userLogin($user_login, $user_password, LOGIN_USER_IP, $keep_in);

            if (0 === $login_res) {
                if (Session::checkvar('redirectlink')) {
                    Request::setHeader('Location: ' . Session::checkvar('redirectlink'));
                    Session::delvar('redirectlink');
                    Request::shutDown();
                }

                Request::setHeader('Location: /index.php');
                Request::shutDown();

            } elseif ($login_res === 4) {
                $delete = array('user_id', 'user_password', 'captcha_keystring');
                Session::delvar($delete);

                $Smarty->assign('error', $Smarty->_get('wrong_device'));
            } else {
                $delete = array('user_id', 'user_password', 'captcha_keystring');
                Session::delvar($delete);

                $Smarty->assign('error', $Smarty->_get('wrong_pass'));
            }
        }
    }

    public static function _logout()
    {
        Auth::userLogout();
        Request::setHeader('Location: /login');
        Request::shutDown();
    }

    public static function _reminder()
    {
        //--- ToDo: Make reminder function
    }
}