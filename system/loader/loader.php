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

class Load
{
	protected static $classes = array();
	protected static $directories = array();
	protected static $namespaces = array();
	protected static $aliases = array();

	protected function __construct()
	{
		//
	}

	public static function addClass($className, $classPath)
	{
		self::$classes[$className] = $classPath;
	}

	public static function addClasses(array $classes)
	{
		foreach ($classes as $name => $path) {
			self::$classes[$name] = $path;
		}
	}

	public static function addFiles($path)
	{
		$files = glob($path . '/*.php');

		foreach ($files as $file) {
			require($file);
		}
	}

	public static function addModules($path = '')
	{
		$dir = dir($path . '/modules');

		$modules = array();

		while (false !== ($entry = $dir->read())) {
			if (substr($entry, 0, 1) === '.')
				continue;

			$module_dir = $dir->path . '/' . $entry;

			if (!is_dir($module_dir))
				continue;

			if (!(is_file($module_dir . '/Module.php') && include_once($module_dir . '/Module.php'))) {
				$modules['errors'][] = $entry;
				continue;
			} else {
				$module_name = 'Module' . $entry;
				new $module_name();
			}
		}

		$dir->Close();

		return $modules;
	}

	public static function addDirectory($path)
	{
		self::$directories[] = rtrim($path, '/');
	}

	public static function regNamespace($namespace, $path)
	{
		self::$namespaces[trim($namespace, '\\') . '\\'] = rtrim($path, '/');
	}

	public static function addAlias($alias, $className)
	{
		self::$aliases[$alias] = $className;
	}

	protected static function PSR0($className, $directory = null)
	{
		$classPath = '';

		if (($pos = strripos($className, '\\')) !== false) {
			$namespace = substr($className, 0, $pos);
			$className = substr($className, $pos + 1);
			$classPath = str_replace('\\', '/', $namespace) . '/';
		}

		$classPath .= str_replace('_', '/', $className) . '.php';

		$directories = ($directory === null)
			? self::$directories
			: array($directory);

		foreach ($directories as $_directory) {
			if (file_exists($_directory . '/' . $classPath)) {
				include($_directory . '/' . $classPath);

				return true;
			}
		}

		return false;
	}

	public static function loadClass($className)
	{

		$className = ltrim($className, '\\');

		if (isset(self::$aliases[$className]))
			return class_alias(self::$aliases[$className], $className);

		if (isset(self::$classes[$className]) && file_exists(self::$classes[$className])) {
			include self::$classes[$className];

			return true;
		}

		foreach (self::$namespaces as $namespace => $path) {
			if (strpos($className, $namespace) === 0) {
				if (self::PSR0(substr($className, strlen($namespace)), $path))
					return true;
			}
		}

		if (self::PSR0($className) || self::PSR0(strtolower($className)))
			return true;

		return false;
	}

	public static function init()
	{
		spl_autoload_register('self::loadClass', true);
	}

}