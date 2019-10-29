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



class ModelUsers extends Model
{

    public static function getUsers($exclude = '')
    {
        $exclude = ($exclude != '' && is_numeric($exclude))
            ? "AND usr.user_group_id != '" . $exclude . "'"
            : '';

        $sql = "
				SELECT
					usr.*,
					grp.name AS group_name
				FROM
					" . PREFIX . "users AS usr
				LEFT JOIN
					" . PREFIX . "user_groups AS grp
					ON usr.user_group_id = grp.user_group_id
				WHERE
					usr.deleted != 1
				AND usr.organization_id = " . ORGID . "
				$exclude
				ORDER BY
					usr.lastname ASC
			";

        $query = DB::Query($sql);

        $users = array();

        while ($row = $query->getAssoc()) {
            $row['deletable'] = (self::getDeletable($row['user_id'], $row['user_group_id']) AND ($row['owner'] != '1'));
            $row['editable'] = self::getEditable($row['user_id'], $row['user_group_id']);
            $row['avatar'] = (file_exists(CP_DIR . '/uploads/avatars/' . md5((int)$row['user_id']) . '-' . md5((int)ORGID) . '.jpg')) ?
                ('/uploads/avatars/' . md5((int)$row['user_id']) . '-' . md5((int)ORGID) . '.jpg') : '/uploads/avatars/default.jpg';

            array_push($users, $row);
        }

        return $users;
    }

    public static function getDeletable($user_id, $user_group_id)
    {
        $deletable = ($user_group_id == 1 OR $user_group_id == 2 OR $user_id == UID OR !Permission::perm('users_delete'))
            ? false
            : true;

        return $deletable;
    }

    public static function getEditable($user_id, $user_group_id)
    {
        $editable = (
            (UGROUP != 1 AND UGROUP == $user_group_id) OR
            (UGROUP != 1 AND $user_group_id == 1) OR
            (UGROUP != 1 AND $user_group_id == 2) OR
            (Permission::perm('users_edit'))
        );

        return $editable;
    }

