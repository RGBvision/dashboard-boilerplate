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



class ModelGroups extends Model
{
    /*
     |--------------------------------------------------------------------------------------
     | getGroups
     |--------------------------------------------------------------------------------------
     |
     |
     */
    public static function getGroups($exclude = '')
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
					" . PREFIX . "user_groups AS grp
				LEFT JOIN
					" . PREFIX . "users AS usr
					ON usr.user_group_id = grp.user_group_id
				WHERE
					grp.user_group_id NOT IN (1,2)
					AND grp.deleted != '1'
				$exclude
				GROUP BY
					grp.user_group_id ASC
			";

        $query = DB::Query($sql);

        $groups = array();

        while ($row = $query->getAssoc()) {
            $row['deleted'] = self::getDeleted($row['user_group_id'], $row['users']);

            $row['editable'] = self::getEditable($row['user_group_id']);

            array_push($groups, $row);
        }

        return $groups;
    }


    /*
     |--------------------------------------------------------------------------------------
     | getGroup
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function getDeleted($user_group_id, $count)
    {
        $deleted = ($user_group_id == 1 OR $user_group_id == 2 OR $count > 0 OR !Permission::perm('groups_delete'))
            ? false
            : true;

        return $deleted;
    }


    /*
     |--------------------------------------------------------------------------------------
     | saveGroup
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function getEditable($user_group_id)
    {
        $editable = (
            (UGROUP != 1 AND UGROUP == $user_group_id) OR
            (UGROUP != 1 AND $user_group_id == 1) OR
            (UGROUP != 1 AND $user_group_id == 2) OR
            (!Permission::perm('groups_edit'))
        );

        return $editable;
    }


    /*
     |--------------------------------------------------------------------------------------
     | saveGroup
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function saveGroup()
    {
        $save = true;

        $type = 'danger';
        $arg = array();

        //Router::demo();

        $Smarty = Tpl::getInstance();

        $user_group_id = Request::post('user_group_id');
        $permissions = Request::post('permissions');
        $action = Request::post('action');

        if ($permissions AND is_array($permissions))
            $permissions = implode('|', $permissions);

        if (!is_numeric($user_group_id))
            $save = false;

        if ($user_group_id == 1 OR $user_group_id == 2)
            $save = false;

        if (self::getEditable($user_group_id))
            $save = false;

        if ($save OR $action == 'add') {
            if ($user_group_id) {
                $sql = "
						UPDATE
							" . PREFIX . "user_groups
						SET
							permissions = '" . $permissions . "'
						" . (Request::post('user_group_name') ? ", name = '" . Request::post('user_group_name') . "'" : '') . "
						WHERE
							user_group_id = '" . $user_group_id . "'
					";

                DB::Query($sql);

                $message = $Smarty->_get('groups_message_edit_success');
                $type = 'success';
            } else {
                $sql = "
							INSERT INTO
								" . PREFIX . "user_groups
							SET
								permissions = '" . $permissions . "'
							" . (Request::post('user_group_name') ? ", name = '" . Request::post('user_group_name') . "'" : '') . "
						";

                DB::Query($sql);

                $user_group_id = DB::getInsertId();

                if ($user_group_id) {
                    $message = $Smarty->_get('groups_message_edit_success');
                    $type = 'success';
                    $arg = array('id' => $user_group_id);
                } else {
                    $message = $Smarty->_get('groups_message_edit_error');
                }
            }
        } else {
            $message = $Smarty->_get('groups_message_edit_error');
        }

        Router::response($type, $message, '/index.php?route=groups', $arg);
    }


    /*
     |--------------------------------------------------------------------------------------
     | getAllPermissions
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function deleteGroup()
    {
        //Router::demo();

        $user_group_id = Request::get('user_group_id');

        $type = 'danger';

        $delete = true;

        if (!$user_group_id)
            $delete = false;

        $Smarty = Tpl::getInstance();

        if ($delete) {
            $group = self::getGroup((int)$user_group_id);

            if ($group['deleted']) {
                $sql = "
						UPDATE
							" . PREFIX . "user_groups
						SET
							deleted = '1'
						WHERE
							user_group_id = '" . $user_group_id . "'
					";

                DB::Query($sql);

                $message = $Smarty->_get('groups_message_del_success');
                $type = 'success';
            } else {
                $message = $Smarty->_get('groups_message_del_perm_error');
            }
        } else {
            $message = $Smarty->_get('groups_message_del_id_error');
        }

        Router::response($type, $message, '/route/groups');
    }


    /*
     |--------------------------------------------------------------------------------------
     | getGroupPermissions
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function getGroup($user_group_id)
    {
        $sql = "
				SELECT
					grp.user_group_id,
					grp.name,
					COUNT(usr.user_id) AS users
				FROM
					" . PREFIX . "user_groups AS grp
				LEFT JOIN
					" . PREFIX . "users AS usr
					ON usr.user_group_id = grp.user_group_id
				WHERE
					grp.deleted != 1 AND
					grp.user_group_id NOT IN (1,2)
				AND 
					grp.user_group_id = '" . $user_group_id . "'
			";

        $group = DB::query($sql)->getAssoc();

        if ($group['user_group_id'] <= 0)
            return false;

        $group['deleted'] = self::getDeleted($group['user_group_id'], $group['users']);

        $group['editable'] = self::getEditable($group['user_group_id']);

        return $group;
    }


    /*
     |--------------------------------------------------------------------------------------
     | getGroupName
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function getAllPermissions($user_group_id = null)
    {
        $permissions = array();

        $_permissions = Permission::get();

        $group_permissons = self::getGroupPermissions($user_group_id);

        foreach ($_permissions AS $category => $permission) {
            $permissions[$category] = array();

            $permissions[$category]['name'] = 'perm_header_' . $category;

            if (is_array($permission['perm'])) {
                foreach ($permission['perm'] AS $perm) {
                    $permissions[$category]['perm'][$perm] = (in_array($perm, $group_permissons) ? true : false);
                }
            }

            if (isset($_permissions[$category]['icon']))
                $permissions[$category]['icon'] = $_permissions[$category]['icon'];

            $permissions[$category]['priority'] = $_permissions[$category]['priority'];
        }

        unset($_permissions, $group_permissons);

        return Arrays::multiSort($permissions, 'priority');
    }


    /*
     |--------------------------------------------------------------------------------------
     | getEditable
     |--------------------------------------------------------------------------------------
     | false -
     |
     */

    public static function getGroupPermissions($user_group_id)
    {
        $permissions = array();

        if (!empty($user_group_id)) {
            $sql = "
					SELECT
						permissions
					FROM
						" . PREFIX . "user_groups
					WHERE
						user_group_id = '" . (int)$user_group_id . "'
				";

            $query = DB::Query($sql)->getRow();

            $permissions = explode('|', $query);
        }

        return $permissions;
    }


    /*
     |--------------------------------------------------------------------------------------
     | getDisabled
     |--------------------------------------------------------------------------------------
     | false -
     |
     */

    public static function getGroupName($user_group_id)
    {
        $sql = "
				SELECT
					name
				FROM
					" . PREFIX . "user_groups
				WHERE
					user_group_id = '" . (int)$user_group_id . "'
			";

        $name = DB::Query($sql)->getRow();

        return $name;
    }


    /*
     |--------------------------------------------------------------------------------------
     | getDeleted
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function getDisabled($user_group_id)
    {
        $disabled = (
            ($user_group_id == 1) OR
            ($user_group_id == 2)
        );

        return $disabled;
    }
}