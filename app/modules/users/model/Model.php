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

class UsersModel extends Model
{

    public static function isDeletable($user_id, $user_role_id): bool
    {
        return (
            ($user_role_id !== 1) &&
            ($user_id !== UID) &&
            Permissions::has('users_delete')
        );
    }

    public static function isEditable($user_id, $user_role_id): bool
    {
        return (
            ((UROLE != 1) && (UROLE == $user_role_id)) ||
            ((UROLE != 1) && ($user_role_id == 1)) ||
            ((UROLE != 1) && ($user_role_id == 2)) ||
            Permissions::has('users_edit')
        );
    }

    public static function canAddUser(): bool
    {
        return Permissions::has('users_add');
    }

    public static function getUser($user_id)
    {
        $sql = "
				SELECT
					usr.*,
					grp.name AS role_name
				FROM
					users AS usr
				LEFT JOIN
					user_roles AS grp
					ON usr.user_role_id = grp.user_role_id
				WHERE
				    usr.user_id = " . (int)$user_id . "
				LIMIT 1
			";

        $user = DB::row($sql);

        $user['deletable'] = self::isDeletable($user_id, $user['user_role_id']);
        $user['editable'] = self::isEditable($user_id, $user['user_role_id']);
        $user['user_avatar'] = User::getAvatar((int)$user_id);

        return $user;
    }

    public static function getDisabled($user_id, $user_role_id)
    {
        $disabled = (
            ($user_role_id == 1) or
            ($user_role_id == 2)
        );

        return $disabled;
    }

    
    public static function getRoles(): array
    {

        $res = [];

        $rows = DB::Query("SELECT * FROM user_roles WHERE user_role_id NOT IN (1,2) AND deleted = 0");

        foreach ($rows as $row) {
            $res[] = $row;
        }

        return $res;
    }

}
