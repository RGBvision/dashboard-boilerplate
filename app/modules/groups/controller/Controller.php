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

class GroupsController extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        // Check user permissions
        if (!Permission::check('groups_view')) {
            Router::response(false, '', ABS_PATH);
        }

        // Add JS dependencies
        $files = [
            ABS_PATH . 'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css',
            ABS_PATH . 'assets/vendors/datatables.net/jquery.dataTables.js',
            ABS_PATH . 'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js',
            ABS_PATH . 'app/modules/groups/js/groups.js',
        ];

        foreach ($files as $i => $file) {
            Dependencies::add(
                $file,
                $i + 100
            );
        }

    }

    /**
     * Display user groups
     */
    public static function index()
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $data = [

            // Page ID
            'page' => 'groups',

            // Page Title
            'page_title' => $Template->_get('groups_page_title'),

            // Page Header
            'header' => $Template->_get('groups_page_header'),

            // Breadcrumbs
            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('groups_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $groups = UserGroup::getList();

        if (UGROUP !== UserGroup::SUPERADMIN) {
            foreach ($groups as $k => $group) {
                if ((int)$group['user_group_id'] === UserGroup::SUPERADMIN) {
                    Arrays::delete($groups, (string)$k);
                }
            }
        }

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        // Push data to template engine
        $Template
            ->assign('data', $data)
            ->assign('groups', $groups)
            ->assign('access', Permission::perm('groups_edit'))
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/groups/view/index.tpl'));
    }


    public static function edit()
    {
        $user_group_id = (int)Request::get('user_group_id');
        $user_group_name = self::$model->getGroupName($user_group_id);

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        $data = [

            'page' => 'groups',

            'page_title' => $Template->_get('groups_page_edit_title'),

            'header' => $Template->_get('groups_page_edit_header'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('groups_breadcrumb_parent'),
                    'href' => ABS_PATH . 'groups',
                    'page' => 'groups',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('groups_breadcrumb_edit'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $editable = self::$model->isEditable($user_group_id);
        $disabled = self::$model->getDisabled($user_group_id);
        $exists = self::$model->getGroup($user_group_id);

        $Template
            ->assign('data', $data)
            ->assign('user_group_id', $user_group_id)
            ->assign('user_group_name', $user_group_name)
            ->assign('disabled', $disabled)
            ->assign('editable', $editable)
            ->assign('exists', $exists)
            ->assign('permissions', self::$model->getAllPermissions($user_group_id))
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/groups/view/edit.tpl'));
    }


    public static function add()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        $data = [

            'page' => 'groups',

            'page_title' => $Template->_get('groups_page_add_title'),

            'header' => $Template->_get('groups_page_add_header'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('groups_breadcrumb_parent'),
                    'href' => ABS_PATH . 'groups',
                    'page' => 'groups',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('groups_breadcrumb_add'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $Template
            ->assign('data', $data)
            ->assign('access', Permission::perm('groups_edit'))
            ->assign('permissions', self::$model->getAllPermissions())
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/groups/view/add.tpl'));
    }


    public static function save()
    {
        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->saveGroup();
    }


    public static function delete()
    {
        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->deleteGroup();
    }
}