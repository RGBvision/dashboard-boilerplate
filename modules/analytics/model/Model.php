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



class ModelAnalytics extends Model
{

    public static $start_date = 0;

    public static $end_date = 0;

    public function __construct()
    {
        self::$start_date = ($_SESSION['user_settings']['report_range']['start_date']) ? (int)$_SESSION['user_settings']['report_range']['start_date'] : strtotime("1st January " . date('Y'));
        self::$end_date = ($_SESSION['user_settings']['report_range']['end_date']) ? (int)$_SESSION['user_settings']['report_range']['end_date'] : strtotime("1st January next year -1 sec");
    }

    public static function analyticsSettings()
    {
        if (!empty($_REQUEST['user_settings'])) {
            $_SESSION['user_settings'] = array_replace_recursive($_SESSION['user_settings'], $_REQUEST['user_settings']);
            Settings::saveUserSettings();
        }
        $settings_data = array(
            'Интервал' => 'ordersInterval',
            'Год' => 'ordersYear',
            'Неделя' => 'averageDaily',
            'Сутки' => 'averageHourly'
        );
        return $settings_data;
    }

    public static function getIntervalFinances()
    {

        $start_date = (int)self::$start_date;
        $end_date = (int)self::$end_date;

        $start_date_compare = $start_date - ((int)self::$end_date - (int)self::$start_date) - 1;
        $end_date_compare = $start_date - 1;

        $_sql_order_format = '%Y-%m-%d %H:00:00';
        $_sql_output_format = '%d/%H:00';
        $_sql_step = 'HOUR';

        if (($end_date - $start_date + 1) > (60 * 60 * 24)) {
            $_sql_order_format = '%Y-%m-%d 00:00:00';
            $_sql_output_format = '%Y-%m-%d';
            $_sql_step = 'DAY';
        }

        if (($end_date - $start_date + 1) > (60 * 60 * 24 * 90)) {
            $_sql_order_format = '%Y-%m-01 00:00:00';
            $_sql_output_format = '%Y-%m';
            $_sql_step = 'MONTH';
        }

        $sql = "
			SELECT
				DATE_FORMAT(cal.rts, '$_sql_output_format') as orders_report_ts,
				DATE_FORMAT(cal.cts, '$_sql_output_format') as orders_compare_ts,
				IFNULL(r.orders_total, 0) as orders_report_sum,
				IFNULL(c.orders_total, 0) as orders_compare_sum
			FROM (
	      SELECT
	      	DATE_FORMAT(FROM_UNIXTIME($start_date), '$_sql_order_format') + INTERVAL x $_sql_step AS rts,
	      	DATE_FORMAT(FROM_UNIXTIME($start_date_compare), '$_sql_order_format') + INTERVAL x $_sql_step AS cts
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
					SUM(sum) as orders_total,
					DATE_FORMAT(FROM_UNIXTIME(payed), '$_sql_order_format') as orders_date
				FROM
					" . PREFIX . "orders
				WHERE
					status = '1' AND payed > 0 AND organization_id = " . ORGID . " AND payed BETWEEN " . $start_date . " AND " . $end_date . "
				GROUP BY orders_date
			) r ON cal.rts = r.orders_date
			LEFT JOIN (
				SELECT
					SUM(sum) as orders_total,
					DATE_FORMAT(FROM_UNIXTIME(payed), '$_sql_order_format') as orders_date
				FROM
					" . PREFIX . "orders
				WHERE
					status = '1' AND payed > 0 AND organization_id = " . ORGID . " AND payed BETWEEN " . $start_date_compare . " AND " . $end_date_compare . "
				GROUP BY orders_date
			) c ON cal.cts = c.orders_date
			WHERE cal.rts <= DATE_FORMAT(FROM_UNIXTIME($end_date), '$_sql_order_format')
			ORDER BY cal.rts ASC
		";

        $query = DB::Query($sql);

        $income = array();
        $income_average = array();
        $income_labels = array();

        $total = 0;

        $income_compare = array();
        $income_average_compare = array();

        $total_compare = 0;

        while ($row = $query->getAssoc()) {
            $income_labels[] = $row['orders_report_ts'];
            $income[] = $row['orders_report_sum'];
            $total += $row['orders_report_sum'];

            $income_compare[] = $row['orders_compare_sum'];
            $total_compare += $row['orders_compare_sum'];
        }

        foreach ($income as $k => $v) {
            $income_average[$k] = ($total) ? round($v / ($total / 100), 2) : 0;
        }

        foreach ($income_compare as $k => $v) {
            $income_average_compare[$k] = ($total) ? round($v / ($total / 100), 2) : 0;
        }

        $return = array(
            "labels" => ('"' . implode('","', $income_labels) . '"'),
            "revenue_data" => implode(',', ((!empty($_SESSION['user_settings']['analytics']['finances']['ordersInterval']['datatype']) &&
                ($_SESSION['user_settings']['analytics']['finances']['ordersInterval']['datatype'] && Permission::perm('analytics_abs_values'))) ? $income : $income_average)),
            "compare_data" => implode(',', ((!empty($_SESSION['user_settings']['analytics']['finances']['ordersInterval']['datatype']) &&
                ($_SESSION['user_settings']['analytics']['finances']['ordersInterval']['datatype'] && Permission::perm('analytics_abs_values'))) ? $income_compare : $income_average_compare)),
            "year" => date('Y-m-d', $start_date) . '..' . date('Y-m-d', $end_date),
            "compare_year" => date('Y-m-d', $start_date_compare) . '..' . date('Y-m-d', $end_date_compare),
            "total" => $total,
            "compare_total" => $total_compare,
            "total_pct" => ($total) ? round($total / ($total / 100), 2) : 0,
            "compare_total_pct" => ($total) ? round($total_compare / ($total / 100), 2) : 0,
            "compare_diff_pct" => ($total && $total_compare) ? (round($total / ($total_compare / 100), 2) - 100) : 0
        );

        return $return;
    }

