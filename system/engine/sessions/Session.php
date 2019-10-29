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

class Session
{
	public static function init(): void
    {
		self::storage();
		self::start();
	}

	public static function storage(): void
    {
		require_once CP_DIR . '/system/engine/sessions/' . SESSION_SAVE_HANDLER . '.php';
		Sessions::init();
	}

	public static function start(): bool
    {
		if (!session_id()) {
			Sessions::init();
			session_start();
		}

		return true;
	}

	public static function destroy(): void
    {
		if (session_id()) {
			session_unset();

			session_destroy();

			$_SESSION = array();
		}
	}

	public static function getid(): string
    {
		if (!session_id()) {
            self::start();
        }

		return session_id();
	}

	public static function getvar(string $key)
	{
		if (!session_id()) {
            self::start();
        }

		if (self::checkvar($key)) {
            return $_SESSION[$key];
        }

		return null;
	}

    public static function setvar(string $key, $value): void
    {
		if (!session_id()) {
            self::start();
        }

		$_SESSION[$key] = $value;
	}

    public static function checkvar(): bool
    {
		if (!session_id()) {
            self::start();
        }

		foreach (func_get_args() as $argument) {
			if (is_array($argument)) {
				foreach ($argument as $key) {
					if (!isset($_SESSION[(string)$key])) {
                        return false;
                    }
				}
			} else if (!isset($_SESSION[(string)$argument])) {
                    return false;
			}
		}

		return true;
	}

	public static function delvar(): void
    {
		foreach (func_get_args() as $argument) {
			if (is_array($argument)) {
				foreach ($argument as $key) {
					unset($_SESSION[(string)$key]);
				}
			} else {
				unset($_SESSION[(string)$argument]);
			}
		}
	}
}