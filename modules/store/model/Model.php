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



class ModelStore extends Model
{

    public static function getStore()
    {

        $sql = "
				SELECT
					*
				FROM
					" . PREFIX . "goods
				WHERE
					active = '1'
				AND
				    type = '1'
				AND
				    organization_id = '" . ORGID . "'
			";

        $query = DB::Query($sql);

        $users = array();

        while ($row = $query->getAssoc()) {
            array_push($users, $row);
        }

        return $users;
    }

    public static function getConsumables()
    {

        $sql = "
				SELECT
					*
				FROM
					" . PREFIX . "goods
				WHERE
					active = '1'
				AND
				    type = '0'
				AND
				    organization_id = '" . ORGID . "'
			";

        $query = DB::Query($sql);

        $consumables = array();

        while ($row = $query->getAssoc()) {
            $row['sum'] = floatval($row['cost']) * floatval($row['count']);
            array_push($consumables, $row);
        }

        return $consumables;
    }

    public static function getGood()
    {

        $sql = "
				SELECT
					*
				FROM
					" . PREFIX . "goods
				WHERE
					active = '1'
				AND
				    good_id = '" . (int)Request::get('good_id') . "'
				AND
				    organization_id = '" . ORGID . "'
			";

        $good = DB::Query($sql)->getAssoc();

        return $good;
    }

    public static function saveGood()
    {
        $save = true;

        $type = 'danger';

        $Smarty = Tpl::getInstance();

        $good_id = Request::post('good_id');
        $action = Request::post('action');

        if (!is_numeric($good_id))
            $save = false;

        if (($save OR $action == 'add') AND Permission::perm('store_edit')) {

            $old_params = array(
                'department' => intval(Request::post('department_src')),
                'type' => intval(Request::post('type')),
                'cost' => floatval(Request::post('cost_src')),
                'net' => floatval(Request::post('net_src')),
                'markup' => floatval(Request::post('markup_src')),
                'count' => floatval(Request::post('count_src'))
            );

            $new_params = array(
                'department' => intval(Request::post('department')),
                'type' => intval(Request::post('type')),
                'cost' => floatval(Request::post('cost')),
                'net' => floatval(Request::post('net')),
                'markup' => floatval(Request::post('markup')),
                'count' => floatval(Request::post('count'))
            );

            if ($good_id) {

                if ($old_params != $new_params) {

                    $new_type = (($new_params['type'] == $old_params['type']) ? 'NULL' : "'" . $new_params['type'] . "'");
                    $new_department = (($new_params['department'] == $old_params['department']) ? 'NULL' : "'" . $new_params['department'] . "'");

                    $sql = "
						INSERT INTO
                            " . PREFIX . "goods_movement
						SET
                            type = $new_type,
                            department = $new_department,
                            cost = " . ($new_params['cost'] - $old_params['cost']) . ",
                            net = " . ($new_params['net'] - $old_params['net']) . ",
                            markup = " . ($new_params['markup'] - $old_params['markup']) . ",
                            `count` = " . ($new_params['count'] - $old_params['count']) . ",
                            `timestamp` = " . time() . ",
							good_id = " . $good_id . ",
							user_id = " . UID . ",
							forced = '1',
							organization_id = " . ORGID . "
					";

                    DB::Query($sql);

                }

                $sql = "
						UPDATE
							" . PREFIX . "goods
						SET
                            name = '" . Request::post('name') . "',
                            description = '" . addslashes(htmlspecialchars(Request::post('description'))) . "',
                            cost = " . $new_params['cost'] . ",
                            net = " . $new_params['net'] . ",
                            markup = " . $new_params['markup'] . ",
                            `count` = `count` + " . ($new_params['count'] - $old_params['count']) . ",
                            lastupdate = " . time() . "
						WHERE
							good_id = " . $good_id . "
						AND
							organization_id = " . ORGID . "
					";

                DB::Query($sql);

                $message = $Smarty->_get('services_message_edit_success');
                $type = 'success';

            } else {

                $sql = "
                    INSERT INTO
                        " . PREFIX . "goods
                    SET
                        `name` = '" . Request::post('name') . "',
                        description = '" . addslashes(htmlspecialchars(Request::post('description'))) . "',
                        type = '" . $new_params['type'] . "',
                        department = '" . $new_params['department'] . "',
                        cost = " . $new_params['cost'] . ",
                        net = " . $new_params['net'] . ",
                        markup = " . $new_params['markup'] . ",
                        `count` = " . $new_params['count'] . ",
                        unit = '" . Request::post('unit') . "',
                        organization_id = " . ORGID . ",
                        lastupdate = '" . time() . "'
                    ";

                DB::Query($sql);

                $good_id = DB::getInsertId();

                if ($good_id) {

                    $sql = "
                        INSERT INTO
                            " . PREFIX . "goods_movement
                        SET
                            type = '" . $new_params['type'] . "',
                            department = '" . $new_params['department'] . "',
                            cost = " . $new_params['cost'] . ",
                            net = " . $new_params['net'] . ",
                            markup = " . $new_params['markup'] . ",
                            `count` = " . $new_params['count'] . ",
                            `timestamp` = " . time() . ",
                            good_id = " . $good_id . ",
                            user_id = " . UID . ",
                            forced = '1',
                            organization_id = " . ORGID . "
                    ";

                    DB::Query($sql);

                    $message = $Smarty->_get('services_message_edit_success');
                    $type = 'success';
                } else {
                    $message = $Smarty->_get('services_message_edit_error');
                }
            }
        } else {
            $message = $Smarty->_get('services_message_edit_error');
        }

        Router::response($type, $message, '/route/store/consumables');
    }

    /*
     |--------------------------------------------------------------------------------------
     | getEditable
     |--------------------------------------------------------------------------------------
     | false -
     |
     */
    public static function getEditable($user_group_id)
    {
        $editable = (
            (UGROUP != 1 AND UGROUP == $user_group_id) OR
            (UGROUP != 1 AND $user_group_id == 1) OR
            (UGROUP != 1 AND $user_group_id == 2) OR
            (!Permission::perm('store_edit'))
        );

        return $editable;
    }


    /*
     |--------------------------------------------------------------------------------------
     | getDisabled
     |--------------------------------------------------------------------------------------
     | false -
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


    /*
     |--------------------------------------------------------------------------------------
     | getDeleted
     |--------------------------------------------------------------------------------------
     |
     |
     */
    public static function getDeleted($user_group_id)
    {
        $deleted = ($user_group_id == 1 OR $user_group_id == 2 OR !Permission::perm('store_delete'))
            ? false
            : true;

        return $deleted;
    }

}