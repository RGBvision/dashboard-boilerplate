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

class EventLogController extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        // Check if user has permission at least to view module default page
        if (!Permission::has('event_log_view')) {
            Router::response(false, '', ABS_PATH);
        }

        // Add JS dependencies
        $files = [
            ABS_PATH . 'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css',
            ABS_PATH . 'assets/vendors/datatables.net/jquery.dataTables.js',
            ABS_PATH . 'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js',
            $this->module->uri . '/js/event_log.js',
        ];

        foreach ($files as $i => $file) {
            Dependencies::add(
                $file,
                $i + 100
            );
        }
    }

    /**
     * Display event log
     */
    public function index()
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

        $data = [

            // Page ID
            'page' => 'event_log',

            // Page Title
            'page_title' => $Template->_get('event_log_page_title'),

            // Page Header
            'header' => $Template->_get('event_log_page_header'),

            // Breadcrumbs
            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('event_log_breadcrumb'),
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
            ->assign('content', $Template->fetch($this->module->path . '/view/index.tpl'));

    }

    /**
     * Get log data
     */
    public function get()
    {

        $_sortable_index = Arrays::search(Log::SORTABLE, Request::post('columns.' . (int)Request::post('order.0.column') . '.data'), 0);

        $event_log = Log::get(Log::SORTABLE[$_sortable_index], Request::post('order.0.dir'), (int)Request::post('length'), (int)Request::post('start'), Request::post('search.value'));

        $res = [
            'draw' => (int)Request::post('draw'),
            'recordsTotal' => Log::total(),
            'recordsFiltered' => Log::total(Request::post('search.value')),
            'search_value' => Request::post('search.value'),
            'data' => $event_log,
        ];

        Json::output($res, true);

    }

}