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



class ModelDashboard extends Model
{
    public static function index()
    {
        /*
                for ($i = 0; $i < 363; $i++) {

                    for ($j = 8; $j < 22; $j++) {

                        $_orders = rand(-2, 2);

                        for ($r = 0; $r < $_orders; $r++) {

                            $_limit = rand(15, 60) * 60;
                            $_start = rand(0, 59) * 60;

                            DB::Query("
                                INSERT INTO `billing_orders` (
                                    `order_guid`,
                                    `organization_id`,
                                    `customer_id`,
                                    `user_id`,
                                    `employee_id`,
                                    `post_id`,
                                    `status`,
                                    `department`,
                                    `sum`,
                                    `reward`,
                                    `salary`,
                                    `paytype`,
                                    `dependencies`,
                                    `routing`,
                                    `time_limit`,
                                    `opened`,
                                    `payed`,
                                    `closed`
                                )
                                VALUES (
                                    '00000001-00000001-000000005d401b74',
                                    1, " . rand(1, 5) . ", 40, 1, 1, '0', '1',
                                    " . rand(300, 2700) . ",
                                    0.00,
                                    " . rand(30, 270) . ",
                                    '0', '', '',
                                    " . rand(15, 90) . ",
                                    " . (1546300800 + ($i * $j * 60 * 60) + $_start) . ",
                                    " . (1546300800 + ($i * $j * 60 * 60) + $_start + $_limit + (rand(-10, 10) * 60)) . ",
                                    " . (1546300800 + ($i * $j * 60 * 60) + $_start + $_limit + (rand(-5, 20) * 60)) . "
                                );
                            ");
                        }
                    }
                }
        */

    }

    public static function getIncome()
    {

        $start_date = (int)strtotime('tomorrow -7 days');
        $end_date = (int)strtotime('tomorrow') - 1;

        $_sql_order_format = '%Y-%m-%d 00:00:00';
        $_sql_output_format = '%Y-%m-%d';
        $_sql_step = 'DAY';

        $sql = "
			SELECT
				DATE_FORMAT(cal.rts, '$_sql_output_format') as orders_report_ts,
				IFNULL(r.orders_income, 0) as orders_report_sum,
				IFNULL(r.orders_total, 0) as orders_report_count
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
					SUM(sum) as orders_income,
					COUNT(sum) as orders_total,
					DATE_FORMAT(FROM_UNIXTIME(payed), '$_sql_order_format') as orders_date
				FROM
					" . PREFIX . "orders
				WHERE
					payed > 0 AND payed BETWEEN " . $start_date . " AND " . $end_date . "
				GROUP BY orders_date
			) r ON cal.rts = r.orders_date
			WHERE cal.rts <= DATE_FORMAT(FROM_UNIXTIME($end_date), '$_sql_order_format')
			ORDER BY cal.rts ASC
		";

        $query = DB::Query($sql);

        $income = array();
        $count = array();
        $average_check = array();
        $income_labels = array();

        $total_income = 0;
        $total_count = 0;

        while ($row = $query->getAssoc()) {
            $income_labels[] = $row['orders_report_ts'];
            $income[] = $row['orders_report_sum'];
            $count[] = $row['orders_report_count'];
            $average_check[] = ($row['orders_report_count'] > 0) ? round(($row['orders_report_sum'] / $row['orders_report_count']), 2) : 0;
            $total_income += $row['orders_report_sum'];
            $total_count += $row['orders_report_count'];
        }

        $return = array(
            "labels" => ('"' . implode('","', $income_labels) . '"'),
            "revenue_data" => implode(',', $income),
            "count_data" => implode(',', $count),
            "average_check" => implode(',', $average_check),
            "total_income" => $total_income,
            "total_count" => $total_count,
            "total_average_check" => ($total_count > 0) ? round(($total_income / $total_count), 2) : 0
        );

