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



class ModelCompany extends Model
{
    public static function getCompany()
    {
        $company = DB::Query("
                    SELECT
                      name, addr, settings
                    FROM " . PREFIX . "organizations
                    WHERE
                        organization_id = " . ORGID . "
                ")->getAssoc();

        return array(
            'name' => $company['name'],
            'addr' => $company['addr'],
            'settings' => unserialize($company['settings'])
        );
    }


    public static function saveCompany()
    {

        $type = 'danger';

        $Smarty = Tpl::getInstance();

        $permission = Permission::perm('company_edit');

        $company = Request::post('company_id');

        if ($permission) {

            if (!empty($company) AND ((int)ORGID == (int)$company)) {

                $settings = array(
                    'wboxes' => intval(Request::post('wboxes')),
                    'sboxes' => intval(Request::post('sboxes')),
                    'cboxes' => intval(Request::post('cboxes')),
                    'classes' => intval(Request::post('classes')),
                    'wreceivetime' => intval(Request::post('wreceivetime')),
                    'wreturntime' => intval(Request::post('wreturntime')),
                    'wearlytime' => intval(Request::post('wearlytime')),
                    'wdelaytime' => intval(Request::post('wdelaytime')),
                    'creceivetime' => intval(Request::post('creceivetime')),
                    'creturntime' => intval(Request::post('creturntime')),
                    'cearlytime' => intval(Request::post('cearlytime')),
                    'cdelaytime' => intval(Request::post('cdelaytime'))
                );

                $sql = "
                    UPDATE
                        " . PREFIX . "organizations
                    SET
                        name = '" . Request::post('name') . "',
                        addr = '" . Request::post('addr') . "',
                        settings = '" . serialize($settings) . "'
                    WHERE
                        organization_id = " . ORGID . "
                        
                ";

                DB::Query($sql);

                $message = $Smarty->_get('company_message_edit_success');
                $type = 'success';
            } else {
                $message = $Smarty->_get('company_message_edit_danger');
            }

        } else {
            $message = $Smarty->_get('company_message_perm_danger');
        }

        Router::response($type, $message, '/route/company');
    }
}