    public static function getAnnualFinances()
    {

        $months = array(
            'Янв', 'Фев', 'Мрт',
            'Апр', 'Май', 'Июн',
            'Июл', 'Авг', 'Сен',
            'Окт', 'Ноя', 'Дек'
        );

        $start_date = strtotime('1st January ' . date('Y', (int)self::$end_date));
        $end_date = strtotime('1st January ' . (intval(date('Y', (int)self::$end_date)) + 1)) - 1;

        $start_date_compare = strtotime('1st January ' . (intval(date('Y', (int)self::$end_date)) - 1));
        $end_date_compare = strtotime('1st January ' . date('Y', (int)self::$end_date)) - 1;

        // Report

        $sql = "
				SELECT
					SUM(sum) as orders_total,
					DATE_FORMAT(FROM_UNIXTIME(payed), '%c') as orders_month
				FROM
					" . PREFIX . "orders
				WHERE
					status = '1' AND
					payed > 0 AND organization_id = " . ORGID . " AND payed BETWEEN " . $start_date . " AND " . $end_date . "
				GROUP BY orders_month
				ORDER BY payed ASC
			";

        $query = DB::Query($sql);

        $income = array();
        $income_average = array();

        for ($i = 1; $i <= 12; $i++) {
            $income[$i] = 0;
        }

        $total = 0;

        while ($row = $query->getAssoc()) {
            $income[$row['orders_month']] = $row['orders_total'];
            $total += $row['orders_total'];
        }

        foreach ($income as $k => $v) {
            $income_average[$k] = ($total) ? round($v / ($total / 100), 2) : 0;
        }

        // Compare

        $sql = "
				SELECT
					SUM(sum) as orders_total,
					DATE_FORMAT(FROM_UNIXTIME(payed), '%c') as orders_month
				FROM
					" . PREFIX . "orders
				WHERE
					status = '1' AND
					payed > 0 AND organization_id = " . ORGID . " AND payed BETWEEN " . $start_date_compare . " AND " . $end_date_compare . "
				GROUP BY orders_month
				ORDER BY payed ASC
			";

        $query = DB::Query($sql);

        $income_compare = array();
        $income_average_compare = array();

        for ($i = 1; $i <= 12; $i++) {
            $income_compare[$i] = 0;
        }

        $total_compare = 0;

        while ($row = $query->getAssoc()) {
            $income_compare[$row['orders_month']] = $row['orders_total'];
            $total_compare += $row['orders_total'];
        }

        foreach ($income_compare as $k => $v) {
            $income_average_compare[$k] = ($total_compare) ? round($v / ($total_compare / 100), 2) : 0;
        }

        $return = array(
            "labels" => ('"' . implode('","', $months) . '"'),
            "revenue_data" => implode(',', ((!empty($_SESSION['user_settings']['analytics']['finances']['ordersYear']['datatype']) &&
                ($_SESSION['user_settings']['analytics']['finances']['ordersYear']['datatype'] && Permission::perm('analytics_abs_values'))) ? $income : $income_average)),
            "year" => date('Y', $start_date),
            "total" => $total
        );

        return $return;
    }

