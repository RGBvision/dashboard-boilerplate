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

class Auth
{
    protected function __construct()
    {
        //
    }

    protected static function setConstants(int $uid, int $group, string $name): void
    {
        define('UID', $uid);
        define('UGROUP', $group);
        define('UNAME', $name);
    }

    protected static function setSessionVars(stdClass $user): void
    {
        Session::setvar('user_id', (int)$user->user_id);
        Session::setvar('organization_settings', unserialize($user->org_settings, ['allowed_classes' => false]));
        Session::setvar('organization_name', $user->org_name);
        Session::setvar('user_name', $user->name); // Todo
        Session::setvar('user_firstname', $user->firstname);
        Session::setvar('user_lastname', $user->lastname);
        Session::setvar('user_password', $user->password);
        Session::setvar('user_group', (int)$user->user_group_id);
        Session::setvar('user_email', $user->email);
        Session::setvar('user_ip', IP::getIp());

        if (file_exists(CP_DIR . '/uploads/avatars/' . md5((int)$user->user_id) . '-' . md5((int)$user->organization_id) . '.jpg')) {
            Session::setvar('user_avatar', '/uploads/avatars/' . md5((int)$user->user_id) . '-' . md5((int)$user->organization_id) . '.jpg');
        } else {
            Session::setvar('user_avatar', '/uploads/avatars/default.jpg');
        }
    }

    //--- Check if user logged in
    public static function authCheck(): void
    {
        if (Session::checkvar('user_id') === false) {
            Session::destroy();

            if (Request::isAjax()) {
                Response::setStatus(401);
                Request::shutDown();
            }

            Request::setHeader('Location: /login');
        }
    }

    //--- Restore auth
    public static function authRestore(): void
    {
        if (!defined('CP_LOGIN') && (self::authSessions() === false) && (self::authCookie() === false)) {
            //-- Clear Session Data
            Session::delvar('user_id');
            Session::delvar('user_password');
            Session::delvar('permissions');
        }
    }

    //--- Auth by session
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

        //--- Check user in DB if wrong referer or IP changed
        if ($referer === false || Session::getvar('user_ip') !== IP::getIp()) {
            $sql = "
					SELECT 1
					FROM
						" . PREFIX . "users
					WHERE
						user_id = '" . (int)Session::getvar('user_id') . "'
					AND
						password = '" . Secure::sanitize(Session::getvar('user_password')) . "'
					LIMIT 1
				";

            $verified = DB::Query($sql)->numRows();

            if (!$verified) {
                return false;
            }

            Session::setvar('user_ip', IP::getIp());
        }

        self::setConstants(Session::getvar('user_id'), Session::getvar('user_group'), Session::getvar('user_name'));

