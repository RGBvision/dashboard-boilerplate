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
 * @version    2.8
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

use ParagonIE\EasyDB\EasyStatement;

class Sessions
{
    static public $sessLifetime;

    private static ?Sessions $instance = null;

    private function __construct()
    {
        register_shutdown_function('session_write_close');
        self::$sessLifetime = (defined('SESSION_LIFETIME') && is_numeric(SESSION_LIFETIME)) ? SESSION_LIFETIME : max(1440, (int)ini_get('session.gc_maxlifetime'));
        self::setHandler();
    }

    public static function setHandler()
    {
        session_set_save_handler(
            ['Sessions', '_open'],
            ['Sessions', '_close'],
            ['Sessions', '_read'],
            ['Sessions', '_write'],
            ['Sessions', '_destroy'],
            ['Sessions', '_gc']
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
        $qid = DB::row("
				SELECT
					value, ip
				FROM
					sessions
				WHERE
					sesskey = ?
				AND
					expire > ?
			", $sessionId, date('Y-m-d H:i:s'));

        if (($qid) && ($value = $qid['value']) && ($ip = $qid['ip']) && ($ip === IP::getIp())) {
            return $value;
        }

        return '';
    }

    public static function _write($sessionId, $data)
    {
        if (self::_check($sessionId)) {
            DB::query("
					UPDATE
						sessions
					SET
						expire 			= ?,
						value 			= ?,
						ip 				= ?,
					    user_id         = ?
					WHERE
						sesskey 		= ?
					AND
						expire 			> ?
				",
                date('Y-m-d H:i:s', (time() + self::$sessLifetime)),
                $data,
                IP::getIp(),
                (defined('UID') ? UID : 0),
                $sessionId,
                date('Y-m-d H:i:s')
            );

        } else {
            self::_insert($sessionId, $data);
        }

        return true;
    }

    public static function _check($sessionId)
    {
        $qid = DB::cell("
				SELECT
					COUNT(*)
				FROM
					sessions
				WHERE
					sesskey = '" . $sessionId . "'
			");

        if ($qid) {
            return true;
        }

        return false;
    }

    public static function _insert($sessionId, $data)
    {
        DB::insert("sessions", [
            "token" => token(),
            "sesskey" => $sessionId,
            "expire" => date('Y-m-d H:i:s', (time() + self::$sessLifetime)),
            "value" => $data,
            "ip" => IP::getIp(),
            "user_id" => (defined('UID') ? UID : 0)
        ]);

        return true;
    }

    public static function _destroy($sessionId)
    {
        return DB::delete("sessions", ["sesskey" => $sessionId]);
    }

    public static function _gc($maxlifetime)
    {
        $statement = EasyStatement::open()->with("expire < " . date('Y-m-d H:i:s', time()));
        $session_res = DB::delete("sessions", $statement->values());

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