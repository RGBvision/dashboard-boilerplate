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



class ModelOrders extends Model
{

    public static $start_date = 0;

    public static $end_date = 0;

    public function __construct()
    {
        self::$start_date = ($_SESSION['user_settings']['report_range']['start_date']) ? (int)$_SESSION['user_settings']['report_range']['start_date'] : strtotime("1st January " . date('Y'));
        self::$end_date = ($_SESSION['user_settings']['report_range']['end_date']) ? (int)$_SESSION['user_settings']['report_range']['end_date'] : strtotime("1st January next year");
    }

    public static function getOrders()
    {

        $_orders_data = array();

        $start_date = strtotime('today');
        $end_date = strtotime('tomorrow') - 1;

        $sql = "
            SELECT
              o.order_id, o.sum, o.opened, o.closed, o.payed, o.customer_id, DATE_FORMAT(FROM_UNIXTIME(o.opened), '%Y-%m-%d %H:%i:%s') AS datetime,
              c.car_numplate,
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
        }

        return array(
            "period" => date('Y-m-d', $start_date),
            "orders_data" => $_orders_data
        );

    }

    public static function addOrder()
    {

        $save = true;

        $type = 'danger';

        $Smarty = Tpl::getInstance();

        $order_id = Request::post('order_id');
        $action = Request::post('action');

        $_order_time = time();

        if (!is_numeric($order_id))
            $save = false;

        if (($save OR $action == 'add') AND Permission::perm('orders_add')) {

            $employee_salary = DB::Query("
                SELECT
                    salary
                FROM
                    " . PREFIX . "employees
                WHERE
                    employee_id = '" . (int)Request::post('employee') . "'
                AND
                    organization_id = '" . ORGID . "'
            ")->getRow();

            if (!empty($employee_salary)) {
                $employee_salary = unserialize($employee_salary);
            } else {
                $employee_salary = array();
            }

            $employee_reward = DB::Query("
                SELECT
                    emp.salary
                FROM
                    " . PREFIX . "users AS usr
                LEFT JOIN
                    " . PREFIX . "employees AS emp
                ON
                    emp.employee_id = usr.linked_employee
                WHERE
                    usr.user_id = '" . UID . "'
                AND
                    emp.organization_id = '" . ORGID . "'
            ")->getRow();

            if (!empty($employee_reward)) {
                $employee_reward = unserialize($employee_reward);
            } else {
                $employee_reward = array();
            }

            if ($order_id) {
                $sql = "
						UPDATE
							" . PREFIX . "orders
						SET
                            car_numplate = '" . normalizeNumplate(Request::post('car_numplate')) . "',
                            car_model = '" . mb_strtoupper(Request::post('car_model')) . "',
                            car_class = '" . Request::post('car_class') . "',
                            last_activity = '" . $_order_time . "',
                            reg_time = '" . $_order_time . "'
						WHERE
							order_id = '" . $order_id . "' AND
							organization_id = '" . ORGID . "'
					";

                DB::Query($sql);

                $message = $Smarty->_get('orders_message_edit_success');
                $type = 'success';

            } else {

                $sql = "
                  SELECT
                    customer_id
                  FROM
                    " . PREFIX . "customers
                  WHERE
                    car_numplate = '" . normalizeNumplate(Request::post('car_numplate')) . "'
                  AND
                    organization_id = '" . ORGID . "'
                  LIMIT 1
                ";

                $customer_id = DB::Query($sql)->getRow();

                if ($customer_id) {
                    $sql = "
                        UPDATE
                            " . PREFIX . "customers
                        SET
                            last_activity = '" . $_order_time . "'
                        WHERE
                            customer_id = '" . intval($customer_id) . "'
                        AND
                            organization_id = '" . ORGID . "'
					";

                    DB::Query($sql);
                } else {
                    $sql = "
                        INSERT INTO
                            " . PREFIX . "customers
                        SET
                            organization_id = '" . ORGID . "',
                            car_numplate = '" . normalizeNumplate(Request::post('car_numplate')) . "',
                            car_model = '" . mb_strtoupper(Request::post('car_model')) . "',
                            car_class = '" . (int)Request::post('car_class') . "',
                            last_activity = '" . $_order_time . "',
                            reg_time = '" . $_order_time . "'
					";

                    DB::Query($sql);

                    $customer_id = DB::getInsertId();
                }

                if (!empty(Request::post('car_photo'))) {

                    $img_decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', Request::post('car_photo')));

                    $photosubdir = strtolower(sprintf('%08x/%08x', (int)ORGID, (int)$customer_id));

                    if (!file_exists(CP_DIR . '/uploads/customers/' . $photosubdir)) {
                        @mkdir(CP_DIR . '/uploads/customers/' . $photosubdir, 0777, true);
                    }

                    if ($img_decoded != false) {
                        file_put_contents(CP_DIR . '/uploads/customers/' . $photosubdir . '/' . strtolower(sprintf('%016x', (int)$_order_time)) . '.jpg', $img_decoded);
                    }
                }

                $selected_services = Request::post('services');

                $selected_services_ids = array_map('intval', array_keys($selected_services));

                $selected_services_ids = implode(',', $selected_services_ids);

                $services_array = array();

                $time_limit = 0;
                $order_sum = 0;
                $order_salary = 0;
                $order_reward = 0;

                $routing = array();

                $query = DB::Query("
                    SELECT
                        *
                    FROM
                        " . PREFIX . "services
                    WHERE
                        service_id IN (" . $selected_services_ids . ")
                    AND
                        service_id != 0
                    AND
                        organization_id = '" . ORGID . "'
                ");

                while ($row = $query->getAssoc()) {

                    $row['calculation'] = unserialize($row['calculation']);

                    $name = '';
                    $price = 0;
                    $time = 0;
                    $qty = intval($selected_services[(int)$row['service_id']]['val']);

                    switch ((int)$row['type']) {
                        case 1:
                            $name = $row['name'];
                            $price = floatval($row['calculation'][0]['cost']);
                            $time = intval($row['calculation'][0]['time']);
                            $order_salary += self::calcReward(($price * $qty), floatval($row['calculation'][0]['salary']), $row['calculation'][0]['unit']);
                            $order_reward += self::calcReward(($price * $qty), floatval($row['calculation'][0]['reward']), $row['calculation'][0]['measure']);
                            $routing[] = array('qty' => $qty, 'cons' => $row['calculation'][0]['routing']);
                            break;
                        case 2:
                            $name = $row['name'];
                            $price = floatval($row['calculation'][Request::post('car_class')]['cost']);
                            $time = intval($row['calculation'][Request::post('car_class')]['time']);
                            $order_salary += self::calcReward(($price * $qty), floatval($row['calculation'][Request::post('car_class')]['salary']),
                                $row['calculation'][Request::post('car_class')]['unit']);
                            $order_reward += self::calcReward(($price * $qty), floatval($row['calculation'][Request::post('car_class')]['reward']),
                                $row['calculation'][Request::post('car_class')]['measure']);
                            $routing[] = array('qty' => $qty, 'cons' => $row['calculation'][Request::post('car_class')]['routing']);
                            break;
                        case 3:
                            $name = $row['name'] . ' (' . $row['calculation']['parametric'][intval($selected_services[(int)$row['service_id']]['opt'])]['name'] . ')';
                            $price = floatval($row['calculation']['parametric'][intval($selected_services[(int)$row['service_id']]['opt'])]['cost']);
                            $time = intval($row['calculation']['parametric'][intval($selected_services[(int)$row['service_id']]['opt'])]['time']);
                            $order_salary += self::calcReward(($price * $qty), floatval($row['calculation']['parametric'][intval($selected_services[(int)$row['service_id']]['opt'])]['salary']),
                                $row['calculation']['parametric'][intval($selected_services[(int)$row['service_id']]['opt'])]['unit']);
                            $order_reward += self::calcReward(($price * $qty), floatval($row['calculation']['parametric'][intval($selected_services[(int)$row['service_id']]['opt'])]['reward']),
                                $row['calculation']['parametric'][intval($selected_services[(int)$row['service_id']]['opt'])]['measure']);
                            $routing[] = array('qty' => $qty, 'cons' => $row['calculation']['parametric'][intval($selected_services[(int)$row['service_id']]['opt'])]['routing']);
                            break;
                        default:
                            break;
                    }

                    $services_array[(int)$row['service_id']] = array(
                        'id' => (int)$row['service_id'],
                        'name' => $name,
                        'price' => $price,
                        'qty' => $qty,
                        'cost' => ($price * $qty),
                        'time' => $time
                    );

                    $order_sum += $price * $qty;
                    $time_limit += $time * $qty;

                    foreach ($employee_salary as $k => $v) {
                        switch ($v['operand']) {
                            case 6: // Выполнение основной услуги
                                if ($row['prime'] == '1') {
                                    $order_salary += self::calcReward(($price * $qty), floatval($v['cost']), $v['unit']);
                                }
                                break;
                            case 7: // Выполнение не основной услуги
                                if ($row['prime'] == '0') {
                                    $order_salary += self::calcReward(($price * $qty), floatval($v['cost']), $v['unit']);
                                }
                                break;
                            case 8: // Выполнение акционной услуги
                                if ($row['bonus'] == '1') {
                                    $order_salary += self::calcReward(($price * $qty), floatval($v['cost']), $v['unit']);
                                }
                                break;
                        }
                    }

                    foreach ($employee_reward as $k => $v) {
                        switch ($v['operand']) {
                            case 10: // Продажа основной услуги
                                if ($row['prime'] == '1') {
                                    $order_reward += self::calcReward(($price * $qty), floatval($v['cost']), $v['unit']);
                                }
                                break;
                            case 11: // Продажа не основной услуги
                                if ($row['prime'] == '0') {
                                    $order_reward += self::calcReward(($price * $qty), floatval($v['cost']), $v['unit']);
                                }
                                break;
                            case 12: // Продажа акционной услуги
                                if ($row['bonus'] == '1') {
                                    $order_reward += self::calcReward(($price * $qty), floatval($v['cost']), $v['unit']);
                                }
                                break;
                        }
                    }

                }

                foreach ($employee_salary as $k => $v) {
                    switch ($v['operand']) {
                        case 5: // Выполнение заказа
                            $order_salary += self::calcReward($order_sum, floatval($v['cost']), $v['unit']);
                            break;
                    }
                }

                foreach ($employee_reward as $k => $v) {
                    switch ($v['operand']) {
                        case 9: // Прием заказа
                            $order_reward += self::calcReward($order_sum, floatval($v['cost']), $v['unit']);
                            break;
                    }
                }

                $_routing = array();
                foreach ($routing as $k => $v) {
                    if (!empty($v['cons'])) {
                        foreach ($v['cons'] as $kk => $vv) {
                            $_routing[$kk] = (isset($_routing[$kk])) ? ($_routing[$kk] + (floatval($vv) * $v['qty'])) : (floatval($vv) * $v['qty']);
                        }
                    }
                }
                $routing = $_routing;
                unset($_routing);

                $order_guid = strtolower(sprintf('%08x-%08x-%016x',
                    (int)ORGID,
                    (int)$customer_id,
                    (int)$_order_time
                ));

                $sql = "
                        INSERT INTO
                            " . PREFIX . "orders
                        SET
                            organization_id = '" . ORGID . "',
                            user_id = '" . UID . "',
                            order_guid = '" . $order_guid . "',
                            customer_id = '" . $customer_id . "',
                            status = '0',
                            department = '" . Request::post('department') . "',
                            employee_id = '" . (int)Request::post('employee') . "',
                            post_id = '" . Request::post('post_id') . "',
                            sum = '" . floatval($order_sum) . "',
                            reward = '" . floatval($order_reward) . "',
                            salary = '" . floatval($order_salary) . "',
                            paytype = '0',
                            dependencies = '" . serialize($services_array) . "',
                            routing = '" . serialize($routing) . "',
                            time_limit = '" . $time_limit . "',
                            opened = '" . $_order_time . "'
						";

                DB::Query($sql);

                $order_id = DB::getInsertId();

                if ($order_id) {

                    foreach ($routing as $k => $v) {
                        if (floatval($v) != 0) {
                            $sql = "
                                INSERT INTO
                                    " . PREFIX . "goods_movement
                                SET
                                    `count` = " . (floatval($v) * -1) . ",
                                    `timestamp` = " . $time . ",
                                    good_id = " . (int)$k . ",
                                    user_id = " . UID . ",
                                    forced = '0',
                                    organization_id = " . ORGID . "
                            ";

                            DB::Query($sql);
                        }
                    }

                    $order_data = self::getOrderDetails($order_id, true);

                    $query = DB::Query("
                        SELECT
                            *
                        FROM
                            " . PREFIX . "document_templates
                        WHERE
                            organization_id = '" . ORGID . "'
                        AND
                            (department = '0' OR department = '1')
					");

                    while ($row = $query->getAssoc()) {

                        $order_table = new Tpl();

                        $order_table->assign('order_data', $order_data);
                        $order_table_content = $order_table->fetch(CP_DIR . '/modules/orders/view/details.tpl');

                        $total_docs = DB::Query("
                            SELECT
                              COUNT(*)
                            FROM
                                " . PREFIX . "documents
                            WHERE
                                organization_id = '" . ORGID . "'                                                          
						")->getRow();

                        $total_docs_typed = DB::Query("
                            SELECT
                              COUNT(*)
                            FROM
                                " . PREFIX . "documents
                            WHERE
                                organization_id = '" . ORGID . "'
                            AND
                                template_id = '" . $row['template_id'] . "'                                                       
						")->getRow();

                        $tags = array(
                            '#N#',
                            '#NN#',
                            '#D#',
                            '#M#',
                            '#Y#'
                        );

                        $replaces = array(
                            ($total_docs_typed + 1),
                            ($total_docs + 1),
                            date('d', $_order_time),
                            date('m', $_order_time),
                            date('Y', $_order_time)
                        );

                        $doc_numerate = str_replace($tags, $replaces, htmlspecialchars_decode($row['numerate']));

                        $tags = array(
                            '#ДАТА#',
                            '#ВРЕМЯ#',
                            '#КЛИЕНТ_ИМЯ#',
                            '#КЛИЕНТ_ОТЧЕСТВО#',
                            '#КЛИЕНТ_ФАМИЛИЯ#',
                            '#АВТО_НОМЕР#',
                            '#АВТО_МАРКА#',
                            '#ОТВЕТСТВЕННЫЙ#',
                            '<p>#ЗАКАЗ_ТАБЛ#</p>',
                            '#ЗАКАЗ_ТАБЛ#',
                            '#ЗАКАЗ_ИТОГ#'
                        );

                        $replaces = array(
                            date('d-m-Y', $_order_time),
                            date('H:i:s', $_order_time),
                            $order_data['customer']['firstname'],
                            $order_data['customer']['secondname'],
                            $order_data['customer']['lastname'],
                            $order_data['customer']['car_numplate'],
                            $order_data['customer']['car_model'],
                            '#ОТВЕТСТВЕННЫЙ#',
                            '<table class="table table-striped nowrap">' . $order_table_content . '</table>',
                            '<table class="table table-striped nowrap">' . $order_table_content . '</table>',
                            number_format(floatval($order_sum), 2, '.', '')
                        );

                        $doc_content = str_replace($tags, $replaces, htmlspecialchars_decode($row['template']));

                        DB::Query("
                            INSERT INTO
                                " . PREFIX . "documents
                            SET
                                `name` = '" . $row['name'] . "',
                                template_id = '" . $row['template_id'] . "',
                                numerate = '" . $doc_numerate . "',
                                order_id = '" . $order_id . "',
                                organization_id = '" . ORGID . "',
                                customer_id = '" . $customer_id . "',
                                user_id = '" . UID . "',
                                employee_id = '" . (int)Request::post('employee') . "',
                                post_id = '" . Request::post('post_id') . "',
                                active = '1',
                                deleted = '0',
                                department = '" . Request::post('department') . "',
                                regtime = '" . $_order_time . "',
                                edittime = '" . $_order_time . "',
                                deltime = '0',
                                content = '" . addslashes(htmlspecialchars($doc_content)) . "'                            
						");
                    }

                    $message = $Smarty->_get('orders_message_edit_success');
                    $type = 'success';

                } else {
                    $message = $Smarty->_get('orders_message_edit_error');
                }
            }
        } else {
            $message = $Smarty->_get('orders_message_edit_error');
        }

        if ($action == 'add') {
            Router::response($type, $message, '/dashboard');
        } else {
            Router::response($type, $message, '/orders');
        }
    }

    private static function calcReward($sum, $reward, $operator)
    {
        if ($operator == '2') {
            return ((float)$sum / 100 * (float)$reward);
        } else {
            return (float)$reward;
        }
    }

    /*
     |--------------------------------------------------------------------------------------
     | addOrder
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function getOrderDetails($order_id = null, $exclude_docs = false)
    {

        $order_id = (!$order_id) ? Request::request('order_id') : $order_id;

        if (!$order_id) return null;

        $order = DB::Query("
            SELECT
                o.*,
                e.firstname as efirstname,
                e.lastname as elastname,
                u.firstname as ufirstname,
                u.lastname as ulastname             
            FROM
                " . PREFIX . "orders o             
            LEFT JOIN
                " . PREFIX . "employees e             
                    ON o.employee_id = e.employee_id             
            LEFT JOIN
                " . PREFIX . "users u             
                    ON o.employee_id = u.user_id               
            WHERE
                o.order_id = '" . (int)$order_id . "' 
                AND o.organization_id = '" . ORGID . "' LIMIT 1
        ")->getAssoc();

        $order['services'] = unserialize($order['dependencies']);
        $order['delayed_class'] = '';

        if ((int)$order['closed'] > 0) {
            if ((int)$order['closed'] <= ((int)$order['opened'] + ((int)$order['time_limit'] * 60) + intval($_SESSION['organization_settings']['wdelaytime']))) {
                $order['delayed'] = 'не просрочен';
            } else {
                $order['delayed'] = 'просрочен на ' . gmdate('H:i:s', ((int)$order['closed'] - ((int)$order['opened'] + ((int)$order['time_limit'] * 60))));
                $order['delayed_class'] = 'table-danger';
            }
        } else {
            if (time() > ((int)$order['opened'] + ((int)$order['time_limit'] * 60))) {
                $order['delayed'] = 'просрочен';
                $order['delayed_class'] = 'table-danger';
            } else {
                $order['delayed'] = 'не просрочен';
            }
        }

        $order['opened'] = ((int)$order['opened'] > 0) ? date('Y-m-d H:i:s', (int)$order['opened']) : 'нет';
        $order['payed'] = ((int)$order['payed'] > 0) ? date('Y-m-d H:i:s', (int)$order['payed']) : 'нет';
        $order['payed_class'] = ((int)$order['payed'] > 0) ? '' : 'table-danger';
        $order['closed'] = ((int)$order['closed'] > 0) ? date('Y-m-d H:i:s', (int)$order['closed']) : 'нет';
        $order['time_limit'] = ((int)$order['time_limit'] > 0) ? gmdate('H:i:s', ((int)$order['time_limit'] * 60)) : 'нет';

        $customer = DB::Query("
          SELECT
          	*
          FROM
          	" . PREFIX . "customers
          WHERE
          	customer_id = '" . $order['customer_id'] . "' AND
          	organization_id = '" . ORGID . "'
          LIMIT 1
        ")->getAssoc();

        $documents = array();

        if (!$exclude_docs) {
            $query = DB::Query("
              SELECT
                doc_id, name, content
              FROM
                " . PREFIX . "documents
              WHERE
                active = '1' AND
                order_id = '" . (int)$order_id . "' AND
                organization_id = '" . ORGID . "'
            ");

            while ($row = $query->getAssoc()) {
                array_push($documents, $row);
            }
        }

        return array(
            'order' => $order,
            'customer' => $customer,
            'documents' => $documents
        );

    }

    /*
     |--------------------------------------------------------------------------------------
     | closeOrder
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function closeOrder()
    {

        if (Permission::check('orders_add')) {

            $time = time();

            $order = DB::Query("
                SELECT
                    *
                FROM
                    " . PREFIX . "orders
                WHERE
                    order_id = '" . (int)Request::get('order_id') . "' AND
                    organization_id = '" . ORGID . "'
            ")->getAssoc();

            $employee_salary = DB::Query("
                SELECT
                    salary
                FROM
                    " . PREFIX . "employees
                WHERE
                    employee_id = '" . (int)$order['employee_id'] . "'
                AND
                    organization_id = '" . ORGID . "'
            ")->getRow();

            if (!empty($employee_salary)) {
                $employee_salary = unserialize($employee_salary);
            } else {
                $employee_salary = array();
            }

            $_min_time = intval($order['opened']) + (intval($order['time_limit']) * 60) - intval($_SESSION['organization_settings']['wearlytime']);
            $_max_time = intval($order['opened']) + (intval($order['time_limit']) * 60) + intval($_SESSION['organization_settings']['wdelaytime']);

            $_salary = floatval($order['salary']);

            foreach ($employee_salary as $k => $v) {
                switch ($v['operand']) {
                    case 13: // Досрочное выполнение работы
                        if ($time < $_min_time) {
                            $_salary += self::calcReward($order['sum'], floatval($v['cost']), $v['unit']);
                        }
                        break;
                    case 14: // Просрочка регламента работ
                        if ($time > $_max_time) {
                            $_salary += self::calcReward($order['sum'], floatval($v['cost']), $v['unit']);
                        }
                        break;
                }
            }

            $sql = "
                UPDATE
                    " . PREFIX . "orders
                SET
                    closed = '" . $time . "',
                    salary = '" . $_salary . "'
                WHERE
                    order_id = '" . (int)Request::get('order_id') . "' AND
                    organization_id = '" . ORGID . "'
            ";

            DB::Query($sql);
        }

        Router::response('success', 'success', '/dashboard');

    }

    /*
     |--------------------------------------------------------------------------------------
     | payOrder
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function payOrder()
    {

        if (Permission::check('orders_payment') AND ((int)Request::post('order_id') > 0)) {

            $sql = "
                UPDATE
                    " . PREFIX . "orders
                SET
                    status = '1',
                    payed = '" . time() . "',
                    paytype = '" . (int)Request::post('paytype') . "'
                WHERE
                    order_id = '" . (int)Request::post('order_id') . "' AND
                    organization_id = '" . ORGID . "'
            ";

            DB::Query($sql);
        }

        Router::response('success', 'success', '/dashboard');

    }

}