        return true;
    }

    //--- Auth by cookie
    public static function authCookie(): bool
    {
        if (empty(Cookie::get('auth'))) {
            return false;
        }

        $sql = "
				SELECT
					user_id
				FROM
					" . PREFIX . "users_session
				WHERE
					hash = '" . Secure::sanitize(Cookie::get('auth')) . "'
				AND
					agent = '" . Secure::sanitize($_SERVER['HTTP_USER_AGENT']) . "'
			";

        $user_id = DB::Query($sql)->getRow();

        if ((int)$user_id === 0) {
            Cookie::set('auth', '', 0, Core::$cookie_domain, ABS_PATH);
            return false;
        }

        $sql = "
				SELECT
				    usr.user_id,
					usr.user_group_id,
					usr.organization_id,
					usr.name,
					usr.password,
					usr.firstname,
					usr.lastname,
					usr.email,
					usr.active,
					usrs.ip AS ip,
					grp.permissions,
					org.name AS org_name,
					org.settings AS org_settings
				FROM
					" . PREFIX . "users AS usr
				LEFT JOIN
					" . PREFIX . "user_groups AS grp
					ON grp.user_group_id = usr.user_group_id
				LEFT JOIN
					" . PREFIX . "users_session AS usrs
					ON usr.user_id = usrs.user_id
				LEFT JOIN
				    " . PREFIX . "organizations AS org
					ON org.organization_id = usr.organization_id
				WHERE
					usr.user_id = '" . (int)$user_id . "'
				AND
					usrs.hash = '" . Secure::sanitize(Cookie::get('auth')) . "'
				LIMIT 1
			";

        $user = DB::Query($sql)->getObject();

        if (empty($user))
            return false;

        if (LOGIN_USER_IP) {
            if (($user->ip !== 0 && $user->ip !== long2ip(IP::getIp()))) {
                $sql = "
						DELETE FROM
							" . PREFIX . "users_session
						WHERE
							hash = '" . Secure::sanitize(Cookie::get('auth')) . "'
					";

                DB::Query($sql);
            }

            Cookie::set('auth', '', 0, ABS_PATH, Core::$cookie_domain);
            return false;
        }

        DB::Query("
				UPDATE
				    " . PREFIX . "users usr,
					" . PREFIX . "users_session sess
				SET
				    usr.last_activity  = '" . time() . "',
					sess.last_activity = '" . time() . "',
					sess.ip            = '" . ip2long(IP::getIp()) . "'
				WHERE
				    usr.user_id        = '" . (int)$user_id . "' AND
					sess.user_id       = '" . (int)$user_id . "'
			");

        self::setSessionVars($user);
        self::setConstants((int)$user->user_id, (int)$user->user_group_id, $user->name);

        Permission::set($user->permissions);

        return true;
    }


    //--- Check permissions
    public static function authCheckPermission(): bool
    {
        if (!defined('UID') || !Permission::checkAccess('admin_panel')) {
            self::userLogout();
            return false;
        }

        return true;
    }


    //--- Logout
    public static function userLogout(): void
    {

        $sql = "DELETE
                    " . PREFIX . "users_session,
                    " . PREFIX . "sessions
                FROM
                    " . PREFIX . "users_session,
                    " . PREFIX . "sessions
                WHERE
                    " . PREFIX . "users_session.user_id = '" . UID . "'
                AND
                    " . PREFIX . "sessions.user_id = '" . UID . "'";

        DB::Query($sql);

        Cookie::set('auth', '', 0, Core::$cookie_domain, ABS_PATH);

        Session::destroy();

        $_SESSION = array();
    }


    //--- Login
    public static function userLogin(string $login, string $password, bool $attach_ip = false, bool $keep_in = false, int $sleep = 0): int
    {
        $login = Secure::sanitize($login);
        $password = Secure::sanitize($password);

        sleep($sleep);

        if (Session::checkvar('user_id')) {
            session_unset();
            $_SESSION = array();
        }

        if (empty($login)) {
            return 1;
        }

        $sql = "
				SELECT
					usr.user_id,
					usr.user_group_id,
					usr.organization_id,
					usr.name,
					usr.firstname,
					usr.lastname,
					usr.email,
					usr.phone,
					usr.password,
					usr.salt,
					usr.active,
					usr.settings,
					grp.permissions,
					org.name AS org_name,
					org.settings AS org_settings
				FROM
					" . PREFIX . "users AS usr
				INNER JOIN
					" . PREFIX . "user_groups AS grp
					ON grp.user_group_id = usr.user_group_id
				LEFT JOIN
				    " . PREFIX . "organizations AS org
					ON org.organization_id = usr.organization_id
				WHERE
					usr.email = '" . $login . "'
				OR
					usr.name = '" . $login . "'
				OR
					usr.phone = '" . normalizePhone($login) . "'
				LIMIT 1
			";

        $user = DB::Query($sql)->getObject();

        if (!$user || (!(isset($user->password) && $user->password === md5(md5($password . $user->salt))))) {
            return 2; //--- Wrong password
        }

        if ((int)$user->active !== 1) {
            return 3; //--- User inactive
        }

        /* ToDo: Restrict user login from different devices at same time */

        /*

        $sql = "SELECT last_activity FROM " . PREFIX . "users_session WHERE user_id = '" . $user->user_id . "'";

        $last_activity = DB::Query($sql)->getRow();

        if (!isset($last_activity) OR (((int)$last_activity + 3600) < time())) {
            $sql = "DELETE FROM " . PREFIX . "users_session WHERE user_id = '" . $user->user_id . "'";
            DB::Query($sql);
            $sql = "DELETE FROM " . PREFIX . "sessions WHERE user_id = '" . Session::getvar('user_id') . "'";
            DB::Query($sql);
        } else {
            return 4; //--- Already logged in and active on other device
        }

        */

        $salt = randomString();

        $password_hash = md5(md5($password . $salt));

        $time = time();

        $u_ip = ($attach_ip)
            ? ip2long(IP::getIp())
            : 0;

        DB::Query("
				UPDATE
					" . PREFIX . "users
				SET
					last_activity = '" . $time . "',
					password      = '" . $password_hash . "',
					salt          = '" . $salt . "',
					ip            = '" . $u_ip . "'
				WHERE
					user_id       = '" . $user->user_id . "'
			");

        self::setSessionVars($user);
        self::setConstants((int)$user->user_id, (int)$user->user_group_id, $user->name);

        Permission::set($user->permissions);

        if ($keep_in) {
            $expire = $time + COOKIE_LIFETIME;

            $auth = md5($_SERVER['HTTP_USER_AGENT'] . md5($user->user_id));

            $sql = "
					DELETE FROM
						" . PREFIX . "users_session
					WHERE
						hash = '" . Secure::sanitize($auth) . "'
				";

            DB::Query($sql);

            $sql = "
					INSERT INTO
						" . PREFIX . "users_session
					SET
						user_id        = '" . $user->user_id . "',
						hash           = '" . Secure::sanitize($auth) . "',
						ip             = '" . $u_ip . "',
						agent          = '" . Secure::sanitize($_SERVER['HTTP_USER_AGENT']) . "',
						last_activity  = '" . $time . "'
				";

            DB::Query($sql);

            Cookie::set('auth', $auth, $expire, Core::$cookie_domain, ABS_PATH);
        }

        unset($user, $permissions, $sql);

        return 0;
    }
}