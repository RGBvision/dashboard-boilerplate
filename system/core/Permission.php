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
 * @version    2.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Permission
{
    protected static $_permissions = array();

    protected function __construct()
    {
        //
    }

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
                'priority' => $priority
            ];
        }
    }

    /**
     * Get permissions list
     *
     * @return array
     */
    public static function get(): array
    {
        return self::$_permissions;
    }

    /**
     * Set session permissions
     *
     * ToDo: store permissions as JSON / Array
     *
     * @param string $permissions permissions divided by `|` symbol
     */
    public static function set(string $permissions): void
    {
        Session::delvar('permissions');

        $_permissions = explode('|', preg_replace('/\s+/', '', $permissions));

        Session::setvar('permissions', []);

        foreach ($_permissions as $permission) {
            $_SESSION['permissions'][$permission] = 1;
        }
    }

    /**
     * Check if user has permission
     *
     * @param string $perm permission ID
     * @return bool
     */
    public static function check(string $perm): bool
    {
        $permissions = Session::getvar('permissions');
        $user_groups = (Session::checkvar('user_group') === true && Session::getvar('user_group') === 1);
        $all_permissions = (isset($permissions['all_permissions']) && $permissions['all_permissions'] === 1);
        $errors = (isset($permissions[$perm]) && $permissions[$perm] === 'errors_view');
        $permission = (isset($permissions[$perm]) && $permissions[$perm] === 1);

        return $user_groups || $all_permissions || $errors || $permission;
    }

    /**
     * Check if user has permission and define NO_PERMISSION constant if not
     *
     * @param string $perm permission ID
     * @return bool
     */
    public static function checkAccess(string $perm): bool
    {
        if (!self::check($perm)) {
            if (!defined('NO_PERMISSION')) {
                define('NO_PERMISSION', 1);
            }
            return false;
        }
        return true;
    }


    // ToDo: Refactor
    public static function perm(string $perm): bool
    {
        $permissions = Session::getvar('permissions');
        $permission = (isset($permissions[$perm]) && $permissions[$perm] === 1);
        $all_permissions = (isset($permissions['all_permissions']) && $permissions['all_permissions'] === 1);

        return $permission || $all_permissions;
    }
}