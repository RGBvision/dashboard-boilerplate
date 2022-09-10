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

class ControllerProfile extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

        // Check if user has permission
        if (!Permission::check('profile_view')) {
            Router::response(false, '', ABS_PATH);
        }

        // Add JS dependencies
        $files = [
            ABS_PATH . 'assets/vendors/libphonenumber/libphonenumber-max.js',
            ABS_PATH . 'assets/vendors/cropperjs/cropper.min.css',
            ABS_PATH . 'assets/vendors/cropperjs/cropper.min.js',
            ABS_PATH . 'assets/vendors/tinymce/tinymce.min.js',
            ABS_PATH . 'app/modules/profile/js/profile.js',
        ];

        foreach ($files as $i => $file) {
            Dependencies::add(
                $file,
                $i + 100
            );
        }
    }

    /**
     * Profile page
     */
    public static function index()
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/profile/i18n/' . Session::getvar('current_language') . '.ini', 'main');
        $Template->_load(DASHBOARD_DIR . '/app/modules/profile/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        $data = [

            // Page ID
            'page' => 'profile',

            // Page Title
            'page_title' => $Template->_get('profile_page_title'),

            // Page Header
            'header' => $Template->_get('profile_page_header'),

            // Breadcrumbs
            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => '/',
                    'page' => 'dashboard',
                    'push' => 'true',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('profile_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'push' => '',
                    'active' => true,
                ],
            ],
        ];

        // Get user data
        $user = self::$model->getUser(UID);

        // Push data to template engine
        $Template
            ->assign('data', $data)
            ->assign('countries', Valid::getAllCountries())
            ->assign('user', $user)
            ->assign('cropper_tpl', $Template->fetch(DASHBOARD_DIR . '/app/modules/profile/view/cropper.tpl'))
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/profile/view/index.tpl'));
    }

    /**
     * Save user avatar
     * ToDo: Crop and resize
     */
    public static function save_avatar()
    {

        $success = false;

        if (
            UID && ($photo = Request::post('new_avatar'))
        ) {
            $success = User::saveAvatar(UID, $photo);
        }

        Router::response($success, '', Request::referrer());

    }

    /**
     * Set user settings
     *
     * @param string $key
     * @param string $val
     */
    public static function settings_set(string $key, string $val)
    {
        if (
            ($_key = Secure::sanitize($key)) &&
            ($_val = Secure::sanitize($val))
        ) {
            if (UID > 0) {
                Settings::set('user_settings', $_key, $_val);
                Settings::saveUserSettings(UID);
            } else {
                if (in_array($_key, ['user_lang', 'current_language'])) {
                    Session::setvar('current_language', $_val);
                }
            }
        }

        Request::redirect(Request::referrer());

    }
}