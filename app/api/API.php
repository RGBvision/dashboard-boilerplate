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
 * @since      File available since Release 2.0
 */

class API
{

    /**
     * @var string|null User Authorization token
     */
    static private ?string $authorization_token = null;

    /**
     * @var int|null User ID
     */
    static private ?int $user_id = null;

    /**
     * Constructor
     * Set auth token and user ID if available
     */
    public function __construct()
    {

        if (
            ($auth = $_SERVER['HTTP_AUTHORIZATION']) &&
            ($user_id = (int)DB::cell("SELECT users.user_id FROM api_users INNER JOIN users ON api_users.user_id = users.user_id AND users.active = 1 WHERE api_users.auth_token = ? AND api_users.auth_token_expire > ?", $auth, date('Y-m-d H:i:s')))
        ) {
            self::$authorization_token = $auth;
            self::$user_id = $user_id;
            DB::update("users", ["last_activity" => date('Y-m-d H:i:s')], ["user_id" => $user_id]);
        }

    }

    /**
     * Auth user by email and password
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public static function post_auth(string $email, string $password): array
    {

        if (
            ($user = DB::row("SELECT user_id, password, salt FROM users WHERE email = ? AND active = 1 LIMIT 1", $email)) &&
            Auth::verifyPassword($password, $user['salt'], $user['password'])
        ) {

            DB::delete("crm_app_users", ["user_id" => $user['user_id']]);

            $auth_token = null;
            $refresh_token = null;

            do {

                try {
                    $auth_token = bin2hex(random_bytes(32));
                    $refresh_token = bin2hex(random_bytes(32));
                } catch (Exception $e) {
                }

            } while (!$auth_token || !$refresh_token || ((int)DB::cell("SELECT COUNT(*) FROM api_users WHERE auth_token = ? OR refresh_token = ?", $auth_token, $refresh_token) > 0));

            DB::insert(
                "crm_app_users",
                [
                    "user_id" => $user['user_id'],
                    "auth_token" => $auth_token,
                    "auth_token_expire" => date('Y-m-d H:i:s', time() + AUTH_TOKEN_LIFETIME),
                    "refresh_token" => $refresh_token,
                    "refresh_token_expire" => date('Y-m-d H:i:s', time() + REFRESH_TOKEN_LIFETIME),
                ]
            );

            return ['success' => true, 'auth_token' => $auth_token];
        }

        return ['message' => i18n::_('api.auth.fail')];

    }

    /**
     * Save user avatar
     *
     * @param string $base64_image
     * @return array
     */
    public static function put_user_avatar(string $base64_image): array
    {

        if (self::$authorization_token && self::$user_id) {

            if (User::saveAvatar(self::$user_id, $base64_image)) {
                return ['success' => true, 'avatar' => User::getAvatar(self::$user_id)];
            }

            return ['message' => i18n::_('api.file.wrong_data')];

        }

        return ['message' => i18n::_('api.auth.fail')];
    }

}