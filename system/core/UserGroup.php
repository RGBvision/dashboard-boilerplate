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
 * @version    2.19
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class UserGroup
{

    const SUPERADMIN = 1;
    const ANONYMOUS = 2;

    protected static array $sortable_fields = ['id', 'name', 'users'];

    public static function isDeletable($user_group_id, $count)
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

    public static function getList(string $sort = 'id', string $order = 'ASC', int $limit = null, int $start = null, string $search = null): array
    {

        if (!in_array($sort, self::$sortable_fields)) {
            $sort = self::$sortable_fields[0];
        }

        $limits = '';

        if (!is_null($limit) && !is_null($start)) {
            $limits = "LIMIT $start, $limit";
        }

        $like = '';

        if ($search) {
            $_like = DB::buildSearch(['grp.name'], $search);
            $like = ($_like) ? " AND ($_like)" : '';
        }

        $where = 'grp.user_group_id != ' . self::ANONYMOUS;

        if (UGROUP !== self::SUPERADMIN) {
            $where = 'grp.user_group_id NOT IN (' . self::ANONYMOUS . ',' . self::SUPERADMIN . ')';
        }

        $res = [];

        $rows = DB::Query("
            SELECT 
                grp.*, grp.user_group_id AS id,
				COUNT(usr.user_id) AS users
            FROM
                user_groups grp
            LEFT JOIN
                users AS usr
                ON usr.user_group_id = grp.user_group_id AND usr.deleted = 0            
            WHERE $where $like
            GROUP BY grp.user_group_id
            ORDER BY $sort $order, grp.user_group_id ASC
            $limits
        ");

        foreach ($rows as $row) {
            $row['deletable'] = self::isDeletable($row['user_group_id'], $row['users']);
            $row['editable'] = self::isEditable($row['user_group_id']);
            $res[] = $row;
        }

        return $res;
    }

}