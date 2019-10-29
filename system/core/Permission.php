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

class Permission
{
	protected static $_permissions = array();

	protected function __construct()
	{
		//
	}

	public static function add(string $key, array $permission, string $icon = '', int $priority = 10): void
    {
		if (!empty($permission)) {
            self::$_permissions[$key] = array(
                'perm' => $permission,
                'icon' => $icon,
                'priority' => $priority
            );
        }
	}

	public static function get(): array
    {
		return self::$_permissions;
	}

	public static function set(string $permissions): void
    {
		Session::delvar('permissions');

		$_permissions = explode('|', preg_replace('/\s+/', '', $permissions));

		Session::setvar('permissions', array());

		foreach ($_permissions as $permission) {
            $_SESSION['permissions'][$permission] = 1;
        }
	}

	public static function check(string $perm): bool
    {
		$permissions = Session::getvar('permissions');
		$user_groups = (Session::checkvar('user_group') === true && Session::getvar('user_group') === 1);
		$all_permissions = (isset($permissions['all_permissions']) && $permissions['all_permissions'] === 1);
		$errors = (isset($permissions[$perm]) && $permissions[$perm] === 'errors_view');
		$permission = (isset($permissions[$perm]) && $permissions[$perm] === 1);

        return $user_groups || $all_permissions || $errors || $permission;
    }

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

	public static function perm(string $perm): bool
    {
		$permissions = Session::getvar('permissions');
		$permission = (isset($permissions[$perm]) && $permissions[$perm] === 1);
		$all_permissions = (isset($permissions['all_permissions']) && $permissions['all_permissions'] === 1);

        return $permission || $all_permissions;
    }
}