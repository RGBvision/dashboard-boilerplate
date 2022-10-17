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

class LoginModel extends Model
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Save verification hash
     *
     * @param string $email user email
     * @param string $hash verification hash to change password
     * @param string $expired hash valid until
     * @return bool
     */
    public function preparePassChange(string $email, string $hash, string $expired): bool
    {
        return DB::update('users', ['hash' => $hash, 'hash_expire' => $expired], ['email' => $email]) > 0;
    }

    /**
     * Change user password
     *
     * @param string $email user email
     * @param string $hash verification hash
     * @param string $pass new password
     * @return bool
     */
    public function doPassChange(string $email, string $hash, string $pass): bool
    {

        $salt = Secure::randomString();
        $password_hash = Auth::getPasswordHash($pass, $salt);

        return DB::update('users', ['password' => $password_hash, 'salt' => $salt, 'hash' => '', 'hash_expire' => null], DB::statement()->with('email = ?', $email)->andWith('hash = ?', $hash)->andWith('hash_expire >= ?', date('Y-m-d H:i:s'))) > 0;

    }

}