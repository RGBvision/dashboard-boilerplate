<?php


class ModelGroups extends Model
{

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

    public static function getDeletable($user_group_id, $count)
    {
        return !(
            ($user_group_id == 1) ||
            ($user_group_id == 2) ||
            ($count > 0) ||
            !Permission::perm('groups_delete')
        );
    }

    public static function isEditable($user_group_id)
    {
        return (
            ((UGROUP != 1) && ($user_group_id == 1)) ||
            ((UGROUP != 1) && ($user_group_id == 2)) ||
            Permission::perm('groups_edit')
        );
    }

    public static function saveGroup()
    {
        $save = true;

        $type = 'danger';
        $arg = array();


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
                    $arg = array('id' => $user_group_id);
                } else {
                    $message = $Template->_get('groups_message_edit_error');
                }
            }
        } else {
            $message = $Template->_get('groups_message_edit_error');
        }

        Router::response($type === 'success', $message, ABS_PATH . 'groups', $arg);
    }

    public static function deleteGroup()
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

    public static function getGroup($user_group_id)
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

    public static function getAllPermissions($user_group_id = null)
    {
        $permissions = array();

        $_permissions = Permission::get();

        $group_permissons = self::getGroupPermissions($user_group_id);

        foreach ($_permissions as $category => $permission) {
            $permissions[$category] = array();

            $permissions[$category]['name'] = 'perm_header_' . $category;

            if (is_array($permission['perm'])) {
                foreach ($permission['perm'] as $perm) {
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

    public static function getGroupPermissions($user_group_id)
    {
        $permissions = array();

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

    public static function getGroupName($user_group_id)
    {
        $sql = "
				SELECT
					name
				FROM
					user_groups
				WHERE
					user_group_id = '" . (int)$user_group_id . "'
			";

        $name = DB::cell($sql);

        return $name;
    }

    public static function getDisabled($user_group_id)
    {
        $disabled = (
            ($user_group_id == 1) or
            ($user_group_id == 2)
        );

        return $disabled;
    }
}