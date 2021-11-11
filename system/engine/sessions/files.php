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
 * @version    1.1
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Sessions
{
	static public $sessLifeTime;

	static public $savePath;
	static public $sessionName;

	static private $instance = null;

	private function __construct()
	{
		//ini_set('session.save_handler', 'user');

		self::$sessLifeTime = (defined('SESSION_LIFETIME') && is_numeric(SESSION_LIFETIME))
			? SESSION_LIFETIME
			: (get_cfg_var("session.gc_maxlifetime") < 1440
				? 1440
				: get_cfg_var("session.gc_maxlifetime"));

		Sessions::setHandler();
	}


	public static function getInstance(): ?\Sessions
    {
		if (self::$instance === null)
			self::$instance = new self;

		return self::$instance;
	}


	public static function init(): void
    {
		self::getInstance();
	}


	public static function _open($savePath, $sessionName): bool
    {
		self::$savePath = DASHBOARD_DIR . SESSION_DIR;
		self::$sessionName = $sessionName;

		return true;
	}


	public static function _close(): bool
    {
		self::_gc(self::$sessLifeTime);

		return true;
	}


	public static function _read($id)
	{
		$sessionFile = self::_folder($id) . '/' . $id . '.sess';

		if (!file_exists($sessionFile))
			return '';

		if ($fp = @fopen($sessionFile, "r")) {
			$sessionData = fread($fp, filesize($sessionFile));
			return ($sessionData);
		} else {
			return '';
		}

	}


	public static function _write($id, $sessionData)
	{
		$sessionFile = self::_folder($id) . '/' . $id . '.sess';

		if (!file_exists(self::_folder($id))) {
            if (!mkdir($concurrentDirectory = self::_folder($id), 0777, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

		if ($fp = @fopen($sessionFile, 'wb'))
			fwrite($fp, $sessionData);

		return true;
	}


	public static function _destroy($id): bool
    {
		$sessionFile = self::_folder($id) . '/' . $id . '.sess';

		return @unlink($sessionFile);
	}


	public static function _gc($maxLifeTime): bool
    {
		self::_clear(DASHBOARD_DIR . SESSION_DIR, 'sess', $maxLifeTime);

		return true;
	}


	public static function _clear($dir, $mask, $maxLifeTime): void
    {
		$search = glob($dir . '/*');

		if (!is_array($search))
			exit;

		foreach ($search as $fileName) {

			if (strtolower(substr($fileName, strlen($fileName) - strlen($mask), strlen($mask))) === strtolower($mask)) {
                if ((filemtime($fileName) + $maxLifeTime) < time()) {
                    @unlink($fileName);
                }
            }

			if (is_dir($fileName) && !count(glob($fileName . '/*'))) {
                @rmdir($fileName);
            }

			self::_clear($fileName, $mask, $maxLifeTime);
		}
	}


	public static function _folder($id): string
    {
		return self::$savePath . '/' . mb_substr($id, 0, 3);
	}


	public static function setHandler(): void
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


	public function __destruct()
	{
		register_shutdown_function('session_write_close');
	}

}