    public static function canAddUser()
    {
        $max_users = DB::Query("
            SELECT
              plan.max_users
            FROM " . PREFIX . "organizations AS org
            LEFT JOIN
                " . PREFIX . "plans AS plan
                ON org.plan_id = plan.plan_id
            WHERE
                organization_id = " . ORGID . "
        ")->getRow();

        $user_count = DB::Query("
            SELECT
              COUNT(*)
            FROM " . PREFIX . "users
            WHERE
                organization_id = " . ORGID . " AND
                active = '1' AND
                user_group_id != '1'
        ")->getRow();

        return ((int)$user_count < (int)$max_users);
    }

    public static function getUser($user_id)
    {
        $sql = "
				SELECT
					usr.*,
					grp.name AS group_name,
					emp.firstname AS emp_firstname,
					emp.lastname AS emp_lastname,
					emp.employee_id AS emp_id
				FROM
					" . PREFIX . "users AS usr
				LEFT JOIN
					" . PREFIX . "user_groups AS grp
					ON usr.user_group_id = grp.user_group_id
				LEFT JOIN
					" . PREFIX . "employees AS emp
					ON usr.linked_employee = emp.employee_id
				WHERE
				    usr.user_id = " . (int)$user_id . "
				AND
					usr.organization_id = " . ORGID . "
				LIMIT 1
			";

        $user = DB::Query($sql)->getAssoc();

        $user['deletable'] = self::getDeletable($user_id, $user['user_group_id']);
        $user['editable'] = self::getEditable($user_id, $user['user_group_id']);
        $user['linked'] = isset($user['emp_id']) AND ($user['emp_id'] > 0);

        if (file_exists(CP_DIR . '/uploads/avatars/' . md5((int)$user_id) . '-' . md5((int)ORGID) . '.jpg')) {
            $user['user_avatar'] = '/uploads/avatars/' . md5((int)$user_id) . '-' . md5((int)ORGID) . '.jpg';
        }

        return $user;
    }

    public static function getGroups($exclude = '')
    {
        $exclude = ($exclude != '' && is_numeric($exclude))
            ? "AND user_group_id != '" . $exclude . "'"
            : '';

        $sql = "
				SELECT
					user_group_id,
					name
				FROM
					" . PREFIX . "user_groups
				WHERE
					deleted != 1 AND
					user_group_id NOT IN (1,2)
				$exclude
				ORDER BY name ASC
			";

        $query = DB::Query($sql);

        $groups_ids = array();
        $groups_names = array();

        while ($row = $query->getAssoc()) {
            array_push($groups_ids, $row['user_group_id']);
            array_push($groups_names, $row['name']);
        }

        return array(
            'ids' => $groups_ids,
            'names' => $groups_names
        );
    }

    public static function getEmployees()
    {

        $query = DB::Query("
				SELECT
					*
				FROM
					" . PREFIX . "employees
				WHERE
				    active = '1'
				AND
					deleted = '0'
                AND
                    organization_id = " . ORGID . "
                AND employee_id NOT IN (
                        SELECT linked_employee 
                        FROM " . PREFIX . "users
                        WHERE linked_employee > 0
                        AND organization_id = " . ORGID . "
                    )
				ORDER BY lastname ASC
			");

        $employees = array();

        while ($row = $query->getAssoc()) {
            array_push($employees, $row);
        }

        return $employees;
    }

    public static function getDisabled($user_id, $user_group_id)
    {
        $disabled = (
            ($user_group_id == 1) OR
            ($user_group_id == 2)
        );

        return $disabled;
    }

    /*
     |--------------------------------------------------------------------------------------
     | saveUser
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function saveUser()
    {
        $save = true;

        $type = 'danger';
        $arg = array();

        $Smarty = Tpl::getInstance();

        $user_id = Request::post('user_id');
        $user_group_id = Request::post('group');
        $action = Request::post('action');

        if (!is_numeric($user_id) OR !is_numeric($user_group_id))
            $save = false;

        if ((UGROUP != 1 AND ((int)$user_group_id < 3)))
            $save = false;

        if (!Permission::perm('users_edit'))
            $save = false;

        if ($save OR $action == 'add') {

            if ($user_id) {

                $change_pass = '';

                if (!empty(Request::post('password')) AND (Request::post('password') != '******')) {
                    $salt = randomString();
                    $password_hash = md5(md5(Secure::sanitize(Request::post('password')) . $salt));
                    $change_pass = ", password = '" . $password_hash . "', salt = '" . $salt . "'";
                }

                $sql = "
						UPDATE
							" . PREFIX . "users
						SET
							name = '" . normalizePhone(Request::post('phone')) . "',
							firstname = '" . Secure::sanitize(Request::post('firstname')) . "',
							lastname = '" . Secure::sanitize(Request::post('lastname')) . "',
							phone = '" . normalizePhone(Request::post('phone')) . "',
							linked_employee = " . ((intval(Request::post('linked')) > 0) ? intval(Request::post('linked')) : 'NULL') . ",
							description = '" . addslashes(htmlspecialchars(Request::post('description'))) . "',
							user_group_id = '" . $user_group_id . "'" . $change_pass . "
						WHERE
							user_id = '" . $user_id . "'
						AND
						    organization_id = " . ORGID . "
						    
					";

                DB::Query($sql);

                $message = $Smarty->_get('users_message_edit_success');
                $type = 'success';
            } else {
                $salt = randomString();
                $password_hash = md5(md5(Secure::sanitize(Request::post('password')) . $salt));
                $sql = "
                        INSERT INTO
                            " . PREFIX . "users
                        SET
                            name = '" . normalizePhone(Request::post('phone')) . "',
							firstname = '" . Secure::sanitize(Request::post('firstname')) . "',
							lastname = '" . Secure::sanitize(Request::post('lastname')) . "',
							phone = '" . normalizePhone(Request::post('phone')) . "',
							linked_employee = " . ((intval(Request::post('linked')) > 0) ? intval(Request::post('linked')) : 'NULL') . ",
							description = '" . addslashes(htmlspecialchars(Request::post('description'))) . "',
							user_group_id = '" . $user_group_id . "',
							organization_id = '" . ORGID . "',
							password = '" . $password_hash . "',
							salt = '" . $salt . "',
							reg_time = '" . time() . "'
						";

                DB::Query($sql);

                $user_id = DB::getInsertId();

                if ($user_id) {
                    $message = $Smarty->_get('users_message_edit_success');
                    $type = 'success';
                    $arg = array('id' => $user_id);
                } else {
                    $message = $Smarty->_get('users_message_edit_error');
                }
            }

            if (!empty(Request::post('new_avatar'))) {
                $img_decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', Request::post('new_avatar')));
                if ($img_decoded != false) {
                    file_put_contents(CP_DIR . '/uploads/avatars/' . md5((int)$user_id) . '-' . md5((int)ORGID) . '.jpg', $img_decoded);
                    if ($user_id == UID) {
                        Session::setvar('user_avatar', '/uploads/avatars/' . md5((int)$user_id) . '-' . md5((int)ORGID) . '.jpg');
                    }
                }
            }

        } else {
            $message = $Smarty->_get('users_message_edit_error');
        }

        Router::response($type, $message, '/route/users', $arg);
    }

    /*
    |--------------------------------------------------------------------------------------
    | saveUser
    |--------------------------------------------------------------------------------------
    |
    |
    */

    public static function deleteUser()
    {
        $delete = true;

        $type = 'danger';
        $arg = array();

        $Smarty = Tpl::getInstance();

        $user_id = Request::get('user_id');

        if (!is_numeric($user_id))
            $delete = false;

        if (!Permission::perm('users_delete'))
            $delete = false;

        if ($delete) {

            $sql = "
                UPDATE
                    " . PREFIX . "users
                SET
                    name = CONCAT('deleted_by_".UID."___', name),
                    email = CONCAT('deleted_by_".UID."___', email),
                    phone = CONCAT('deleted_by_".UID."___', phone),
                    active = '0',
                    deleted = '1',
                    del_time = '" . time() . "'
                WHERE
                    user_id = '" . $user_id . "'
                AND
                    owner != '1'
                AND
                    organization_id = " . ORGID . "
            ";

            DB::Query($sql);

            $message = $Smarty->_get('users_message_edit_success');
            $type = 'success';

        } else {
            $message = $Smarty->_get('users_message_edit_error');
        }

        Router::response($type, $message, '/route/users', $arg);
    }

    public static function checkUserPhone()
    {

        $type = 'danger';
        $message = '';

        if (!empty(Request::post('phone'))) {

            if (Request::post('phone') == Request::post('current_phone')) {
                $type = 'success';
            } elseif (Request::post('phone')) {
                $sql = "
				SELECT
					COUNT(*)
				FROM
					" . PREFIX . "users
				WHERE
					phone = '" . normalizePhone(Request::post('phone')) . "'
			";

                $type = ((int)DB::Query($sql)->getRow() == 0) ? 'success' : 'danger';
            }
        }

        Router::response($type, $message);
    }

}