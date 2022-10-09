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
 * @version    4.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class DashboardController extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        // Check if user has permission at least to view module default page
        if (!Permission::has('dashboard_view')) {
            // Redirect to login page because dashboard is default module for authorized users
            // So we assume user is not logged in if user has no access to this module
            Router::response(false, '', ABS_PATH . 'login');
        }

        // Add dependencies
        $files = [
            ABS_PATH . 'assets/vendors/apexcharts/apexcharts.min.js',
            ABS_PATH . 'assets/vendors/progressbar.js/progressbar.min.js',
            $this->module->uri . '/js/dashboard.js',
        ];

        foreach ($files as $i => $file) {
            Dependencies::add(
                $file,
                $i + 100
            );
        }
    }

    /**
     * Default page
     */
    public function index()
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

        $data = [

            // Page ID
            'page' => 'dashboard',

            // Page Title
            'page_title' => $Template->_get('dashboard_page_title'),

            // Page Header
            'header' => $Template->_get('dashboard_page_header'),

            // Breadcrumbs
            'breadcrumbs' => [
                [
                    'text' => $Template->_get('dashboard_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        // Load i18n variables
        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        // Push data to template engine
        $Template
            ->assign('data', $data)
            ->assign('visits', $this->model->getVisits())
            ->assign('storage_size', $this->model->getStorageSize())
            ->assign('storage_usage', $this->model->getStorageUsage())
            ->assign('content', $Template->fetch($this->module->path . '/view/index.tpl'));
    }

    /**
     * Backup database
     */
    public function backup_db()
    {
        $status = false;
        if (Permission::has('dashboard_backup_db')) {
            $status = DB::backup();
            Response::setStatus($status ? 200 : 503);
        }
        Json::output(['success' => $status], true);
    }

    /**
     * Clear cache: delete SMARTY cache files
     */
    public function clear_cache()
    {
        if (Permission::has('dashboard_clear_cache')) {
            Dir::delete_contents(DASHBOARD_DIR . TEMP_DIR . '/cache/smarty');
            Json::output(['success' => true], true);
        }
        Json::output(['success' => false], true);
    }


    /**
     * Generate new module
     */
    public function generate()
    {

        $success = false;

        if (
            (UID === 1) &&
            ($name = preg_replace('/[^a-z ]/i', '', Request::post('module'))) &&
            ($dir_name = preg_replace('/\s+/', '', strtolower($name))) &&
            (!Dir::exists(DASHBOARD_DIR . MODULES_DIR . DS . $dir_name))
        ) {
            $this->model->copy_template(DASHBOARD_DIR . "/tmp/module_template", DASHBOARD_DIR . MODULES_DIR . DS . $dir_name, $name);
            $success = Dir::exists(DASHBOARD_DIR . MODULES_DIR . DS . $dir_name);
        }

        Router::response($success, '', Request::referrer());

    }

}