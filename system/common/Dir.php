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
 * @version    2.1
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Dir
{
	protected function __construct()
	{
		//--
	}


    /**
     * Create new directory if not exists
     *
     * @param string $dir path
     * @param int $chmod permissions
     * @return bool
     */
    public static function create(string $dir, int $chmod = 0775): bool
    {
		return (!self::exists($dir))
			? @mkdir($dir, $chmod, true)
			: true;
	}


    /**
     * Check if directory exists
     *
     * @param string $dir path
     * @return bool
     */
    public static function exists(string $dir): bool
    {
		return (file_exists($dir) && is_dir($dir));
	}


	/**
     * Get access permissions
     *
     * @param string $dir path
     * @return int
     */
    public static function checkPerm(string $dir): int
	{
		clearstatcache();
		return (int)substr(sprintf('%o', fileperms($dir)), -4);
	}

    /**
     * Delete directory contents
     *
     * @param string $dir path
     */
    public static function delete_contents(string $dir): void
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
	}

    /**
     * Delete directory and all contents
     *
     * @param string $dir path
     */
    public static function delete(string $dir): void
    {
        self::delete_contents($dir);
		rmdir($dir);
	}

    /**
     * Get directory contents
     *
     * @param string $dir path
     * @return array
     */
    public static function scan(string $dir): array
    {

		if (is_dir($dir) && $handle = opendir($dir)) {
			$files = [];

			while ($element = readdir($handle)) {
				if ($element !== '.' && $element !== '..' && is_dir($dir . DS . $element)) {
                    $files[] = $element;
                }
			}

			return $files;
		}

		return [];
	}

    /**
     * Check if directory writable
     *
     * @param string $path path
     * @return bool
     */
    public static function writable(string $path): bool
    {

		$file = tempnam($path, 'writable');

		if ($file !== false) {
			File::delete($file);

			return true;
		}

		return false;
	}

    /**
     * Get directory size
     *
     * @param string $path path
     * @return int
     */
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

    /**
     * Copy (clone) directory
     *
     * @param string $src source path
     * @param string $dst destination path
     */
    public static function copy(string $src, string $dst): void
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