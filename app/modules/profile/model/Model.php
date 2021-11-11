<?php


class ModelProfile extends Model
{

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

        $user['user_avatar'] = User::getAvatar((int)$user_id);

        return $user;
    }

}