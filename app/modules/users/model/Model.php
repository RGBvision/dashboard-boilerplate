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
 * @version    3.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ModelUsers extends Model
{

    public static function isDeletable($user_id, $user_group_id): bool
    {
        return (
            ($user_group_id !== 1) &&
            ($user_id !== UID) &&
            Permission::perm('users_delete')
        );
    }

    public static function isEditable($user_id, $user_group_id): bool
    {
        return (
            ((UGROUP != 1) && (UGROUP == $user_group_id)) ||
            ((UGROUP != 1) && ($user_group_id == 1)) ||
            ((UGROUP != 1) && ($user_group_id == 2)) ||
            Permission::perm('users_edit')
        );
    }

    public static function canAddUser(): bool
    {
        return Permission::perm('users_add');
    }

    public static function getUser($user_id)
    {
        $sql = "
				SELECT
					usr.*,
					grp.name AS group_name
				FROM
					users AS usr
				LEFT JOIN
					user_groups AS grp
					ON usr.user_group_id = grp.user_group_id
				WHERE
				    usr.user_id = " . (int)$user_id . "
				LIMIT 1
			";

        $user = DB::row($sql);

        $user['deletable'] = self::isDeletable($user_id, $user['user_group_id']);
        $user['editable'] = self::isEditable($user_id, $user['user_group_id']);
        $user['user_avatar'] = User::getAvatar((int)$user_id);

        return $user;
    }

    public static function getDisabled($user_id, $user_group_id)
    {
        $disabled = (
            ($user_group_id == 1) or
            ($user_group_id == 2)
        );

        return $disabled;
    }

    
    public static function getGroups(): array
    {

        $res = [];

        $rows = DB::Query("SELECT * FROM user_groups WHERE user_group_id NOT IN (1,2) AND deleted = 0");

        foreach ($rows as $row) {
            $res[] = $row;
        }

        return $res;
    }

}
