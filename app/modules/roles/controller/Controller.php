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

class RolesController extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        // Check user permissions
        if (!Permissions::has('roles_view')) {
            Router::response(false, '', ABS_PATH);
        }

        // Add JS dependencies
        $files = [
            ABS_PATH . 'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css',
            ABS_PATH . 'assets/vendors/datatables.net/jquery.dataTables.js',
            ABS_PATH . 'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js',
            $this->module->uri . '/js/roles.js',
        ];

        foreach ($files as $i => $file) {
            Dependencies::add(
                $file,
                $i + 100
            );
        }

    }

    /**
     * Display user roles
     */
    public function index()
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

        $data = [

            // Page ID
            'page' => 'roles',

            // Page Title
            'page_title' => $Template->_get('roles_page_title'),

            // Page Header
            'header' => $Template->_get('roles_page_header'),

            // Breadcrumbs
            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('roles_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $roles = UserRoles::getList();

        // Load i18n variables
        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        // Push data to template engine
        $Template
            ->assign('data', $data)
            ->assign('roles', $roles)
            ->assign('content', $Template->fetch($this->module->path . '/view/index.tpl'));
    }


    public function edit(int $role_id)
    {

        $user_role_name = $this->model->getRoleName($role_id);

        $Template = Template::getInstance();

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

        $data = [

            'page' => 'roles',

            'page_title' => $Template->_get('roles_page_edit_title'),

            'header' => $Template->_get('roles_page_edit_header'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('roles_breadcrumb'),
                    'href' => ABS_PATH . 'roles',
                    'page' => 'roles',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('roles_breadcrumb_edit'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $permissions = $this->model->getAllPermissions($role_id);

        foreach ($permissions as $_module => $_permissions) {
            if ($module = Loader::getModule($_module)) {
                $Template->_load($module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'permissions');
            }
        }

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        $Template
            ->assign('data', $data)
            ->assign('user_role_id', $role_id)
            ->assign('user_role_name', $user_role_name)
            ->assign('disabled', $this->model->isDisabled($role_id))
            ->assign('editable', UserRoles::isEditable($role_id))
            ->assign('exists', $this->model->getRole($role_id))
            ->assign('permissions', $this->model->getAllPermissions($role_id))
            ->assign('content', $Template->fetch($this->module->path . '/view/edit.tpl'));
    }


    public function add()
    {

        $Template = Template::getInstance();

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

        $data = [

            'page' => 'roles',

            'page_title' => $Template->_get('roles_page_add_title'),

            'header' => $Template->_get('roles_page_add_header'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('roles_breadcrumb'),
                    'href' => ABS_PATH . 'roles',
                    'page' => 'roles',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('roles_breadcrumb_add'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $permissions = $this->model->getAllPermissions();

        foreach ($permissions as $_module => $_permissions) {
            if ($module = Loader::getModule($_module)) {
                $Template->_load($module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'permissions');
            }
        }

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        $Template
            ->assign('data', $data)
            ->assign('access', Permissions::has('roles_edit'))
            ->assign('permissions', $permissions)
            ->assign('content', $Template->fetch($this->module->path . '/view/add.tpl'));
    }


    public function save()
    {
        $Template = Template::getInstance();

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        $this->model->saveRole();
    }


    public function delete()
    {
        $Template = Template::getInstance();

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        $this->model->deleteRole();
    }
}