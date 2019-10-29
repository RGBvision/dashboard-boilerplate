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



class ModelReports extends Model
{

    public static $start_date = 0;

    public static $end_date = 0;

    public function __construct()
    {
        self::$start_date = ($_SESSION['user_settings']['report_range']['start_date']) ? (int)$_SESSION['user_settings']['report_range']['start_date'] : strtotime("1st January " . date('Y'));
        self::$end_date = ($_SESSION['user_settings']['report_range']['end_date']) ? (int)$_SESSION['user_settings']['report_range']['end_date'] : strtotime("1st January next year");
    }

    public static function ordersSettings()
    {
        if (!empty($_REQUEST['user_settings'])) {
            $_SESSION['user_settings'] = array_replace_recursive($_SESSION['user_settings'], $_REQUEST['user_settings']);
            Settings::saveUserSettings();
        }
        $settings_data = array(
            'Год' => 'ordersYear',
            'Неделя' => 'averageDaily',
            'Сутки' => 'averageHourly',
            'Локации' => 'averageLocation',
            'Проекты' => 'averageDomain',
            'Единый билет' => 'averageComplex'
        );
        return $settings_data;
    }

    public static function getFinances()
    {
        $start_date = (int)self::$start_date;
        $end_date = (int)self::$end_date;

        $start_date_compare = $start_date - ((int)self::$end_date - (int)self::$start_date) - 1;
        $end_date_compare = $start_date - 1;

        $_sql_order_format = '%Y-%m-%d 00:00:00';
        $_sql_output_format = '%Y-%m-%d';
        $_sql_step = 'DAY';

        if (($end_date - $start_date + 1) > (60 * 60 * 24 * 90)) {
            $_sql_order_format = '%Y-%m-01 00:00:00';
            $_sql_output_format = '%Y-%m';
            $_sql_step = 'MONTH';
        }

        $sql = "
			SELECT
				cal.rts as report_rts,
				DATE_FORMAT(cal.rts, '$_sql_output_format') as report_ts,
				IFNULL(r.orders_sum, 0) as report_sum,
				IFNULL(r.orders_salary, 0) as report_salary,
				IFNULL(r.orders_count, 0) as report_count
			FROM (
              SELECT
                DATE_FORMAT(FROM_UNIXTIME($start_date), '$_sql_order_format') + INTERVAL x $_sql_step AS rts
              FROM (
                select (h*100+t*10+u) x from
                        (select 0 h union select 1 union select 2 union select 3 union select 4 union
                        select 5 union select 6 union select 7 union select 8 union select 9) A,
                        (select 0 t union select 1 union select 2 union select 3 union select 4 union
                        select 5 union select 6 union select 7 union select 8 union select 9) B,
                        (select 0 u union select 1 union select 2 union select 3 union select 4 union
                        select 5 union select 6 union select 7 union select 8 union select 9) C
                        order by x
              ) counter
			) cal
			LEFT JOIN (
				SELECT
				    COUNT(order_id) as orders_count,
					SUM(sum) as orders_sum,
					(SUM(reward) + SUM(salary)) as orders_salary,
					DATE_FORMAT(FROM_UNIXTIME(payed), '$_sql_order_format') as orders_date
				FROM
					" . PREFIX . "orders
				WHERE
					status = '1' AND payed > 0 AND organization_id = " . ORGID . " AND payed BETWEEN " . $start_date . " AND " . $end_date . "
				GROUP BY orders_date
			) r ON cal.rts = r.orders_date
			WHERE cal.rts <= DATE_FORMAT(FROM_UNIXTIME($end_date), '$_sql_order_format')
			ORDER BY cal.rts ASC
		";

        $query = DB::Query($sql);

        $total_sum = 0;
        $total_salary = 0;
        $total_count = 0;
        $total_average_check = 0;

        $report = array();

        while ($row = $query->getAssoc()) {
            $row['average_check'] = (($row['report_count'] != 0) ? round(floatval($row['report_sum']) / intval($row['report_count']), 2) : 0);
            array_push($report, $row);
            $total_sum += $row['report_sum'];
            $total_salary += $row['report_salary'];
            $total_count += $row['report_count'];
            $total_average_check += $row['average_check'];
        }

        $return = array(
            "finances_data" => $report,
            "total_sum" => $total_sum,
            "total_salary" => $total_salary,
            "total_count" => $total_count,
            "total_average_check" => $total_average_check,
            "period" => date('Y-m-d', $start_date) . '..' . date('Y-m-d', $end_date),
        );

        return $return;
    }

    public static function getOrders()
    {

        $_orders_data = array();

        $start_date = strtotime('midnight ' . date('Y-m-d', (int)self::$start_date));
        $end_date = strtotime('+1 day ' . date('Y-m-d', (int)self::$end_date)) - 1;

        $total_income = 0;
        $total_orders = 0;

        $sql = "
            SELECT
              o.order_id, o.sum, o.opened, o.closed, o.payed, o.customer_id,
              c.car_numplate, DATE_FORMAT(FROM_UNIXTIME(o.opened), '%Y-%m-%d %H:%i:%s') AS datetime,
              e.firstname as efirstname, e.lastname as elastname,
              u.firstname as ufirstname, u.lastname as ulastname
            FROM
              " . PREFIX . "orders o
            LEFT JOIN
                " . PREFIX . "customers c
            ON o.customer_id = c.customer_id
            LEFT JOIN
                " . PREFIX . "employees e
            ON
                o.employee_id = e.employee_id
            LEFT JOIN
                " . PREFIX . "users u
            ON
                o.employee_id = u.user_id
            WHERE
                o.order_id <> '0' AND
                o.opened BETWEEN " . $start_date . " AND " . $end_date . " AND
                o.organization_id = '" . ORGID . "'
            ORDER BY o.opened DESC
        ";

        $query = DB::Query($sql);

        while ($row = $query->getAssoc()) {
            $row['status_class'] = (($row['payed'] != '0') AND ($row['closed'] != '0')) ? '' : 'table-warning';
            $row['status_label'] = (($row['payed'] == '0') ? 'не оплачен' : 'оплачен')
                . ' / ' . (($row['closed'] == '0') ? 'в работе' : 'выполнен');
            array_push($_orders_data, $row);
            $total_income += (($row['payed'] != '0') ? floatval($row['sum']) : 0);
            $total_orders += (($row['closed'] != '0') ? 1 : 0);
        }

        return array(
            "period" => date('Y-m-d', $start_date) . ' - ' . date('Y-m-d', $end_date),
            "orders_data" => $_orders_data,
            "total_income" => $total_income,
            "total_orders" => $total_orders
        );

    }

}