    public static function getDailyAverageFinances()
    {

        if (date('N', (int)self::$start_date) == 1) {
            $start_date = strtotime('midnight ' . date('Y-m-d', (int)self::$start_date));
        } else {
            $start_date = strtotime('Monday -7 days ' . date('Y-m-d', (int)self::$start_date));
        }
        $end_date = strtotime('next Monday ' . date('Y-m-d', (int)self::$end_date)) - 1;

        $days_of_week = array(
            'Вс', 'Пн', 'Вт',
            'Ср', 'Чт', 'Пт',
            'Сб'
        );

        $sql = "
				SELECT
					SUM(sum) as revenue_daily,
					DATE_FORMAT(FROM_UNIXTIME(payed), '%w') as day_number
				FROM
					" . PREFIX . "orders
				WHERE
					status = '1' AND
					payed > 0 AND organization_id = " . ORGID . " AND payed BETWEEN " . $start_date . " AND " . $end_date . "
				GROUP BY day_number
				ORDER BY day_number ASC
			";

        $query = DB::Query($sql);

        $income = array();
        $income_average = array();

        for ($i = 0; $i <= 6; $i++) {
            $income[$i] = 0;
        }

        $total = 0;

        while ($row = $query->getAssoc()) {
            $income[$row['day_number']] = $row['revenue_daily'];
            $total += $row['revenue_daily'];
        }

        foreach ($income as $k => $v) {
            $income_average[$k] = ($total) ? round($v / ($total / 100), 2) : 0;
        }

        self::move_to_bottom($days_of_week, 0);
        self::move_to_bottom($income_average, 0);

        $return = array(
            "labels" => ('"' . implode('","', $days_of_week) . '"'),
            "income_data" => implode(',', ((!empty($_SESSION['user_settings']['analytics']['finances']['averageDaily']['datatype']) &&
                ($_SESSION['user_settings']['analytics']['finances']['averageDaily']['datatype'] && Permission::perm('analytics_abs_values'))) ? $income : $income_average)),
            "period" => date('Y-m-d', $start_date) . ' - ' . date('Y-m-d', $end_date),
            "total" => $total,
            "average" => round($total / 7)
        );

        return $return;
    }

    public static function getHourlyAverageFinances()
    {

        $start_date = strtotime('midnight ' . date('Y-m-d', (int)self::$start_date));
        $end_date = strtotime('+1 day ' . date('Y-m-d', (int)self::$end_date)) - 1;

        $sql = "
				SELECT
					SUM(sum) as revenue_hourly,
					DATE_FORMAT(FROM_UNIXTIME(payed), '%k') as hour_number
				FROM
					" . PREFIX . "orders
				WHERE
					status = '1' AND
					payed > 0 AND organization_id = " . ORGID . " AND payed BETWEEN " . $start_date . " AND " . $end_date . "
				GROUP BY hour_number
				ORDER BY hour_number ASC
			";

        $query = DB::Query($sql);

        $income = array();
        $income_average = array();

        for ($i = 0; $i <= 23; $i++) {
            $income[$i] = 0;
        }

        $total = 0;

        while ($row = $query->getAssoc()) {
            $income[$row['hour_number']] = $row['revenue_hourly'];
            $total += $row['revenue_hourly'];
        }

        foreach ($income as $k => $v) {
            $income_average[$k] = ($total) ? round($v / ($total / 100), 2) : 0;
        }

        $return = array(
            "labels" => ('"' . implode('","', array_keys($income_average)) . '"'),
            "income_data" => implode(',', ((!empty($_SESSION['user_settings']['analytics']['finances']['averageHourly']['datatype']) &&
                ($_SESSION['user_settings']['analytics']['finances']['averageHourly']['datatype'] && Permission::perm('analytics_abs_values'))) ? $income : $income_average)),
            "period" => date('Y-m-d', $start_date) . ' - ' . date('Y-m-d', $end_date),
            "total" => $total,
            "average" => round($total / 23)
        );

        return $return;
    }

