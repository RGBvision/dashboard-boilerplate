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

class Sessions
{
    static public $sessLifetime;

    static private $instance = null;

    private function __construct()
    {
        register_shutdown_function('session_write_close');

        self::$sessLifetime = (defined('SESSION_LIFETIME') && is_numeric(SESSION_LIFETIME))
            ? SESSION_LIFETIME
            : (ini_get('session.gc_maxlifetime') < 1440
                ? 1440
                : ini_get('session.gc_maxlifetime'));

        self::setHandler();
    }

    public static function setHandler()
    {
        session_set_save_handler(
            array('Sessions', '_open'),
            array('Sessions', '_close'),
            array('Sessions', '_read'),
            array('Sessions', '_write'),
            array('Sessions', '_destroy'),
            array('Sessions', '_gc')
        );
    }

    public static function init()
    {
        self::getInstance();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function _open($path, $name)
    {
        return true;
    }

    public static function _close()
    {
        return true;
    }

    public static function _read($sessionId)
    {
        $qid = DB::Query("
				SELECT
					value, Ip
				FROM
					" . PREFIX . "sessions
				WHERE
					sesskey = '" . $sessionId . "'
				AND
					expire > '" . time() . "'
			");

        if ((list($value, $ip) = $qid->getArray()) && $ip === IP::getIp()) {
            return $value;
        }

        return '';
    }

    public static function _write($sessionId, $data)
    {
        if (self::_check($sessionId)) {
            DB::Query("
					UPDATE
						" . PREFIX . "sessions
					SET
						expire 			= " . (time() + self::$sessLifetime) . ",
						expire_date 	= FROM_UNIXTIME(expire,'%d.%m.%Y, %H:%i:%s'),
						value 			= '" . addslashes($data) . "',
						Ip 				= '" . IP::getIp() . "',
					    user_id         = '" . (defined('UID') ? UID : 0) . "'
					WHERE
						sesskey 		= '" . $sessionId . "'
					AND
						expire 			> '" . time() . "'
				");
        } else {
            self::_insert($sessionId, $data);
        }

        return true;
    }

    public static function _check($sessionId)
    {
        $qid = DB::Query("
				SELECT
					COUNT(*)
				FROM
					" . PREFIX . "sessions
				WHERE
					sesskey = '" . $sessionId . "'
			")->getRow();

        if ($qid) {
            return true;
        }

        return false;
    }

    public static function _insert($sessionId, $data)
    {
        $sql = "
				INSERT INTO
					" . PREFIX . "sessions
				SET
					token			= '" . token() . "',
					sesskey			= '" . $sessionId . "',
					expire			= '" . (time() + self::$sessLifetime) . "',
					expire_date		= FROM_UNIXTIME(expire, '%d.%m.%Y, %H:%i:%s'),
					value			= '" . addslashes($data) . "',
					ip				= '" . IP::getIp() . "',
					user_id         = '" . (defined('UID') ? UID : 0) . "'
			";

        return DB::Query($sql);
    }

    public static function _destroy($ses_id)
    {
        return DB::Query("
				DELETE FROM
					" . PREFIX . "sessions
				WHERE
					sesskey = '" . $ses_id . "'
			");
    }

    public static function _gc($maxlifetime)
    {
        $session_res = DB::Query("
				DELETE FROM
					" . PREFIX . "sessions
				WHERE
					expire < (UNIX_TIMESTAMP(NOW()) - " . (int)$maxlifetime . ")
			");

        if (!$session_res) {
            return false;
        }

        return true;
    }

    function __destruct()
    {
        //
    }
}