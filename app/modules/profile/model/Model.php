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

class ProfileModel extends Model
{

    /**
     * Get user data
     *
     * @param $user_id
     * @return mixed
     */
    public function getUser($user_id)
    {
        $sql = "
				SELECT
					usr.*,
					role.name AS role_name
				FROM
					users AS usr
				LEFT JOIN
					user_roles AS role
					ON usr.user_role_id = role.user_role_id
				WHERE
				    usr.user_id = " . (int)$user_id . "
				LIMIT 1
			";

        $user = DB::row($sql);

        $user['user_avatar'] = User::getAvatar((int)$user_id);

        return $user;
    }

}