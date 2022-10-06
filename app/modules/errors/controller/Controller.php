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
 * @version    3.1
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ErrorsController extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display error page
     *
     * @param string $header
     * @param string $message
     */
    private static function displayError(string $header, string $message)
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $data = [

            'page' => 'errors',

            'page_title' => $Template->_get('error_page_title'),

            'header' => $Template->_get('error_page_title'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'home',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('error_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $Template
            ->assign('data', $data)
            ->assign('_header', $header)
            ->assign('_message', $message)
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/errors/view/index.tpl'));

    }

    /**
     * 404 error (default)
     */
    public static function index()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $header = $Template->_get('header_404');
        $message = $Template->_get('message_404');

        self::displayError($header, $message);

    }

    /**
     * Module not found
     */
    public static function module()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $module = Request::get('module');

        $header = $Template->_get('header_module');
        $message = sprintf($Template->_get('message_module'), $module);

        self::displayError($header, $message);

    }

    /**
     * Model not found
     */
    public static function model()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $model = Request::get('model');

        $header = $Template->_get('header_model');
        $message = sprintf($Template->_get('message_model'), $model);

        self::displayError($header, $message);

    }

    /**
     * Controller not found
     */
    public static function controller()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $controller = Request::get('controller');

        $header = $Template->_get('header_controller');
        $message = sprintf($Template->_get('message_controller'), $controller);

        self::displayError($header, $message);

    }

    /**
     * Method not found
     */
    public static function method()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $request = Request::get('method');

        $parts = [];

        if (isset($request))
            $parts = explode('/', preg_replace('/[^a-zA-Z0-9_\/]/', '', trim((string)$request, '/')));

        $header = $Template->_get('header_method');
        $message = sprintf($Template->_get('message_method'), $parts[0], $parts[1]);

        self::displayError($header, $message);

    }

    /**
     * Access denied
     */
    public static function denied()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $header = $Template->_get('header_denied');
        $message = $Template->_get('message_denied');

        self::displayError($header, $message);

    }
}