    public static function getIntervalOrders()
    {

        $start_date = (int)self::$start_date;
        $end_date = (int)self::$end_date;

        $start_date_compare = $start_date - ((int)self::$end_date - (int)self::$start_date) - 1;
        $end_date_compare = $start_date - 1;

        $_sql_order_format = '%Y-%m-%d %H:00:00';
        $_sql_output_format = '%d/%H:00';
        $_sql_step = 'HOUR';

        if (($end_date - $start_date + 1) > (60 * 60 * 24)) {
            $_sql_order_format = '%Y-%m-%d 00:00:00';
            $_sql_output_format = '%Y-%m-%d';
            $_sql_step = 'DAY';
        }

        if (($end_date - $start_date + 1) > (60 * 60 * 24 * 90)) {
            $_sql_order_format = '%Y-%m-01 00:00:00';
            $_sql_output_format = '%Y-%m';
            $_sql_step = 'MONTH';
        }

        $sql = "
			SELECT
				DATE_FORMAT(cal.rts, '$_sql_output_format') as orders_report_ts,
				DATE_FORMAT(cal.cts, '$_sql_output_format') as orders_compare_ts,
				IFNULL(r.orders_total, 0) as orders_report_sum,
				IFNULL(c.orders_total, 0) as orders_compare_sum
			FROM (
	      SELECT
	      	DATE_FORMAT(FROM_UNIXTIME($start_date), '$_sql_order_format') + INTERVAL x $_sql_step AS rts,
	      	DATE_FORMAT(FROM_UNIXTIME($start_date_compare), '$_sql_order_format') + INTERVAL x $_sql_step AS cts
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
					COUNT(DISTINCT order_id) as orders_total,
					DATE_FORMAT(FROM_UNIXTIME(payed), '$_sql_order_format') as orders_date
				FROM
					" . PREFIX . "orders
				WHERE
					status = '1' AND payed > 0 AND organization_id = " . ORGID . " AND payed BETWEEN " . $start_date . " AND " . $end_date . "
				GROUP BY orders_date
			) r ON cal.rts = r.orders_date
			LEFT JOIN (
				SELECT
					COUNT(DISTINCT order_id) as orders_total,
					DATE_FORMAT(FROM_UNIXTIME(payed), '$_sql_order_format') as orders_date
				FROM
					" . PREFIX . "orders
				WHERE
					status = '1' AND payed > 0 AND organization_id = " . ORGID . " AND payed BETWEEN " . $start_date_compare . " AND " . $end_date_compare . "
				GROUP BY orders_date
			) c ON cal.cts = c.orders_date
			WHERE cal.rts <= DATE_FORMAT(FROM_UNIXTIME($end_date), '$_sql_order_format')
			ORDER BY cal.rts ASC
		";

        $query = DB::Query($sql);

        $income = array();
        $income_average = array();
        $income_labels = array();

        $total = 0;

        $income_compare = array();
        $income_average_compare = array();

        $total_compare = 0;

        while ($row = $query->getAssoc()) {
            $income_labels[] = $row['orders_report_ts'];
            $income[] = $row['orders_report_sum'];
            $total += $row['orders_report_sum'];

            $income_compare[] = $row['orders_compare_sum'];
            $total_compare += $row['orders_compare_sum'];
        }

        foreach ($income as $k => $v) {
            $income_average[$k] = ($total) ? round($v / ($total / 100), 2) : 0;
        }

        foreach ($income_compare as $k => $v) {
            $income_average_compare[$k] = ($total) ? round($v / ($total / 100), 2) : 0;
        }

        $return = array(
            "labels" => ('"' . implode('","', $income_labels) . '"'),
            "revenue_data" => implode(',', ((!empty($_SESSION['user_settings']['analytics']['finances']['ordersInterval']['datatype']) &&
                ($_SESSION['user_settings']['analytics']['finances']['ordersInterval']['datatype'] && Permission::perm('analytics_abs_values'))) ? $income : $income_average)),
            "compare_data" => implode(',', ((!empty($_SESSION['user_settings']['analytics']['finances']['ordersInterval']['datatype']) &&
                ($_SESSION['user_settings']['analytics']['finances']['ordersInterval']['datatype'] && Permission::perm('analytics_abs_values'))) ? $income_compare : $income_average_compare)),
            "year" => date('Y-m-d', $start_date) . '..' . date('Y-m-d', $end_date),
            "compare_year" => date('Y-m-d', $start_date_compare) . '..' . date('Y-m-d', $end_date_compare),
            "total" => $total,
            "compare_total" => $total_compare,
            "total_pct" => ($total) ? round($total / ($total / 100), 2) : 0,
            "compare_total_pct" => ($total) ? round($total_compare / ($total / 100), 2) : 0,
            "compare_diff_pct" => ($total && $total_compare) ? (round($total / ($total_compare / 100), 2) - 100) : 0
        );

        return $return;
    }

