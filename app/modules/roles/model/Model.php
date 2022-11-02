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

class RolesModel extends Model
{

    public function getRole(int $user_role_id): ?array
    {
        $sql = "
				SELECT
					grp.user_role_id,
					grp.name,
					COUNT(usr.user_id) AS users
				FROM
					user_roles AS grp
				LEFT JOIN
					users AS usr
					ON usr.user_role_id = grp.user_role_id
				WHERE
					grp.deleted != 1 AND
					grp.user_role_id != 2
				AND 
					grp.user_role_id = ?
			";

        if (!$role = DB::row($sql, $user_role_id)) {
            return null;
        }

        $role['deleted'] = UserRoles::isDeletable($role['user_role_id'], $role['users']);
        $role['editable'] = UserRoles::isEditable($role['user_role_id']);

        return $role;
    }

    public function saveRole()
    {
        $save = true;

        $type = 'danger';
        $arg = [];

        $Template = Template::getInstance();

        $user_role_id = Request::post('user_role_id');
        $permissions = Request::post('permissions');
        $action = Request::post('action');

        if ($permissions and is_array($permissions))
            $permissions = implode('|', $permissions);

        if (!is_numeric($user_role_id)) $save = false;

        if ($user_role_id == 1 or $user_role_id == 2) $save = false;

        if (!UserRoles::isEditable($user_role_id)) $save = false;

        if ($save or $action == 'add') {
            if ($user_role_id) {
                $sql = "
						UPDATE
							user_roles
						SET
							permissions = '" . $permissions . "'
						" . (Request::post('user_role_name') ? ", name = '" . Request::post('user_role_name') . "'" : '') . "
						WHERE
							user_role_id = '" . $user_role_id . "'
					";

                DB::Query($sql);

                $message = $Template->_get('roles_message_edit_success');
                $type = 'success';
            } else {
                $sql = "
							INSERT INTO
								user_roles
							SET
								permissions = '" . $permissions . "'
							" . (Request::post('user_role_name') ? ", name = '" . Request::post('user_role_name') . "'" : '') . "
						";

                DB::Query($sql);

                $user_role_id = DB::getInsertId();

                if ($user_role_id) {
                    $message = $Template->_get('roles_message_edit_success');
                    $type = 'success';
                    $arg = ['id' => $user_role_id];
                } else {
                    $message = $Template->_get('roles_message_edit_error');
                }
            }
        } else {
            $message = $Template->_get('roles_message_edit_error');
        }

        Router::response($type === 'success', $message, ABS_PATH . 'roles', $arg);
    }

    public function deleteRole()
    {


        $user_role_id = Request::get('user_role_id');

        $type = 'danger';

        $delete = true;

        if (!$user_role_id)
            $delete = false;

        $Template = Template::getInstance();

        if ($delete) {
            $role = self::getRole((int)$user_role_id);

            if ($role['deleted']) {
                $sql = "
						UPDATE
							user_roles
						SET
							deleted = '1'
						WHERE
							user_role_id = '" . $user_role_id . "'
					";

                DB::Query($sql);

                $message = $Template->_get('roles_message_del_success');
                $type = 'success';
            } else {
                $message = $Template->_get('roles_message_del_perm_error');
            }
        } else {
            $message = $Template->_get('roles_message_del_id_error');
        }

        Router::response($type === 'success', $message, '/route/roles');
    }

    public function getAllPermissions(?int $user_role_id = null): array
    {

        $permissions = [];

        $_permissions = Permissions::getList();

        $role_permissions = self::getRolePermissions($user_role_id);

        foreach ($_permissions as $category => $permission) {
            $permissions[$category] = [];

            $permissions[$category]['name'] = 'perm_header_' . $category;

            if (is_array($permission['perm'])) {
                foreach ($permission['perm'] as $perm) {
                    $permissions[$category]['perm'][$perm] = in_array($perm, $role_permissions);
                }
            }

            if (isset($permission['icon']))
                $permissions[$category]['icon'] = $permission['icon'];

            $permissions[$category]['priority'] = $permission['priority'];
        }

        unset($_permissions, $role_permissions);

        return Arrays::multiSort($permissions, 'priority');
    }

    public function getRolePermissions(?int $user_role_id = null): array
    {
        $permissions = [];

        if ($user_role_id) {
            $_permissions = DB::cell('SELECT permissions FROM user_roles WHERE user_role_id = ?', $user_role_id);
            $permissions = Json::decode($_permissions);
        }

        return $permissions;
    }

    public function getRoleName(int $user_role_id): string|null
    {
        return DB::cell('SELECT `name` FROM user_roles WHERE user_role_id = ?', $user_role_id);
    }

    public function isDisabled(int $user_role_id): bool
    {
        return in_array($user_role_id, [UserRoles::SUPERADMIN, UserRoles::ANONYMOUS], true);
    }
}