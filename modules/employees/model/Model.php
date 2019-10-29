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



class ModelEmployees extends Model
{

    public static function getEmployees()
    {

        $sql = "
				SELECT
					emp.*,
					usr.linked_employee,
					dep.name AS department_name
				FROM
					" . PREFIX . "employees AS emp
				LEFT JOIN
					" . PREFIX . "departments AS dep
					ON emp.department_id = dep.department_id
                LEFT JOIN
                    " . PREFIX . "users AS usr
                    ON emp.employee_id = usr.linked_employee
				WHERE
					emp.deleted != '1'
				AND emp.organization_id = " . ORGID . "
				ORDER BY
					emp.lastname ASC
			";

        $query = DB::Query($sql);

        $employees = array();

        while ($row = $query->getAssoc()) {
            $row['deletable'] = self::getDeletable($row['employee_id']);
            $row['editable'] = self::getEditable($row['employee_id']);
            $row['avatar'] = (file_exists(CP_DIR . '/uploads/employees/' . md5((int)$row['employee_id']) . '-' . md5((int)ORGID) . '.jpg')) ?
                ('/uploads/employees/' . md5((int)$row['employee_id']) . '-' . md5((int)ORGID) . '.jpg') : '/uploads/employees/default.jpg';

            array_push($employees, $row);
        }

        return $employees;
    }

    public static function getDeletable($employee_id)
    {
        $deletable = (Permission::perm('employees_delete'));

        return $deletable;
    }

    public static function getEditable($employee_id)
    {
        $editable = (Permission::perm('employees_edit'));

        return $editable;
    }