    public static function getAnnualOrders()
    {

        $months = array(
            'Янв', 'Фев', 'Мрт',
            'Апр', 'Май', 'Июн',
            'Июл', 'Авг', 'Сен',
            'Окт', 'Ноя', 'Дек'
        );

        $start_date = strtotime('1st January ' . date('Y', (int)self::$end_date));
        $end_date = strtotime('1st January ' . date('Y', (int)self::$end_date) . ' +1 year') - 1;

        $sql = "
				SELECT
					COUNT(DISTINCT order_id) as orders_total,
					DATE_FORMAT(FROM_UNIXTIME(payed), '%c') as orders_month
				FROM
					" . PREFIX . "orders
				WHERE
					status = '1' AND
					payed > 0 AND
					organization_id = " . ORGID . " AND
					payed BETWEEN " . $start_date . " AND " . $end_date . "
				GROUP BY orders_month
				ORDER BY payed ASC
			";

        $query = DB::Query($sql);

        $income = array();
        $income_average = array();

        for ($i = 1; $i <= 12; $i++) {
            $income[$i] = 0;
        }

        $total = 0;

        while ($row = $query->getAssoc()) {
            $income[$row['orders_month']] = $row['orders_total'];
            $total += $row['orders_total'];
        }

        foreach ($income as $k => $v) {
            $income_average[$k] = ($total) ? round($v / ($total / 100), 2) : 0;
        }

        $return = array(
            "labels" => ('"' . implode('","', $months) . '"'),
            "revenue_data" => implode(',', ((!empty($_SESSION['user_settings']['analytics']['orders']['ordersYear']['datatype']) &&
                ($_SESSION['user_settings']['analytics']['orders']['ordersYear']['datatype'] && Permission::perm('analytics_abs_values'))) ? $income : $income_average)),
            "year" => date('Y', $start_date),
            "total" => $total,
            "average" => round($total / 12),
            "previous" => date('Y', strtotime("-1 year"))
        );

        return $return;
    }

