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
 * @version    2.42
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class User
{

    const SUPERUSER = 1;

    protected static array $sortable_fields = ['lastname', 'phone', 'email', 'group_name', 'last_activity'];

    public static function isDeletable($user_id, $user_group_id): bool
    {
        return (
            ($user_group_id !== UserGroup::SUPERADMIN) &&
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

    public static function getList(string $sort = 'lastname', string $order = 'ASC', int $limit = null, int $start = null, string $search = null): array
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
            $_like = DB::buildSearch(['usr.firstname', 'usr.lastname', 'usr.phone', 'usr.email', 'grp.name'], $search);
            $like = ($_like) ? " AND ($_like)" : '';
        }

        $where = 'usr.user_id IS NOT NULL';

        if (UGROUP !== UserGroup::SUPERADMIN) {
            $where = 'grp.user_group_id != ' . UserGroup::SUPERADMIN;
        }

        $rows = DB::Query("
				SELECT
					usr.user_id, usr.country_code, usr.phone, usr.email, usr.firstname, usr.lastname, usr.active, usr.last_activity, usr.deleted, usr.del_time, usr.settings,
				    grp.user_group_id AS group_id,
					grp.name AS group_name
				FROM
					users AS usr
				LEFT JOIN
					user_groups AS grp
					ON usr.user_group_id = grp.user_group_id
				WHERE $where $like				
				ORDER BY $sort $order, usr.lastname
                $limits
			");

        $users = [];

        foreach ($rows as $row) {

            $row['phone'] = Valid::internationalPhone($row['phone'], $row['country_code']);

            $row['deletable'] = self::isDeletable($row['user_id'], $row['user_group_id']) and ($row['owner'] != '1');
            $row['editable'] = self::isEditable($row['user_id'], $row['user_group_id']);
            $row['avatar'] = self::getAvatar((int)$row['user_id']);

            array_push($users, $row);
        }

        return $users;
    }


    public static function total(string $search = null): int
    {
        $like = '';

        if ($search) {
            $_like = DB::buildSearch(['usr.firstname', 'usr.lastname', 'usr.phone', 'usr.email', 'grp.name'], $search);
            $like = ($_like) ? " AND ($_like)" : '';
        }

        $where = 'usr.user_id IS NOT NULL';

        if (UGROUP !== UserGroup::SUPERADMIN) {
            $where = 'grp.user_group_id != ' . UserGroup::SUPERADMIN;
        }

        return (int)DB::cell("
            SELECT COUNT(usr.user_id) 
            FROM users AS usr
			LEFT JOIN user_groups AS grp ON usr.user_group_id = grp.user_group_id
			WHERE $where $like
        ");
    }

    public static function getAvatar(int $id): string
    {

        if ($file = File::find(DASHBOARD_DIR . '/uploads/avatars/' . md5($id) . '_*.jpg')[0]) {
            $user_avatar = HOST . '/uploads/avatars/' . File::basename($file) . '?v=' . File::lastChange($file);
        } else {
            $user_avatar = HOST . '/uploads/avatars/default.jpg';
        }

        return $user_avatar;
    }

    public static function saveAvatar(int $id, string $photo): bool
    {
        $img_decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $photo));

        if ($img_decoded != false) {

            $_tmp_file = DASHBOARD_DIR . TEMP_DIR . '/uploads/' . md5($id) . '.jpg';
            $_new_file = DASHBOARD_DIR . '/uploads/avatars/' . md5($id) . '_' . sprintf('%08x', time()) . '.jpg';

            File::putContents($_tmp_file, $img_decoded);

            if (File::mime($_tmp_file) === 'image/jpeg') {

                // Delete all old avatars
                foreach (File::find(DASHBOARD_DIR . '/uploads/avatars/' . md5($id) . '_*.jpg') as $file) {
                    File::delete($file);
                }

                File::rename($_tmp_file, $_new_file);

                Log::log(Log::INFO, 'System\User', "Avatar for user ($id) saved");

                if ($id === UID) {
                    Session::setvar('user_avatar', self::getAvatar($id));
                }

                return true;
            }

            File::delete($_tmp_file);

        }

        Log::log(Log::ERROR, 'System\User', "Error saving avatar for user ($id): wrong image data");
        return false;

    }

    public static function saveUser(?int $id, string $firstname, string $lastname, string $country_code, string $phone, string $email, ?string $pass = null, int $group = 2, ?string $photo = null, bool $send_email = false): bool
    {

        if ($id) { // Save

            // Check if email or phone already used by another user
            if (self::isEmailUsed($email, $id) || self::isPhoneUsed($phone, $country_code, $id)) {
                return false;
            }

            DB::update(
                "users",
                [
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "country_code" => $country_code,
                    "phone" => $phone,
                    "email" => $email,
                    "user_group_id" => $group,
                ],
                [
                    "user_id" => $id
                ]
            );

            if ($pass) { // Update pass

                $salt = randomString();
                $password_hash = password_hash(hash_hmac("sha256", $pass, $salt . PWD_PEPPER), PASSWORD_ARGON2ID);

                DB::update(
                    "users",
                    [
                        "password" => $password_hash,
                        "salt" => $salt
                    ],
                    [
                        "user_id" => $id
                    ]
                );

            }

        } else { // Add

            // Check if email or phone already used by another user
            if (self::isEmailUsed($email) || self::isPhoneUsed($phone, $country_code)) {
                return false;
            }

            // Password required for new users
            if (!$pass) {
                return false;
            }

            $salt = randomString();
            $password_hash = password_hash(hash_hmac("sha256", $pass, $salt . PWD_PEPPER), PASSWORD_ARGON2ID);

            $id = DB::insertGet(
                "users",
                [
                    "user_group_id" => $group,
                    "email" => $email,
                    "country_code" => $country_code,
                    "phone" => $phone,
                    "password" => $password_hash,
                    "salt" => $salt,
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "active" => 1,
                    "settings" => "{}"
                ],
                "user_id"
            );

        }

        if ($photo && $id) {
            self::saveAvatar($id, $photo);
        }

        $res = ($id > 0);

        Log::log($res ? Log::INFO : Log::ERROR, 'System\User', $res ? "User ($id) data saved or updated" : "Error saving user data ($id)");

        return $res;

    }

    public static function delete(int $id): bool
    {
        return DB::update(
                "users",
                [
                    "active" => 0,
                    "deleted" => 1,
                    "del_time" => time()
                ],
                [
                    "user_id" => $id
                ]
            ) > 0;
    }

    public static function restore(int $id): bool
    {
        return DB::update(
                "users",
                [
                    "active" => 1,
                    "deleted" => 0,
                    "del_time" => null
                ],
                [
                    "user_id" => $id
                ]
            ) > 0;
    }

    public static function block(int $id): bool
    {
        return DB::update(
                "users",
                [
                    "active" => 0
                ],
                [
                    "user_id" => $id
                ]
            ) > 0;
    }

    public static function unblock(int $id): bool
    {
        return DB::update(
                "users",
                [
                    "active" => 1
                ],
                [
                    "user_id" => $id
                ]
            ) > 0;
    }

    public static function isPhoneUsed(string $phone, string $country_code, int $exclude_user_id = 0): bool
    {
        return DB::exists("SELECT user_id FROM users WHERE country_code = ? AND phone = ? AND user_id != ?", $country_code, $phone, $exclude_user_id);
    }

    public static function isEmailUsed(string $email, int $exclude_user_id = 0): bool
    {
        return DB::exists("SELECT user_id FROM users WHERE email = ? AND user_id != ?", $email, $exclude_user_id);
    }

}