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



class ModelDocuments extends Model
{

    public static $start_date = 0;

    public static $end_date = 0;

    public function __construct()
    {
        self::$start_date = ($_SESSION['user_settings']['report_range']['start_date']) ? (int)$_SESSION['user_settings']['report_range']['start_date'] : strtotime("1st January " . date('Y'));
        self::$end_date = ($_SESSION['user_settings']['report_range']['end_date']) ? (int)$_SESSION['user_settings']['report_range']['end_date'] : strtotime("1st January next year");
    }

    public static function getDocuments()
    {

        $_documents_data = array();

        $start_date = strtotime('midnight ' . date('Y-m-d', (int)self::$start_date));
        $end_date = strtotime('+1 day ' . date('Y-m-d', (int)self::$end_date)) - 1;

        $sql = "
          SELECT
          	d.*, DATE_FORMAT(FROM_UNIXTIME(regtime), '%Y-%m-%d %H:%i:%s') AS datetime,
          	c.car_numplate,
            u.firstname as ufirstname, u.lastname as ulastname
          FROM
          	".PREFIX."documents d
          LEFT JOIN
                " . PREFIX . "customers c
          ON d.customer_id = c.customer_id
          LEFT JOIN
              " . PREFIX . "users u
          ON
              d.user_id = u.user_id
          WHERE
          	d.doc_id <> '0' AND
          	d.regtime BETWEEN " . $start_date . " AND " . $end_date . "
          ORDER BY d.regtime DESC
          ";

        $query = DB::Query($sql);

        while ($row = $query->getAssoc()) {
            array_push($_documents_data, $row);
        }

        return array(
            "period" => date('Y-m-d', $start_date) . ' - ' . date('Y-m-d', $end_date),
            "documents_data" => $_documents_data
        );

    }

    public static function getDocument($doc_id = null)
    {

        $doc_id = (!$doc_id) ? Request::request('id') : $doc_id;

        if (!$doc_id) return null;

        $document = DB::Query("
            SELECT
                *            
            FROM
                " . PREFIX . "documents              
            WHERE
                doc_id = '" . (int)$doc_id . "' 
                AND organization_id = '" . ORGID . "' LIMIT 1
        ")->getAssoc();

        return $document;

    }

    public static function getTemplates()
    {
        $_templates_data = array();

        $sql = "
          SELECT
          	*, DATE_FORMAT(FROM_UNIXTIME(regtime), '%Y-%m-%d %H:%i:%s') AS created, DATE_FORMAT(FROM_UNIXTIME(edittime), '%Y-%m-%d %H:%i:%s') AS edited
          FROM
          	" . PREFIX . "document_templates
          WHERE
          	organization_id = '" . ORGID . "'
          ";

        $query = DB::Query($sql);

        while ($row = $query->getAssoc()) {
            array_push($_templates_data, $row);
        }

        return $_templates_data;

    }

    public static function getTemplate()
    {
        $_template_data = array();

        $template_id = Request::get('id');

        if (is_numeric($template_id)) {

            $_template_data = DB::Query("
              SELECT
                *, DATE_FORMAT(FROM_UNIXTIME(regtime), '%Y-%m-%d %H:%i:%s') AS created, DATE_FORMAT(FROM_UNIXTIME(edittime), '%Y-%m-%d %H:%i:%s') AS edited
              FROM
                " . PREFIX . "document_templates
              WHERE
                organization_id = '" . ORGID . "'
              AND
                template_id = '" . intval($template_id) . "'
              LIMIT 1
            ")->getAssoc();
        }

        return $_template_data;

    }

    public static function saveTemplate()
    {
        $save = true;

        $type = 'danger';

        $Smarty = Tpl::getInstance();

        $template_id = Request::post('template_id');
        $action = Request::post('action');

        if (!is_numeric($template_id))
            $save = false;

        if (($save OR $action == 'add') AND Permission::perm('documents_control')) {

            if ($template_id) {
                $sql = "
						UPDATE
							" . PREFIX . "document_templates
						SET                            
                            `name` = '" . Secure::sanitize(Request::post('template_name')) . "',
                            numerate = '" . Secure::sanitize(Request::post('template_numerate')) . "',
                            `active` = '1',
                            `deleted` = '0',
                            `department` =  '" . intval(Request::post('template_department')) . "',
                            `show` =  '" . intval(Request::post('template_show')) . "',
                            `edittime` = '" . time() . "',
                            `template` = '" . addslashes(htmlspecialchars(str_replace('<br></p>','</p>',Request::post('content')))) . "'
						WHERE
							template_id = '" . $template_id . "' AND
							organization_id = '" . ORGID . "'
					";

                DB::Query($sql);

                $message = $Smarty->_get('documents_template_edit_success');
                $type = 'success';

            } else {

                $sql = "
                        INSERT INTO
                            " . PREFIX . "document_templates
                        SET                            
                            `name` = '" . Secure::sanitize(Request::post('template_name')) . "',
                            numerate = '" . Secure::sanitize(Request::post('template_numerate')) . "',
                            `organization_id` = '" . ORGID . "',
                            `active` = '1',
                            `deleted` = '0',
                            `department` =  '" . intval(Request::post('template_department')) . "',
                            `show` =  '" . intval(Request::post('template_show')) . "',
                            `regtime` = '" . time() . "',
                            `edittime` = '" . time() . "',
                            `deltime` = '0',
                            `template` = '" . addslashes(htmlspecialchars(Request::post('content'))) . "'                            
						";

                DB::Query($sql);

                $service_id = DB::getInsertId();

                if ($service_id) {
                    $message = $Smarty->_get('documents_template_edit_success');
                    $type = 'success';
                } else {
                    $message = $Smarty->_get('documents_template_edit_error');
                }
            }
        } else {
            $message = $Smarty->_get('documents_template_edit_error');
        }

        Router::response($type, $message, '/route/documents/templates');
    }

}