    public static function canAddEmployee()
    {
        $max_employees = DB::Query("
            SELECT
              plan.max_employees
            FROM " . PREFIX . "organizations AS org
            LEFT JOIN
                " . PREFIX . "plans AS plan
                ON org.plan_id = plan.plan_id
            WHERE
                organization_id = " . ORGID . "
        ")->getRow();

        $employee_count = DB::Query("
            SELECT
              COUNT(*)
            FROM " . PREFIX . "employees
            WHERE
                organization_id = " . ORGID . " AND
                active = '1'
        ")->getRow();

        return ((int)$employee_count < (int)$max_employees);
    }

    public static function getEmployee($employee_id)
    {
        $sql = "
				SELECT
					emp.*,
					dep.name AS department_name
				FROM
					" . PREFIX . "employees AS emp
				LEFT JOIN
					" . PREFIX . "departments AS dep
					ON emp.department_id = dep.department_id
				WHERE
				    emp.employee_id = " . (int)$employee_id . "
				AND
					emp.organization_id = " . ORGID . "
				LIMIT 1
			";

        $employee = DB::Query($sql)->getAssoc();

        $employee['deletable'] = self::getDeletable($employee_id);
        $employee['editable'] = self::getEditable($employee_id);
        $employee['salary'] = unserialize($employee['salary']);

        if (file_exists(CP_DIR . '/uploads/employees/' . md5((int)$employee_id) . '-' . md5((int)ORGID) . '.jpg')) {
            $employee['employee_avatar'] = '/uploads/employees/' . md5((int)$employee_id) . '-' . md5((int)ORGID) . '.jpg';
        }

        return $employee;
    }

    public static function getDepartments()
    {

        $sql = "
				SELECT
					department_id,
					name
				FROM
					" . PREFIX . "departments
				ORDER BY department_id
			";

        $query = DB::Query($sql);

        $departments_ids = array();
        $departments_names = array();

        while ($row = $query->getAssoc()) {
            array_push($departments_ids, $row['department_id']);
            array_push($departments_names, $row['name']);
        }

        return array(
            'ids' => $departments_ids,
            'names' => $departments_names
        );
    }

    /*
     |--------------------------------------------------------------------------------------
     | saveEmployee
     |--------------------------------------------------------------------------------------
     |
     |
     */

    public static function saveEmployee()
    {
        $save = true;

        $type = 'danger';
        $arg = array();

        $Smarty = Tpl::getInstance();

        $employee_id = Request::post('employee_id');
        $department_id = Request::post('department');
        $action = Request::post('action');

        if (!is_numeric($employee_id) OR !is_numeric($department_id))
            $save = false;

        if (!Permission::perm('employees_edit'))
            $save = false;

        if ($save OR $action == 'add') {

            $salary = Request::post('salary');
            $salary_data = array();

            foreach ($salary as $k => $v) {
                if (
                    (is_numeric($k)) AND
                    (intval($v['operand']) > 0) AND
                    (floatval($v['cost']) != 0) AND
                    (intval($v['unit']) > 0)
                ) {
                    $salary_data[] = array(
                        'operand' => intval($v['operand']),
                        'cost' => floatval($v['cost']),
                        'unit' => intval($v['unit'])
                    );
                }
            }

            if ($employee_id) {

                $sql = "
						UPDATE
							" . PREFIX . "employees
						SET
							firstname = '" . Secure::sanitize(Request::post('firstname')) . "',
							lastname = '" . Secure::sanitize(Request::post('lastname')) . "',
							phone = '" . normalizePhone(Request::post('phone')) . "',
							description = '" . addslashes(htmlspecialchars(Request::post('description'))) . "',
							salary = '" . (!empty($salary_data) ? serialize($salary_data) : '') . "',
							department_id = '" . $department_id . "'
						WHERE
							employee_id = '" . $employee_id . "'
						AND
						    organization_id = " . ORGID . "
						    
					";

                DB::Query($sql);

                $message = $Smarty->_get('employees_message_edit_success');
                $type = 'success';
            } else {
                $sql = "
                        INSERT INTO
                            " . PREFIX . "employees
                        SET
							firstname = '" . Secure::sanitize(Request::post('firstname')) . "',
							lastname = '" . Secure::sanitize(Request::post('lastname')) . "',
							phone = '" . normalizePhone(Request::post('phone')) . "',
							salary = '" . (!empty($salary_data) ? serialize($salary_data) : '') . "',
							description = '" . addslashes(htmlspecialchars(Request::post('description'))) . "',
							department_id = '" . $department_id . "',
							organization_id = '" . ORGID . "',
							reg_time = '" . time() . "'
						";

                DB::Query($sql);

                $employee_id = DB::getInsertId();

                if ($employee_id) {
                    $message = $Smarty->_get('employees_message_edit_success');
                    $type = 'success';
                    $arg = array('id' => $employee_id);
                } else {
                    $message = $Smarty->_get('employees_message_edit_error');
                }
            }

            if (!empty(Request::post('new_avatar'))) {
                $img_decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', Request::post('new_avatar')));
                if ($img_decoded != false) {
                    file_put_contents(CP_DIR . '/uploads/employees/' . md5((int)$employee_id) . '-' . md5((int)ORGID) . '.jpg', $img_decoded);
                    if ($employee_id == UID) {
                        Session::setvar('employee_avatar', '/uploads/employees/' . md5((int)$employee_id) . '-' . md5((int)ORGID) . '.jpg');
                    }
                }
            }

        } else {
            $message = $Smarty->_get('employees_message_edit_error');
        }

        Router::response($type, $message, '/route/employees', $arg);
    }

    /*
    |--------------------------------------------------------------------------------------
    | saveEmployee
    |--------------------------------------------------------------------------------------
    |
    |
    */

    public static function deleteEmployee()
    {
        $delete = true;

        $type = 'danger';
        $arg = array();

        $Smarty = Tpl::getInstance();

        $employee_id = Request::get('employee_id');

        if (!is_numeric($employee_id))
            $delete = false;

        if (!Permission::perm('employees_delete'))
            $delete = false;

        if ($delete) {

            $sql = "
                UPDATE
                    " . PREFIX . "employees
                SET
                    active = '0',
                    deleted = '1',
                    del_time = '" . time() . "'
                WHERE
                    employee_id = '" . $employee_id . "'
                AND
                    organization_id = " . ORGID . "
            ";

            DB::Query($sql);

            $message = $Smarty->_get('employees_message_edit_success');
            $type = 'success';

        } else {
            $message = $Smarty->_get('employees_message_edit_error');
        }

        Router::response($type, $message, '/route/employees', $arg);
    }

}