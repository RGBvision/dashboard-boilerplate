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
 * @version    3.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ModelGroups extends Model
{

    public function getGroups($exclude = '')
    {
        $exclude = ($exclude != '' && is_numeric($exclude))
            ? "AND grp.user_group_id != " . (int)$exclude
            : '';

        $sql = "
				SELECT
					grp.user_group_id,
					grp.name,
					COUNT(usr.user_id) AS users
				FROM
					user_groups AS grp
				LEFT JOIN
					users AS usr
					ON usr.user_group_id = grp.user_group_id AND usr.deleted = 0
				WHERE
					grp.user_group_id != 2
					AND grp.deleted != '1'
				$exclude
				GROUP BY
					grp.user_group_id ASC
			";

        $rows = DB::query($sql);

        $groups = [];

        foreach ($rows as $row) {
            $row['deletable'] = self::getDeletable($row['user_group_id'], $row['users']);
            $row['editable'] = self::isEditable($row['user_group_id']);

            $groups[] = $row;
        }

        return $groups;
    }

    public function getDeletable($user_group_id, $count): bool
    {
        return !(
            ($user_group_id == 1) ||
            ($user_group_id == 2) ||
            ($count > 0) ||
            !Permission::perm('groups_delete')
        );
    }

    public function isEditable($user_group_id): bool
    {
        return (
            ((UGROUP != 1) && ($user_group_id == 1)) ||
            ((UGROUP != 1) && ($user_group_id == 2)) ||
            Permission::perm('groups_edit')
        );
    }

    public function saveGroup()
    {
        $save = true;

        $type = 'danger';
        $arg = [];


        $Template = Template::getInstance();

        $user_group_id = Request::post('user_group_id');
        $permissions = Request::post('permissions');
        $action = Request::post('action');

        if ($permissions and is_array($permissions))
            $permissions = implode('|', $permissions);

        if (!is_numeric($user_group_id))
            $save = false;

        if ($user_group_id == 1 or $user_group_id == 2)
            $save = false;

        if (!self::isEditable($user_group_id))
            $save = false;

        if ($save or $action == 'add') {
            if ($user_group_id) {
                $sql = "
						UPDATE
							user_groups
						SET
							permissions = '" . $permissions . "'
						" . (Request::post('user_group_name') ? ", name = '" . Request::post('user_group_name') . "'" : '') . "
						WHERE
							user_group_id = '" . $user_group_id . "'
					";

                DB::Query($sql);

                $message = $Template->_get('groups_message_edit_success');
                $type = 'success';
            } else {
                $sql = "
							INSERT INTO
								user_groups
							SET
								permissions = '" . $permissions . "'
							" . (Request::post('user_group_name') ? ", name = '" . Request::post('user_group_name') . "'" : '') . "
						";

                DB::Query($sql);

                $user_group_id = DB::getInsertId();

                if ($user_group_id) {
                    $message = $Template->_get('groups_message_edit_success');
                    $type = 'success';
                    $arg = ['id' => $user_group_id];
                } else {
                    $message = $Template->_get('groups_message_edit_error');
                }
            }
        } else {
            $message = $Template->_get('groups_message_edit_error');
        }

        Router::response($type === 'success', $message, ABS_PATH . 'groups', $arg);
    }

    public function deleteGroup()
    {


        $user_group_id = Request::get('user_group_id');

        $type = 'danger';

        $delete = true;

        if (!$user_group_id)
            $delete = false;

        $Template = Template::getInstance();

        if ($delete) {
            $group = self::getGroup((int)$user_group_id);

            if ($group['deleted']) {
                $sql = "
						UPDATE
							user_groups
						SET
							deleted = '1'
						WHERE
							user_group_id = '" . $user_group_id . "'
					";

                DB::Query($sql);

                $message = $Template->_get('groups_message_del_success');
                $type = 'success';
            } else {
                $message = $Template->_get('groups_message_del_perm_error');
            }
        } else {
            $message = $Template->_get('groups_message_del_id_error');
        }

        Router::response($type === 'success', $message, '/route/groups');
    }

    public function getGroup($user_group_id)
    {
        $sql = "
				SELECT
					grp.user_group_id,
					grp.name,
					COUNT(usr.user_id) AS users
				FROM
					user_groups AS grp
				LEFT JOIN
					users AS usr
					ON usr.user_group_id = grp.user_group_id
				WHERE
					grp.deleted != 1 AND
					grp.user_group_id != 2
				AND 
					grp.user_group_id = '" . $user_group_id . "'
			";

        $group = DB::row($sql);

        if ($group['user_group_id'] <= 0)
            return false;

        $group['deleted'] = self::getDeletable($group['user_group_id'], $group['users']);

        $group['editable'] = self::isEditable($group['user_group_id']);

        return $group;
    }

    public function getAllPermissions($user_group_id = null)
    {
        $permissions = [];

        $_permissions = Permission::get();

        $group_permissions = self::getGroupPermissions($user_group_id);

        foreach ($_permissions as $category => $permission) {
            $permissions[$category] = [];

            $permissions[$category]['name'] = 'perm_header_' . $category;

            if (is_array($permission['perm'])) {
                foreach ($permission['perm'] as $perm) {
                    $permissions[$category]['perm'][$perm] = in_array($perm, $group_permissions);
                }
            }

            if (isset($permission['icon']))
                $permissions[$category]['icon'] = $permission['icon'];

            $permissions[$category]['priority'] = $permission['priority'];
        }

        unset($_permissions, $group_permissions);

        return Arrays::multiSort($permissions, 'priority');
    }

    public function getGroupPermissions($user_group_id)
    {
        $permissions = [];

        if (!empty($user_group_id)) {
            $sql = "
					SELECT
						permissions
					FROM
						user_groups
					WHERE
						user_group_id = '" . (int)$user_group_id . "'
				";

            $query = DB::cell($sql);

            $permissions = explode('|', $query);
        }

        return $permissions;
    }

    public function getGroupName($user_group_id)
    {
        $sql = "
				SELECT
					name
				FROM
					user_groups
				WHERE
					user_group_id = '" . (int)$user_group_id . "'
			";

        return DB::cell($sql);
    }

    public function getDisabled($user_group_id): bool
    {
        return ($user_group_id == 1) || ($user_group_id == 2);
    }
}