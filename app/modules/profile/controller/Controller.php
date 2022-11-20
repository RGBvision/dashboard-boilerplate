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

class ProfileController extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

        // Check if user has permission
        if (!Permissions::has('profile_view')) {
            Router::response(false, '', ABS_PATH);
        }

        // Add JS dependencies
        $files = [
            ABS_PATH . 'assets/vendors/libphonenumber/libphonenumber-max.js',
            ABS_PATH . 'assets/vendors/cropperjs/cropper.min.css',
            ABS_PATH . 'assets/vendors/cropperjs/cropper.min.js',
            ABS_PATH . 'assets/vendors/tinymce/tinymce.min.js',
            $this->module->uri . '/js/profile.js',
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
    public function index()
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

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
        $user = $this->model->getUser(USERID);

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        // Push data to template engine
        $Template
            ->assign('data', $data)
            ->assign('countries', Valid::getAllCountries())
            ->assign('user', $user)
            ->assign('cropper_tpl', $Template->fetch($this->module->path . '/view/cropper.tpl'))
            ->assign('content', $Template->fetch($this->module->path . '/view/index.tpl'));
    }

    /**
     * Save user avatar
     * ToDo: Crop and resize
     */
    public function save_avatar()
    {

        $success = false;

        if (
            USERID && ($photo = Request::post('new_avatar'))
        ) {
            $success = User::saveAvatar(USERID, $photo);
        }

        Router::response($success, '', Request::referrer());

    }

    /**
     * Set user settings
     *
     * @param string $key
     * @param string $val
     */
    public function settings_set(string $key, string $val)
    {
        if (
            ($_key = Secure::sanitize($key)) &&
            ($_val = Secure::sanitize($val))
        ) {
            if (USERID > 0) {
                Settings::set('user_settings', $_key, $_val);
                Settings::saveUserSettings(USERID);
            } else {
                if (in_array($_key, ['user_lang', 'current_language'])) {
                    Session::setvar('current_language', $_val);
                }
            }
        }

        Request::redirect(Request::referrer());

    }
}