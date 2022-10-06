<?php


/**
 * This source file is part of the AVE.cms. More information,
 * documentation and tutorials can be found at http://www.ave-cms.ru
 *
 * @package      AVE.cms
 * @file         admin/modules/mailer/model/model.php
 * @author       AVE.cms <support@ave-cms.ru>
 * @copyright    2007-2017 (c) AVE.cms
 * @link         http://www.ave-cms.ru
 * @version      4.0
 * @since        $date$
 * @license      license GPL v.2 http://www.ave-cms.ru/license.txt
 */


class MailerModel extends Model
{
    public static function saveMailer()
    {
        Router::demo();

        $type = 'danger';

        $Smarty = Template::getInstance();

        $permission = Permission::perm('mailer_edit');

        if ($permission) {
            $message = $Smarty->_get('mailer_message_edit_success');
            $type = 'success';
        } else {
            $message = $Smarty->_get('mailer_message_perm_danger');
        }

        Router::response($type, $message, 'index.php?router=mailer');
    }
}