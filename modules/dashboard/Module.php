<?php

/**
 * This source file is part of the AVE.cms. More information,
 * documentation and tutorials can be found at http://www.ave-cms.ru
 *
 * @package      AVE.cms
 * @file         admin/modules/dashboard/Module.php
 * @author       AVE.cms <support@ave-cms.ru>
 * @copyright    2007-2017 (c) AVE.cms
 * @link         http://www.ave-cms.ru
 * @version      4.0
 * @since        $date$
 * @license      license GPL v.2 http://www.ave-cms.ru/license.txt
 */

class ModuleDashboard extends Module
{
    //-- Версия модуля
    public static $version = '1.0';

    //-- Дата
    public static $date = '18.12.2017';

    //-- Системное имя модуля
    public static $_moduleName = 'dashboard';


    public function __construct()
    {
        //-- Родитель
        parent::__construct();


        /*
        |--------------------------------------------------------------------------------------
        | Назначаем права
        |--------------------------------------------------------------------------------------
        |
        */
        $_permissions = array('dashboard_view', 'dashboard_summary', 'dashboard_getorders');
        Permission::add('dashboard', $_permissions, 'sli sli-programming-computer-pie-graph', 10);


        /*
        |--------------------------------------------------------------------------------------
        | Инстанс Smarty
        |--------------------------------------------------------------------------------------
        |
        */
        $Smarty = Tpl::getInstance();


        /*
        |--------------------------------------------------------------------------------------
        | Подгружаем файл переводов
        |--------------------------------------------------------------------------------------
        |
        */
        $Smarty->_load(CP_DIR . '/modules/dashboard/lang/' . $_SESSION['current_language'] . '.ini', 'name');


        /*
        |--------------------------------------------------------------------------------------
        | Подгружаем файл переводов (Права)
        |--------------------------------------------------------------------------------------
        |
        */
        $Smarty->_load(CP_DIR . '/modules/dashboard/lang/' . $_SESSION['current_language'] . '.ini', 'permissions');


        /*
        |--------------------------------------------------------------------------------------
        | Добавляем меню навигации
        |--------------------------------------------------------------------------------------
        |
        */
        Navigation::add(
            10,
            $Smarty->_get('dashboard_menu_name'),
            'sli sli-programming-computer-pie-graph',
            'dashboard',
            self::$_moduleName,
            Navigation::LEFT,
            Navigation::LEFT_MAIN,
            '',
            '',
            false
        );
    }
}