        return $return;
    }

    public static function getServices()
    {

        $_dep = array();

        if (Permission::check('dashboard_getorders')) {
            switch (UGROUP) {
                case 3:
                    $_dep = array('0', '1', '2', '3');
                    break;
                case 4:
                    $_dep = array('0', '1');
                    break;
                case 5:
                    $_dep = array('0', '2');;
                    break;
                case 6:
                    $_dep = array('0', '3');;
                    break;
            }
        }

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
				    department IN ('" . implode("','", $_dep) . "')
				ORDER BY
					sort ASC
			";

        $query = DB::Query($sql);

        $services = array();

        while ($row = $query->getAssoc()) {
            $row['calculation'] = unserialize($row['calculation']);

            switch ($row['type']) {
                case 1:
                    $row['cost'] = $row['calculation'][0]['cost'];
                    $row['time'] = $row['calculation'][0]['time'];
                    break;
                case 2:
                    $cost = array();
                    $time = array();
                    for ($i = 1; $i <= $_SESSION['organization_settings']['classes']; $i++) {
                        $cost[] = $row['calculation'][$i]['cost'];
                        $time[] = $row['calculation'][$i]['time'];
                    }
                    $row['cost'] = implode(';', $cost);
                    $row['time'] = implode(';', $time);
                    break;
                case 3:
                    $row['cost'] = 0;
                    $row['time'] = 0;
                    $row['parametric'] = $row['calculation']['parametric'];
                    break;
                default:
                    break;
            }

            unset($row['calculation']);

            array_push($services, $row);
        }

        return $services;
    }

    public static function getActiveOrders()
    {

        $sql = "
				SELECT
					o.order_id, o.order_guid, o.payed, o.closed,
					c.customer_id, c.phone, c.email, c.firstname, c.lastname,
					c.car_numplate, c.car_model, c.description
				FROM
					" . PREFIX . "orders AS o
				LEFT JOIN
				    " . PREFIX . "customers AS c
                    ON o.customer_id = c.customer_id
                    AND o.organization_id = c.organization_id
				WHERE
				    (o.closed = 0 OR o.payed = 0)
				ORDER BY
					o.order_id ASC
			";

        $query = DB::Query($sql);

        $orders = array();

        while ($row = $query->getAssoc()) {
            //$row['status_class'] = (($row['payed'] != '0') AND ($row['closed'] != '0')) ? 'card-success' : 'card-danger';
            if (Permission::check('orders_add')) {
                $row['status_class'] = ($row['payed'] != '0') ? 'card-success' : 'card-danger';
                $row['status'] = (($row['payed'] == '0') ? '<i class="sli sli-status-close text-danger"></i> не оплачен' : '<i class="sli sli-status-check-1 text-success"></i> оплачен');
            }
            if (Permission::check('orders_payment')) {
                $row['status_class'] = ($row['closed'] != '0') ? 'card-success' : 'card-danger';
                $row['status'] = (($row['closed'] == '0') ? '<i class="sli sli-status-close text-danger"></i> в работе' : '<i class="sli sli-status-check-1 text-success"></i> выполнен');
            }
            $row['canclose'] = ($row['closed'] == '0') ? 1 : 0;
            $row['canpay'] = ($row['payed'] == '0') ? 1 : 0;

            $row['photo'] = (file_exists(CP_DIR . '/uploads/customers/' . str_replace('-', '/', $row['order_guid']) . '.jpg')) ?
                ('/uploads/customers/' . str_replace('-', '/', $row['order_guid']) . '.jpg') : '/uploads/customers/no_photo.jpg';

            array_push($orders, $row);
        }

        return $orders;
    }

    public static function recognize()
    {
        if (Request::isAjax() AND Request::post('photo')) {

            $type = 'danger';
            $arg = array();
            $message = array(
                'numplate' => '',
                'model' => '',
                'class' => '1',
                'customer' => '',
                'description' => ''
            );

            $photo = Request::post('photo');

            $data = preg_replace('#^data:image/\w+;base64,#i', '', $photo);

            $url = "https://api.openalpr.com/v2/recognize_bytes?country=eu&secret_key=sk_95d7bd62655f4b76e0e3d159";
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_NOBODY, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 15);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            $response = curl_exec($curl);
            curl_close($curl);

            $response_data = json_decode($response, true, 512, JSON_OBJECT_AS_ARRAY);

            if ((json_last_error() == JSON_ERROR_NONE) AND isset($response_data['results'][0]['plate'])) {

                $type = 'success';

                $numplate = normalizeNumplate($response_data['results'][0]['plate']);

                $sql = "
                  SELECT
                    *,
                    (
                        IF (car_numplate = '" . $numplate . "', 100, 0) 
                        + IF (car_numplate LIKE '%" . $numplate . "%', 50, 0)
                    ) AS relevant
                  FROM
                    " . PREFIX . "customers
                  WHERE
                    organization_id = '" . ORGID . "'
                  HAVING 
                    relevant > 0
                  ORDER BY
                    relevant DESC
                  LIMIT 1
                ";

                $customer_data = DB::Query($sql)->getAssoc();

                if (is_array($customer_data)) {

                    $message = array(
                        'numplate' => $customer_data['car_numplate'],
                        'model' => $customer_data['car_model'],
                        'class' => $customer_data['car_class'],
                        'customer' => trim($customer_data['firstname'] . ' ' . $customer_data['secondname']),
                        'description' => htmlspecialchars_decode($customer_data['description'])
                    );

                } else {

                    $message = array(
                        'numplate' => $numplate,
                        'model' => '',
                        'class' => '1',
                        'customer' => '',
                        'description' => ''
                    );

                }
            }

            Router::response($type, $message, '/dashboard', $arg);
        }
    }

    public static function getcustomer()
    {

        $type = 'danger';
        $arg = array();
        $message = array(
            'numplate' => '',
            'model' => '',
            'class' => '1',
            'customer' => '',
            'description' => ''
        );

        if (Request::isAjax() AND Request::post('numplate')) {


            $type = 'success';

            $numplate = normalizeNumplate(Request::post('numplate'));

            $sql = "
                  SELECT
                    *,
                    (
                        IF (car_numplate = '" . $numplate . "', 100, 0) 
                        + IF (car_numplate LIKE '%" . $numplate . "%', 50, 0)
                    ) AS relevant
                  FROM
                    " . PREFIX . "customers
                  WHERE
                    organization_id = '" . ORGID . "'
                  HAVING 
                    relevant > 0
                  ORDER BY
                    relevant DESC
                  LIMIT 1
                ";

            $customer_data = DB::Query($sql)->getAssoc();

            if (is_array($customer_data)) {

                $message = array(
                    'numplate' => $customer_data['car_numplate'],
                    'model' => $customer_data['car_model'],
                    'class' => $customer_data['car_class'],
                    'customer' => trim($customer_data['firstname'] . ' ' . $customer_data['secondname']),
                    'description' => htmlspecialchars_decode($customer_data['description'])
                );

            } else {

                $message = array(
                    'numplate' => $numplate,
                    'model' => '',
                    'class' => '1',
                    'customer' => '',
                    'description' => ''
                );

            }
        }

        Router::response($type, $message, '/dashboard', $arg);
    }

    public static function getevents()
    {

        $_events_data = array();

        if (Request::isAjax()) {

            $start_date = (int)strtotime(Request::get('start'));
            $end_date = (int)strtotime(Request::get('end'));

            $sql = "
              SELECT
                o.*, DATE_FORMAT(FROM_UNIXTIME(opened), '%Y-%m-%dT%H:%i:%s') AS datetime,
                c.car_numplate
              FROM
                  " . PREFIX . "orders o
                LEFT JOIN
                    " . PREFIX . "customers c
                ON o.customer_id = c.customer_id
              WHERE
                o.order_id <> '0' AND
                o.opened BETWEEN " . $start_date . " AND " . $end_date . "
              GROUP BY order_id
              ORDER BY opened ASC
            ";

            $query = DB::Query($sql);

            while ($row = $query->getAssoc()) {

                $_event_data = array();

                $_event_data['id'] = (int)$row['order_id'];
                $_event_data['title'] = $row['car_numplate'];
                $_event_data['url'] = './orders/show?only=1&order_id=' . (int)$row['order_id'];
                $_event_data['start'] = date('Y-m-d\TH:i:s', (int)$row['opened']);
                if ((int)$row['closed'] > 0) {
                    $_event_data['end'] = date('Y-m-d\TH:i:s', (int)$row['closed']);
                    if ((int)$row['closed'] <= ((int)$row['opened'] + ((int)$row['time_limit'] * 60) + intval($_SESSION['organization_settings']['wdelaytime']))) {
                        $_event_data['backgroundColor'] = '#34bfa3';
                    } else {
                        $_event_data['backgroundColor'] = '#f4516c';
                    }
                    if ((int)$row['payed'] == 0) {
                        $_event_data['backgroundColor'] = '#ffb822';
                        $_event_data['textColor'] = '#000';
                    }
                } else {
                    $_event_data['end'] = date('Y-m-d\TH:i:s', (int)$row['opened'] + ((int)$row['time_limit'] * 60));
                    if (time() > ((int)$row['opened'] + ((int)$row['time_limit'] * 60))) {
                        $_event_data['backgroundColor'] = '#ffb822';
                        $_event_data['textColor'] = '#000';
                    }
                }

                $_events_data[] = $_event_data;
            }

        }

        Json::show($_events_data, true);

    }

}