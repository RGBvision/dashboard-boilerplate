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
 * @version    4.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Permissions
{
    protected static array $_permissions = [];

    /**
     * Add permission record
     *
     * @param string $module module
     * @param array $permission list of permissions names
     * @param string $icon CSS class of displayed icon
     * @param int $priority priority for sorting
     */
    public static function add(string $module, array $permission, string $icon = '', int $priority = 10): void
    {
        if (!empty($permission)) {
            self::$_permissions[$module] = [
                'perm' => $permission,
                'icon' => $icon,
                'priority' => $priority,
            ];
        }
    }

    /**
     * Get permissions list
     *
     * @return array
     */
    public static function getList(): array
    {
        return self::$_permissions;
    }

    /**
     * Set session permissions
     *
     * @param array $permissions permissions
     */
    public static function set(array $permissions): void
    {
        Session::delvar('permissions');
        Session::setvar('permissions', $permissions);
    }

    /**
     * Check if user has specific permission
     *
     * @param string $perm permission ID
     * @return bool
     */
    public static function has(string $perm): bool
    {
        return ($permissions = Session::getvar('permissions')) && (in_array('all_permissions', $permissions) || in_array($perm, $permissions));
    }

    /**
     * Check if user has permission and define NO_PERMISSION constant if not
     *
     * @param string $perm permission ID
     * @return bool
     */
    public static function checkAccess(string $perm): bool
    {
        if (!self::has($perm)) {
            if (!defined('NO_PERMISSION')) {
                define('NO_PERMISSION', 1);
            }
            return false;
        }
        return true;
    }

}