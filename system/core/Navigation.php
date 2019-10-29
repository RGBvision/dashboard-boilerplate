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

class Navigation
{
	public static $items = array();

	//-- Type
    public const LEFT = 1;
    public const TOP = 2;
    public const USER = 3;

	//-- Rubric
    public const LEFT_MAIN = 1;
    public const LEFT_CONTROL = 2;
    public const LEFT_APPS = 3;
    public const LEFT_SETTINGS = 4;
    public const LEFT_USER = 5;

	public static $left_headers = array(
		1 => 'left_menu_header_1',
		2 => 'left_menu_header_2',
		3 => 'left_menu_header_3',
		4 => 'left_menu_header_4',
		5 => 'left_menu_header_5'
	);

	public static function add(int $sorting, string $name, string $icon, string $url, string $id, int $type, int $rubric, string $parent, string $onclick, bool $external = false): void
    {
		self::$items[] = array(
			'sorting' => $sorting,
			'name' => $name,
			'icon' => $icon,
			'link' => $url,
			'id' => $id,
			'type' => $type,
			'rubric' => $rubric,
			'parent' => $parent,
			'onclick' => $onclick,
			'external' => $external
		);
	}

	public static function show($type): array
    {
		$navigation = array();

		$nav_sort = array();

		foreach (self::$items AS $key => $nav) {
			if (($nav['link'] !== '') && !Permission::check($nav['id'] . '_view')) {
                continue;
            }

			if ($nav['parent']) {
				$nav_sort[$nav['type']][$nav['rubric']][$nav['parent']]['submenu'][] = $nav;
			} else {
				$nav_sort[$nav['type']][$nav['rubric']][$nav['id']]['sorting'] = $nav['sorting'];
				$nav_sort[$nav['type']][$nav['rubric']][$nav['id']]['name'] = $nav['name'];
				$nav_sort[$nav['type']][$nav['rubric']][$nav['id']]['icon'] = $nav['icon'];
				$nav_sort[$nav['type']][$nav['rubric']][$nav['id']]['link'] = $nav['link'];
				$nav_sort[$nav['type']][$nav['rubric']][$nav['id']]['id'] = $nav['id'];
				$nav_sort[$nav['type']][$nav['rubric']][$nav['id']]['type'] = $nav['type'];
				$nav_sort[$nav['type']][$nav['rubric']][$nav['id']]['rubric'] = $nav['rubric'];
				$nav_sort[$nav['type']][$nav['rubric']][$nav['id']]['parent'] = $nav['parent'];
				$nav_sort[$nav['type']][$nav['rubric']][$nav['id']]['onclick'] = $nav['onclick'];
				$nav_sort[$nav['type']][$nav['rubric']][$nav['id']]['sorting'] = $nav['sorting'];
				$nav_sort[$nav['type']][$nav['rubric']][$nav['id']]['external'] = $nav['external'];
			}
		}

		foreach ($nav_sort[$type] AS $key => $nav) {
			if (!empty($nav)) {
				$navigation[$key] = Arrays::multiSort($nav, 'sorting');

				foreach ($navigation[$key] AS $k => $sub) {
					if (isset($sub['submenu'])) {
						$navigation[$key][$k]['submenu'] = Arrays::multiSort($sub['submenu'], 'sorting');
					} elseif (!isset($sub['submenu']) && empty($navigation[$key][$k]['link'])) {
                        unset($navigation[$key][$k]);
                    }
				}
			}
		}

		unset($nav_sort);

		asort($navigation);

		return $navigation;
	}
}