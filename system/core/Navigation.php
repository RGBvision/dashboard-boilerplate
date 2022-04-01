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

class Navigation
{

    // Navigation items array
    public static array $items = [];

    // Navigation types
    public const SIDEBAR = 1; // Sidebar navigation
    public const TOP = 2; // Top navigation
    public const USER = 3; // User dropdown menu

    // Sidebar navigation rubrics
    public const SIDEBAR_MAIN = 1;
    public const SIDEBAR_CONTROL = 2;
    public const SIDEBAR_CONTENT = 3;
    public const SIDEBAR_SETTINGS = 4;
    public const SIDEBAR_INFO = 5;

    // i18n sidebar navigation rubrics names
    public static array $sidebar_headers = [
        1 => 'sidebar_header_1',
        2 => 'sidebar_header_2',
        3 => 'sidebar_header_3',
        4 => 'sidebar_header_4',
        5 => 'sidebar_header_5',
    ];

    /**
     * Add navigation item
     *
     * @param int $sorting Sorting
     * @param string $name Displayed name
     * @param string $icon Icon CSS class
     * @param string $url URL
     * @param string $id Page ID
     * @param int $type Navigation type
     * @param int $rubric Navigation rubric
     * @param string $parent Parent navigation item (for submenu)
     * @param string $onclick JS onClick code
     * @param bool $external External link flag
     */
    public static function add(int $sorting, string $name, string $icon, string $url, string $id, int $type, int $rubric, string $parent, string $onclick, bool $external = false): void
    {
        self::$items[] = [
            'sorting' => $sorting,
            'name' => $name,
            'icon' => $icon,
            'link' => $url,
            'id' => $id,
            'type' => $type,
            'rubric' => $rubric,
            'parent' => $parent,
            'onclick' => $onclick,
            'external' => $external,
        ];
    }

    /**
     * Get sorted navigation items array by navigation type
     *
     * @param int $type Navigation type
     * @return array
     */
    public static function get(int $type): array
    {

        $navigation = [];

        $nav_sort = [];

        foreach (self::$items as $key => $nav) {

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

        if (empty($nav_sort[$type])) {
            return [];
        }

        foreach ($nav_sort[$type] as $key => $nav) {

            if (!empty($nav)) {

                $navigation[$key] = Arrays::multiSort($nav, 'sorting');

                foreach ($navigation[$key] as $k => $sub) {
                    if (isset($sub['submenu'])) {
                        $navigation[$key][$k]['submenu'] = Arrays::multiSort($sub['submenu'], 'sorting');
                    } else if (!isset($sub['submenu']) && empty($navigation[$key][$k]['link'])) {
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