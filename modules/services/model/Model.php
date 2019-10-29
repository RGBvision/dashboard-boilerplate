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



class ModelServices extends Model
{

    public static function getServices()
    {

        $department = array(
            0 => 'Любой',
            1 => 'Мойка',
            2 => 'Сервис',
            3 => 'Касса'
        );

        $sql = "
				SELECT
					*
				FROM
					" . PREFIX . "services
				WHERE
					deleted = '0'
				AND
				    active = '1'
				AND 
					organization_id = '" . ORGID . "'
				ORDER BY
					sort ASC
			";

        $query = DB::Query($sql);

        $services = array();

        while ($row = $query->getAssoc()) {
            $row['department'] = $department[(int)$row['department']];
            array_push($services, $row);
        }

        return $services;
    }


    /*
     |--------------------------------------------------------------------------------------
     | getService
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function getService($service_id)
    {
        $sql = "
				SELECT
					*
				FROM
					" . PREFIX . "services
				WHERE
					deleted = '0'
				AND 
					service_id = '" . $service_id . "'
				AND 
					organization_id = '" . ORGID . "'
			";

        $service = DB::query($sql)->getAssoc();

        if ($service['service_id'] <= 0)
            return false;

        $calculation = array();

        for ($i = 0; $i <= $_SESSION['organization_settings']['classes']; $i++) {
            $calculation[$i] = array('cost' => 0, 'time_limit' => '0', 'reward' => 0, 'measure' => 1, 'salary' => 0, 'unit' => 1);
        }

        $calculation['parametric'][0] = array('name'=> '', 'cost' => 0, 'time_limit' => '0', 'reward' => 0, 'measure' => 1, 'salary' => 0, 'unit' => 1);

        $service['calculation'] = array_replace_recursive($calculation, unserialize($service['calculation']));

        return $service;
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
				ORDER BY `name`
			";

        $query = DB::Query($sql);

        $consumables = array();

        while ($row = $query->getAssoc()) {
            $consumables[$row['good_id']] = $row;
        }

        return $consumables;
    }

    /*
     |--------------------------------------------------------------------------------------
     | saveService
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function saveService()
    {
        $save = true;

        $type = 'danger';

        $Smarty = Tpl::getInstance();

        $service_id = Request::post('service_id');
        $action = Request::post('action');

        if (!is_numeric($service_id))
            $save = false;

        if (($save OR $action == 'add') AND Permission::perm('services_edit')) {

            $calculation_src = Request::post('calculation');
            $calculation = array();

            switch (Request::post('type')) {
                case '1':
                    $calculation[0] = $calculation_src[0];
                    break;
                case '2':
                    unset($calculation_src[0]);
                    unset($calculation_src['parametric']);
                    $calculation = $calculation_src;
                    break;
                case '3':
                    unset($calculation_src['parametric']['index']);
                    $calculation['parametric'] = $calculation_src['parametric'];
                    break;
            }

            if ($service_id) {
                $sql = "
						UPDATE
							" . PREFIX . "services
						SET
                            name = '" . Request::post('service_name') . "',
                            description = '" . addslashes(htmlspecialchars(Request::post('description'))) . "',
                            prime = '" . (Request::post('prime') ? 1 : 0) . "',
                            bonus = '" . (Request::post('bonus') ? 1 : 0) . "',
                            type = '" . intval(Request::post('type')) . "',
                            department = '" . Request::post('department') . "',
                            max_count = '" . intval(Request::post('max_count')) . "',
                            calculation = '" . serialize($calculation) . "',
                            lastupdate = '" . time() . "'
						WHERE
							service_id = '" . $service_id . "' AND
							organization_id = '" . ORGID . "'
					";

                DB::Query($sql);

                $message = $Smarty->_get('services_message_edit_success');
                $type = 'success';

            } else {

                $sql = "
                        INSERT INTO
                            " . PREFIX . "services
                        SET
                            organization_id = '" . ORGID . "',
                            name = '" . Request::post('service_name') . "',
                            description = '" . addslashes(htmlspecialchars(Request::post('description'))) . "',
                            prime = '" . (Request::post('prime') ? 1 : 0) . "',
                            bonus = '" . (Request::post('bonus') ? 1 : 0) . "',
                            type = '" . intval(Request::post('type')) . "',
                            department = '" . Request::post('department') . "',
                            max_count = '" . intval(Request::post('max_count')) . "',
                            calculation = '" . serialize($calculation) . "',
                            lastupdate = '" . time() . "'
						";

                DB::Query($sql);

                $service_id = DB::getInsertId();

                if ($service_id) {
                    $message = $Smarty->_get('services_message_edit_success');
                    $type = 'success';
                } else {
                    $message = $Smarty->_get('services_message_edit_error');
                }
            }
        } else {
            $message = $Smarty->_get('services_message_edit_error');
        }

        Router::response($type, $message, '/route/services');
    }

    public static function sort()
    {
        if (Request::isAjax() AND Request::post('data')) {

            $type = 'danger';
            $arg = array();
            $message = '';

            $ids = explode(',', Request::post('data'));

            foreach ($ids as $k => $v) {
                if (is_numeric($v)) {
                    DB::Query("UPDATE " . PREFIX . "services SET sort = '". ((int)$k + 1) . "' WHERE service_id = '". (int)$v . "' AND organization_id = '" . ORGID . "'; ");
                    $type = 'success';
                }
            }

            Router::response($type, $message, '/route/services', $arg);
        }
    }

    public static function deleteService()
    {
        $delete = true;

        $type = 'danger';
        $arg = array();

        $Smarty = Tpl::getInstance();

        $service_id = Request::get('service_id');

        if (!is_numeric($service_id))
            $delete = false;

        if (!Permission::perm('services_delete'))
            $delete = false;

        if ($delete) {

            $sql = "
                UPDATE
                    " . PREFIX . "services
                SET
                    active = '0',
                    deleted = '1'
                WHERE
                    service_id = '" . $service_id . "'
                AND
                    organization_id = " . ORGID . "
            ";

            DB::Query($sql);

            $message = $Smarty->_get('services_message_del_success');
            $type = 'success';

        } else {
            $message = $Smarty->_get('services_message_del_error');
        }

        Router::response($type, $message, '/route/services', $arg);
    }

}