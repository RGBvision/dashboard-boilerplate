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
 * @version    2.1
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ModelLogin extends Model
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Generate password change link and send it to user's email
     *
     * @param int $user_id user ID
     * @param string $email user email
     * @throws Exception
     */
    public function preparePassChange(int $user_id, string $email):void
    {

        $Template = Template::getInstance();
        $Template->_load(DASHBOARD_DIR . '/app/modules/login/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $expired = date('Y-m-d H:i:s', strtotime('+4 hours'));

        $hash = md5($user_id . $email) . md5($user_id . $expired);

        DB::update("users", ["hash" => $hash, "hash_expire" => $expired], ["email" => $email]);

        $body = sprintf($Template->_get('login_reset_mail_body'), IP::getIp(), HOST, ABS_PATH, $hash, HOST, ABS_PATH, $hash, $expired);

        Mailer::send(
            $email,
            $body,
            $Template->_get('login_reset_mail_title'),
            '',
            '',
            'text/html'
        );
    }

    /**
     * Change user's password
     *
     * @param string $email user email
     * @param string $hash verification hash
     * @param string $pass new password
     */
    public function doPassChange(string $email, string $hash, string $pass):void
    {

        $salt = randomString();
        $password_hash = Auth::getPasswordHash($pass, $salt);

        DB::update("users", ["password" => $password_hash, "salt" => $salt, "hash" => "", "hash_expire" => null], ["email" => $email, "hash" => $hash]);

    }

}