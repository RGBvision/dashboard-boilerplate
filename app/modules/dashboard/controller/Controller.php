<?php

/**
 * This file is part of the dashboard.rgbvision.net package.
 *
 * (c) Alex Graham <contact@rgbvision.net>
 *
 * @package    dashboard.rgbvision.net
 * @author     Alex Graham <contact@rgbvision.net>
 * @copyright  Copyright 2017-2021, Alex Graham
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    3.2
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ControllerDashboard extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        // Check if user has permission at least to view module default page
        if (!Permission::check('dashboard_view')) {
            // Redirect to login page because dashboard is default module for authorized users
            // So we assume that user is not logged in if user has no access to this module
            Router::response(false, '', ABS_PATH . 'login');
        }

        // Add JS dependencies
        $files = [
            ABS_PATH . 'assets/vendors/apexcharts/apexcharts.min.js',
            ABS_PATH . 'assets/vendors/progressbar.js/progressbar.min.js',
            ABS_PATH . 'assets/js/dashboard.js',
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
    public static function index()
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/dashboard/i18n/' . Session::getvar('current_language') . '.ini', 'main');

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
        $Template->_load(DASHBOARD_DIR . '/app/modules/dashboard/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        // Push data to template engine
        $Template
            ->assign('data', $data)
            ->assign('visits', self::$model->getVisits())
            ->assign('storage_size', self::$model->getStorageSize())
            ->assign('storage_usage', self::$model->getStorageUsage())
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/dashboard/view/index.tpl'));
    }

    /**
     * Backup database
     */
    public static function backup_db()
    {
        $status = false;
        if (Permission::check('dashboard_backup_db')) {
            $status = DB::backup();
            Response::setStatus($status ? 200 : 503);
        }
        Json::show(['success' => $status], true);
    }

    /**
     * Clear cache: delete SMARTY cache files
     */
    public static function clear_cache()
    {
        if (Permission::check('dashboard_clear_cache')) {
            Dir::delete_contents(DASHBOARD_DIR . TEMP_DIR . '/cache/smarty');
            Json::show(['success' => true], true);
        }
        Json::show(['success' => false], true);
    }


    /**
     * Generate new module
     */
    public static function generate()
    {

        $success = false;

        if (
            (UID === 1) &&
            ($name = preg_replace('/[^a-z ]/i', '', Request::post('module'))) &&
            ($dir_name = preg_replace('/\s+/', '', strtolower($name))) &&
            (!Dir::exists(DASHBOARD_DIR . "/app/modules/$dir_name"))
        ) {
            self::$model->copy_template(DASHBOARD_DIR . "/tmp/module_template", DASHBOARD_DIR . "/app/modules/$dir_name", $name);
            $success = Dir::exists(DASHBOARD_DIR . "/app/modules/$dir_name");
        }

        Router::response($success, '', Request::referrer());

    }

}