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
 * @version    2.7
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Auth
{

    protected function __construct()
    {
        //
    }

    protected static function setConstants(int $uid, int $group, string $template): void
    {
        define('UID', $uid);
        define('UGROUP', $group);
        define('TPL_DIR', $template);
    }

    protected static function setSessionVars(stdClass $user): void
    {
        Session::setvar('user_id', (int)$user->user_id);
        Session::setvar('user_firstname', $user->firstname);
        Session::setvar('user_lastname', $user->lastname);
        Session::setvar('user_password', $user->password);
        Session::setvar('user_group', (int)$user->user_group_id);
        Session::setvar('user_email', $user->email);
        Session::setvar('user_ip', IP::getIp());
        Session::setvar('user_avatar', User::getAvatar((int)$user->user_id));
        Session::setvar('tpl_dir', $user->template);
    }

    // Check if user logged in
    public static function authCheck(): void
    {
        if (!Session::checkvar('user_id')) {
            Session::destroy();

            if (Request::isAjax()) {
                Response::setStatus(401);
                Response::shutDown();
            }

            Router::response(false, '', ABS_PATH . 'login');
        }
    }

    // Restore auth
    public static function authRestore(): void
    {

        if (!self::authSessions() && !self::authCookie()) {
            // Clear Session Data
            Session::delvar('user_id', 'user_password', 'permissions');
            define('UGROUP', 2);
        }

    }

    // Auth by session
    public static function authSessions(): bool
    {
        if (!Session::checkvar('user_id') || !Session::checkvar('user_password')) {
            return false;
        }

        $referer = false;

        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer = parse_url($_SERVER['HTTP_REFERER']);
            $referer = (trim($referer['host']) === $_SERVER['SERVER_NAME']);
        }

        // Check user in DB if wrong referer or IP changed
        if ($referer === false || Session::getvar('user_ip') !== IP::getIp()) {
            $sql = "
					SELECT COUNT(user_id)
					FROM
						users
					WHERE
						user_id = ?
					AND
						password = ?
					LIMIT 1
				";

            $verified = (bool)DB::cell($sql, (int)Session::getvar('user_id'), Session::getvar('user_password'));

            if (!$verified) {
                return false;
            }

            Session::setvar('user_ip', IP::getIp());

        }

        DB::update("users", ["last_activity" => date('Y-m-d H:i:s')], ["user_id" => (int)Session::getvar('user_id')]);
        DB::update("users_session", ["last_activity" => date('Y-m-d H:i:s'), "ip" => IP::getIp()], ["user_id" => (int)Session::getvar('user_id')]);

        self::setConstants(Session::getvar('user_id'), Session::getvar('user_group'), Session::getvar('tpl_dir'));

        return true;
    }

    // Auth by cookie
    public static function authCookie(): bool
    {
        if (empty(Cookie::get('auth'))) {
            return false;
        }

        $sql = "
				SELECT
					user_id
				FROM
					users_session
				WHERE
					hash = ?
				AND
					agent = ?
			";

        $user_id = (int)DB::cell($sql, Secure::sanitize(Cookie::get('auth')), Secure::sanitize($_SERVER['HTTP_USER_AGENT']));

        if ($user_id === 0) {
            Cookie::set('auth', '', 0, Core::$cookie_domain, ABS_PATH);
            return false;
        }

        $sql = "
				SELECT
				    usr.user_id,
					usr.user_group_id,
					usr.password,
					usr.firstname,
					usr.lastname,
					usr.email,
					usr.active,
					usrs.ip,
					grp.permissions,
				    grp.template
				FROM
					users AS usr
				LEFT JOIN
					user_groups AS grp
					ON grp.user_group_id = usr.user_group_id
				LEFT JOIN
					users_session AS usrs
					ON usr.user_id = usrs.user_id
				WHERE
					usr.user_id = ?
				AND
					usrs.hash = ?
				LIMIT 1
			";

        if (!$user = Arrays::toObject(DB::row($sql, $user_id, Secure::sanitize(Cookie::get('auth'))))) {
            return false;
        }

        if (LOGIN_USER_IP) {
            if (($user->ip !== '' && $user->ip !== IP::getIp())) {
                DB::delete("users_session", ["hash" => Secure::sanitize(Cookie::get('auth'))]);
            }

            Cookie::set('auth', '', 0, ABS_PATH, Core::$cookie_domain);
            return false;
        }

        DB::update("users", ["last_activity" => date('Y-m-d H:i:s')], ["user_id" => $user_id]);
        DB::update("users_session", ["last_activity" => date('Y-m-d H:i:s'), "ip" => IP::getIp()], ["user_id" => $user_id]);

        self::setSessionVars($user);
        self::setConstants((int)$user->user_id, (int)$user->user_group_id, $user->template);

        Permission::set($user->permissions);

        return true;
    }


    // Check permissions
    public static function authCheckPermission(): bool
    {
        if (!defined('UID') || !Permission::checkAccess('admin_panel')) {
            self::userLogout();
            return false;
        }

        return true;
    }


    // Logout
    public static function userLogout(): void
    {

        DB::delete("users_session", ["user_id" => UID]);
        DB::delete("sessions", ["user_id" => UID]);

        Cookie::set('auth', '', 0, Core::$cookie_domain, ABS_PATH);

        Session::destroy();

        $_SESSION = [];

        if (defined('UID') && UID) {
            Log::log(Log::INFO, 'System\Auth', "User (" . UID . ") logged out");
        }
    }

    const LOGIN_SUCCESS = 0;
    const EMPTY_LOGIN = 1;
    const WRONG_PASS = 2;
    const USER_INACTIVE = 3;

    // Login
    public static function userLogin(string $email, string $password, bool $attach_ip = false, bool $keep_in = false, int $sleep = 0): int
    {
        $email = Valid::normalizeEmail($email);
        $password = Secure::sanitize($password);

        sleep($sleep);

        if (Session::checkvar('user_id')) {
            session_unset();
            $_SESSION = [];
        }

        if (empty($email)) {
            return self::EMPTY_LOGIN;
        }

        $sql = "
				SELECT
					usr.user_id,
					usr.user_group_id,
					usr.firstname,
					usr.lastname,
					usr.email,
					usr.phone,
					usr.password,
					usr.salt,
					usr.active,
					usr.settings,
					grp.permissions, 
				    grp.template				       
				FROM
					users AS usr
				INNER JOIN
					user_groups AS grp
					ON grp.user_group_id = usr.user_group_id
				WHERE
					usr.email = ?
				LIMIT 1
			";

        $user = Arrays::toObject((array)DB::row($sql, $email));

        if (!$user || !(isset($user->password) && self::verifyPassword($password, $user->salt, $user->password))) {
            return self::WRONG_PASS;
        }

        if ((int)$user->active !== 1) {
            return self::USER_INACTIVE;
        }

        $salt = randomString();

        $password_hash =  self::getPasswordHash($password, $salt);

        $time = time();

        $user_ip = $attach_ip ? IP::getIp() : '';

        DB::update("users", [
            "last_activity" => date('Y-m-d H:i:s', $time),
            "password" => $password_hash,
            "salt" => $salt,
            "ip" => $user_ip,
        ], ["user_id" => $user->user_id]);

        self::setSessionVars($user);
        self::setConstants((int)$user->user_id, (int)$user->user_group_id, $user->template);

        Permission::set($user->permissions);

        $expire = $keep_in ? ($time + COOKIE_LIFETIME) : 0;

        $auth = md5($_SERVER['HTTP_USER_AGENT'] . md5($user->user_id));

        DB::delete('users_session', ['hash' => Secure::sanitize($auth)]);

        DB::insert("users_session", [
            "user_id" => (int)$user->user_id,
            "hash" => Secure::sanitize($auth),
            "ip" => $user_ip,
            "agent" => Secure::sanitize($_SERVER['HTTP_USER_AGENT']),
            "last_activity" => date('Y-m-d H:i:s', $time),
        ]);

        Cookie::set('auth', $auth, $expire, Core::$cookie_domain, ABS_PATH);

        Log::log(Log::INFO, 'System\Auth', "User ($user->user_id) logged in");

        unset($user, $permissions, $sql);

        return self::LOGIN_SUCCESS;
    }

    public static function getPasswordHash(string $password, string $salt): string
    {
        return password_hash(hash_hmac("sha256", $password, $salt . PWD_PEPPER), PASSWORD_ARGON2ID) ?: '';
    }

    public static function verifyPassword(string $password, string $salt, string $hash): bool
    {
        return password_verify(hash_hmac("sha256", $password, $salt . PWD_PEPPER), $hash);
    }

}