    public static function getDailyAverageOrders()
    {

        if (date('N', (int)self::$start_date) == 1) {
            $start_date = strtotime('midnight ' . date('Y-m-d', (int)self::$start_date));
        } else {
            $start_date = strtotime('Monday -7 days ' . date('Y-m-d', (int)self::$start_date));
        }
        $end_date = strtotime('next Monday ' . date('Y-m-d', (int)self::$end_date)) - 1;

        $days_of_week = array(
            'Вс', 'Пн', 'Вт',
            'Ср', 'Чт', 'Пт',
            'Сб'
        );

        $sql = "
				SELECT
					COUNT(DISTINCT order_id) as revenue_daily,
					DATE_FORMAT(FROM_UNIXTIME(payed), '%w') as day_number
				FROM
					" . PREFIX . "orders
				WHERE
					status = '1' AND
					payed > 0 AND
					organization_id = " . ORGID . " AND
					payed BETWEEN " . $start_date . " AND " . $end_date . "
				GROUP BY day_number
				ORDER BY day_number ASC
			";

        $query = DB::Query($sql);

        $income = array();
        $income_average = array();

        for ($i = 0; $i <= 6; $i++) {
            $income[$i] = 0;
        }

        $total = 0;

        while ($row = $query->getAssoc()) {
            $income[$row['day_number']] = $row['revenue_daily'];
            $total += $row['revenue_daily'];
        }

        foreach ($income as $k => $v) {
            $income_average[$k] = ($total) ? round($v / ($total / 100), 2) : 0;
        }

        self::move_to_bottom($days_of_week, 0);
        self::move_to_bottom($income_average, 0);

        $return = array(
            "labels" => ('"' . implode('","', $days_of_week) . '"'),
            "income_data" => implode(',', ((!empty($_SESSION['user_settings']['analytics']['orders']['averageDaily']['datatype']) &&
                ($_SESSION['user_settings']['analytics']['orders']['averageDaily']['datatype'] && Permission::perm('analytics_abs_values'))) ? $income : $income_average)),
            "period" => date('Y-m-d', $start_date) . ' - ' . date('Y-m-d', $end_date),
            "total" => $total,
            "average" => round($total / 7)
        );

        return $return;
    }

    public static function getHourlyAverageOrders()
    {

        $start_date = strtotime('midnight ' . date('Y-m-d', (int)self::$start_date));
        $end_date = strtotime('+1 day ' . date('Y-m-d', (int)self::$end_date)) - 1;

        $sql = "
				SELECT
					COUNT(DISTINCT order_id) as revenue_hourly,
					DATE_FORMAT(FROM_UNIXTIME(payed), '%k') as hour_number
				FROM
					" . PREFIX . "orders
				WHERE
					status = '1' AND
					payed > 0 AND
					organization_id = " . ORGID . " AND
					payed BETWEEN " . $start_date . " AND " . $end_date . "
				GROUP BY hour_number
				ORDER BY hour_number ASC
			";

        $query = DB::Query($sql);

        $income = array();
        $income_average = array();

        for ($i = 0; $i <= 23; $i++) {
            $income[$i] = 0;
        }

        $total = 0;

        while ($row = $query->getAssoc()) {
            $income[$row['hour_number']] = $row['revenue_hourly'];
            $total += $row['revenue_hourly'];
        }

        foreach ($income as $k => $v) {
            $income_average[$k] = ($total) ? round($v / ($total / 100), 2) : 0;
        }

        $return = array(
            "labels" => ('"' . implode('","', array_keys($income_average)) . '"'),
            "income_data" => implode(',', ((!empty($_SESSION['user_settings']['analytics']['orders']['averageHourly']['datatype']) &&
                ($_SESSION['user_settings']['analytics']['orders']['averageHourly']['datatype'] && Permission::perm('analytics_abs_values'))) ? $income : $income_average)),
            "period" => date('Y-m-d', $start_date) . ' - ' . date('Y-m-d', $end_date),
            "total" => $total,
            "average" => round($total / 23)
        );

        return $return;
    }

