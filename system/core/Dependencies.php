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
 * @version    2.1
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 2.0
 */

class Dependencies
{
	public static array $files = [];

	function __construct()
	{
		//
	}

    /**
     * Добавить файл.
     *
     * @param string $file путь и имя файла
     * @param int $priority приоритет для сортировки
     * @param string $params параметры
     */
    public static function add(string $file, int $priority = 10, string $params = ''): void
	{
		self::$files[] = [
			'file' => $file,
			'priority' => $priority,
            'params' => $params
		];
	}

    /**
     * Получить отсортированный список файлов.
     *
     * @return array массив с файлами
     */
    public static function get(): array
	{
		return Arrays::multiSort(self::$files, 'priority');
	}
}