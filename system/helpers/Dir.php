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

class Dir
{
	protected function __construct()
	{
		//--
	}

	public static function create(string $dir, $chmod = 0775): bool
    {
		return (!self::exists($dir))
			? @mkdir($dir, $chmod, true)
			: true;
	}

	public static function exists(string $dir): bool
    {
		return (file_exists($dir) && is_dir($dir));
	}

	public static function checkPerm(string $dir): int
	{
		clearstatcache();
		return (int)substr(sprintf('%o', fileperms($dir)), -4);
	}

	public static function delete(string $dir): void
    {

		if (is_dir($dir)) {
			$elements = scandir($dir);

			foreach ($elements as $element) {
				if ($element !== '.' && $element !== '..') {
					if (filetype($dir . '/' . $element) === 'dir') {
                        self::delete($dir . '/' . $element);
                    } else {
                        unlink($dir . '/' . $element);
                    }
				}
			}
		}

		reset($elements);
		rmdir($dir);
	}

	public static function scan(string $dir): array
    {

		if (is_dir($dir) && $handle = opendir($dir)) {
			$files = array();

			while ($element = readdir($handle)) {
				if ($element !== '.' && $element !== '..' && is_dir($dir . DS . $element)) {
                    $files[] = $element;
                }
			}

			return $files;
		}

		return [];
	}

	public static function writable($path): bool
    {
		$path = (string)$path;

		$file = tempnam($path, 'writable');

		if ($file !== false) {
			File::delete($file);

			return true;
		}

		return false;
	}

	public static function size(string $path): int
	{

		$total_size = 0;
		$files = scandir($path);
		$clean_path = rtrim($path, '/') . '/';

		foreach ($files as $t) {
			if ($t !== '.' && $t !== '..') {
				$current_file = $clean_path . $t;

				if (is_dir($current_file)) {
					$total_size += self::size($current_file);
				} else {
					$total_size += filesize($current_file);
				}
			}
		}

		return $total_size;
	}

	public static function copy($src, $dst): void
    {
		$dir = opendir($src);

        if (!mkdir($dst) && !is_dir($dst)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dst));
        }

		while (false !== ($file = readdir($dir))) {
			if (($file !== '.') && ($file !== '..')) {
				if (is_dir($src . '/' . $file)) {
					self::copy($src . '/' . $file, $dst . '/' . $file);
				} else {
					copy($src . '/' . $file, $dst . '/' . $file);
				}
			}
		}

		closedir($dir);
	}
}