    public static function getIntervalEmployees()
    {

        $start_date = (int)self::$start_date;
        $end_date = (int)self::$end_date;

        $start_date_compare = $start_date - ((int)self::$end_date - (int)self::$start_date) - 1;
        $end_date_compare = $start_date - 1;

        $labels = array();
        $income = array();
        $income_c = array();
        $salary = array();
        $salary_c = array();

        $sql = "
			SELECT
                e.employee_id,
                e.firstname,
                e.lastname,
                IFNULL(s.total_sum, 0) as income,
                IFNULL(c.total_sum, 0) as income_compare,
                IFNULL(s.total_salary, 0) as salary,
                IFNULL(c.total_salary, 0) as salary_compare
            FROM
                " . PREFIX . "employees e
            LEFT JOIN
                (
                    SELECT
                        employee_id,
                        SUM(sum) AS total_sum,
                        SUM(salary) AS total_salary,
                        SUM(reward) AS total_reward
                    FROM
                        " . PREFIX . "orders  
                    WHERE
                        status = '1' 
                        AND payed > 0  
                        AND organization_id = " . ORGID . "  
                        AND payed BETWEEN " . $start_date . " AND " . $end_date . "   
                    GROUP BY
                        employee_id  
                ) s ON s.employee_id = e.employee_id  
            LEFT JOIN
                (
                    SELECT
                        employee_id,
                        SUM(sum) AS total_sum,
                        SUM(salary) AS total_salary,
                        SUM(reward) AS total_reward  
                    FROM
                        " . PREFIX . "orders  
                    WHERE
                        status = '1' 
                        AND payed > 0  
                        AND organization_id = " . ORGID . "  
                        AND payed BETWEEN " . $start_date_compare . " AND " . $end_date_compare . "   
                    GROUP BY
                        employee_id  
                ) c ON c.employee_id = e.employee_id 
            WHERE
                e.organization_id = " . ORGID . " 
                AND e.active = '1' 
            GROUP BY
                e.employee_id 
            ORDER BY
                e.lastname,
                e.department_id
		";

        $query = DB::Query($sql);

        while ($row = $query->getAssoc()) {
            $labels[$row['employee_id']] = $row['lastname'] . " " . $row['firstname'];
            $income[$row['employee_id']] = $row['income'];
            $income_c[$row['employee_id']] = $row['income_compare'];
            $salary[$row['employee_id']] = $row['salary'];
            $salary_c[$row['employee_id']] = $row['salary_compare'];
        }

        $sql = "
            SELECT
                u.linked_employee,
                IFNULL(s.total_reward, 0) as reward,
                IFNULL(c.total_reward, 0) as reward_compare
            FROM
                " . PREFIX . "users u
            LEFT JOIN
                (
                    SELECT
                        user_id,
                        SUM(reward) AS total_reward
                    FROM
                        " . PREFIX . "orders  
                    WHERE
                        status = '1' 
                        AND payed > 0  
                        AND organization_id = " . ORGID . "  
                        AND payed BETWEEN " . $start_date . " AND " . $end_date . "   
                    GROUP BY
                        user_id  
                ) s ON s.user_id = u.user_id  
            LEFT JOIN
                (
                    SELECT
                        user_id,
                        SUM(reward) AS total_reward  
                    FROM
                        " . PREFIX . "orders  
                    WHERE
                        status = '1' 
                        AND payed > 0  
                        AND organization_id = " . ORGID . "  
                        AND payed BETWEEN " . $start_date_compare . " AND " . $end_date_compare . "   
                    GROUP BY
                        user_id  
                ) c ON c.user_id = u.user_id 
            WHERE
                u.organization_id = " . ORGID . "
                AND u.linked_employee > 0
                AND u.active = '1' 
            GROUP BY
                u.linked_employee
		";

        $query = DB::Query($sql);

        while ($row = $query->getAssoc()) {
            $salary[$row['linked_employee']] += $row['reward'];
            $salary_c[$row['linked_employee']] += $row['reward_compare'];
        }

        $return = array(
            "labels" => ('"' . implode('","', $labels) . '"'),
            "income" => ('"' . implode('","', $income) . '"'),
            "income_c" => ('"' . implode('","', $income_c) . '"'),
            "salary" => ('"' . implode('","', $salary) . '"'),
            "salary_c" => ('"' . implode('","', $salary_c) . '"'),
            "year" => date('Y-m-d', $start_date) . '..' . date('Y-m-d', $end_date),
            "compare_year" => date('Y-m-d', $start_date_compare) . '..' . date('Y-m-d', $end_date_compare),
        );

        return $return;
    }

    private static function move_to_top(&$array, $key)
    {
        $temp = array($key => $array[$key]);
        unset($array[$key]);
        $array = $temp + $array;
    }

    private static function move_to_bottom(&$array, $key)
    {
        $value = $array[$key];
        unset($array[$key]);
        $array[$key] = $value;
    }

}