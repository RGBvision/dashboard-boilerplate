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

    public static function getUser(int $user_id)
    {

        $user = DB::row('
				SELECT
					usr.*,
					role.name AS role_name
				FROM
					users AS usr
				LEFT JOIN
					user_roles AS role
					ON usr.user_role_id = role.user_role_id
				WHERE
				    usr.user_id = ?
				LIMIT 1
			', $user_id);

        $user['deletable'] = User::isDeletable($user_id, $user['user_role_id']);
        $user['editable'] = User::isEditable($user_id, $user['user_role_id']);
        $user['user_avatar'] = User::getAvatar($user_id);

        return $user;
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
