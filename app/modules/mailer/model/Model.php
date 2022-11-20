<?php


/**
 * This file is part of the dashboard.rgbvision.net package.
 *
 * (c) Alex Graham <contact@rgbvision.net>
 *
 * @package    dashboard.rgbvision.net
 * @author     Alex Graham <contact@rgbvision.net>
 * @copyright  Copyright 2017-2022, Alex Graham
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    3.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class MailerModel extends Model
{
    public function saveMailer()
    {
        $type = 'danger';

        $Smarty = Template::getInstance();

        $permission = Permissions::has('mailer_edit');

        if ($permission) {
            $message = $Smarty->_get('mailer_message_edit_success');
            $type = 'success';
        } else {
            $message = $Smarty->_get('mailer_message_perm_danger');
        }

        Router::response($type, $message, ABS_PATH . 'mailer');
    }
}