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



class ModelCustomers extends Model
{

    public static function getCustomers($exclude = '')
    {
        $exclude = ($exclude != '' && is_numeric($exclude))
            ? "AND car_class != '" . $exclude . "'"
            : '';

        $sql = "
				SELECT
					*
				FROM
					" . PREFIX . "customers
				WHERE
					deleted != 1
				AND
				    organization_id = '" . ORGID . "'
				$exclude
				ORDER BY
					lastname ASC
			";

        $query = DB::Query($sql);

        $users = array();

        while ($row = $query->getAssoc()) {
            array_push($users, $row);
        }

        return $users;
    }

    public static function update()
    {
        if (Request::isAjax() AND ((int)Request::post('customer_id') > 0)) {

            $sql = "
                UPDATE
                    " . PREFIX . "customers
                SET
                    firstname = '" . preg_replace('/[^[:alpha:]]/u', '', Request::post('customer_firstname')) . "',
                    secondname = '" . preg_replace('/[^[:alpha:]]/u', '', Request::post('customer_secondname')) . "',
                    lastname = '" . preg_replace('/[^[:alpha:]]/u', '', Request::post('customer_lastname')) . "',
                    phone = '" . normalizePhone(Request::post('customer_phone')) . "',
                    email = '" . normalizeEmail(Request::post('customer_email')) . "',
                    car_model = '" . mb_strtoupper(Request::post('customer_car_model')) . "',
                    car_numplate = '" . normalizeNumplate(Request::post('customer_car_numplate')) . "',
                    description = '" . addslashes(htmlspecialchars(Request::post('description'))) . "'
                WHERE
                    customer_id = '" . (int)Request::post('customer_id') . "' AND
                    organization_id = '" . ORGID . "'
            ";

            DB::Query($sql);

            $type = 'success';
            $arg = array();
            $message = '';

            Router::response($type, $message, '/route/customers', $arg);
        }
    }

}