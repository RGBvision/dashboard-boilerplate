<?php

class API
{

    /**
     * @var string|null User Auth token
     */
    static private ?string $auth = null;

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
            ($auth = $_SERVER['HTTP_AUTH']) &&
            ($user_id = (int)DB::cell("SELECT users.user_id FROM api_users INNER JOIN users ON api_users.user_id = users.user_id AND users.active = 1 WHERE api_users.token = ? LIMIT 1", $auth))
        ) {
            self::$auth = $auth;
            self::$user_id = $user_id;
            DB::update("users", ["last_activity" => time()], ["user_id" => $user_id]);
        }

    }

    /**
     * Auth API user by email and password
     *
     * @param string $email
     * @param string $password
     * @param string $fcm_token FCM token for Push notifications
     * @return array
     */
    public static function get_auth(string $email, string $password, string $fcm_token = ''): array
    {

        if (
            ($user = DB::row("SELECT user_id, password, salt FROM users WHERE email = ? AND active = 1 LIMIT 1", $email)) &&
            Auth::verifyPassword($password, $user['salt'], $user['password'])
        ) {

            DB::delete("crm_app_users", ["user_id" => $user['user_id']]);

            do {
                $token = md5($email . microtime());
            } while ((int)DB::cell("SELECT COUNT(*) FROM api_users WHERE token = ?", $token) > 0);

            // Additionally save device locale
            DB::insert("crm_app_users", ["user_id" => $user['user_id'], "token" => $token, "firebase_token" => $fcm_token, "locale" => Session::getvar('current_language')]);

            return ['success' => true, 'token' => $token];
        }

        return ['message' => i18n::_('api.auth.fail')];

    }

    /**
     * Save user photo (avatar)
     *
     * @param string $base64_image
     * @return array
     */
    public static function put_user_photo(string $base64_image): array
    {

        if (self::$auth && self::$user_id) {

            if (User::saveAvatar(self::$user_id, $base64_image)) {
                return ['success' => true, 'photo' => User::getAvatar(self::$user_id)];
            }

            return ['message' => i18n::_('api.file.wrong_data')];

        }

        return ['message' => i18n::_('api.auth.fail')];
    }

}