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
 * @version    3.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ControllerUsers extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

        // Check if user has permission
        if (!Permission::check('users_view')) {
            Router::response(false, '', ABS_PATH);
        }

        // Add JS/CSS dependencies
        $files = [
            ABS_PATH . 'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css',
            ABS_PATH . 'assets/vendors/datatables.net/jquery.dataTables.js',
            ABS_PATH . 'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js',
            ABS_PATH . 'assets/vendors/libphonenumber/libphonenumber-max.js',
            ABS_PATH . 'assets/vendors/cropperjs/cropper.min.css',
            ABS_PATH . 'assets/vendors/cropperjs/cropper.min.js',
            ABS_PATH . 'assets/js/users.js',
        ];

        foreach ($files as $i => $file) {
            Dependencies::add(
                $file,
                $i + 100
            );
        }

    }

    /**
     * Users list page
     */
    public static function index()
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/users/i18n/' . Session::getvar('current_language') . '.ini', 'main');
        $Template->_load(DASHBOARD_DIR . '/app/modules/users/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        $data = [

            // Page ID
            'page' => 'users',

            // Page Title
            'page_title' => $Template->_get('users_page_title'),

            // Page Header
            'header' => $Template->_get('users_page_header'),

            // Breadcrumbs
            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('users_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        // Push data to template engine
        $Template
            ->assign('data', $data)
            ->assign('groups', UserGroup::getList())
            ->assign('countries', Valid::getAllCountries())
            ->assign('can_add_user', self::$model::canAddUser())
            ->assign('can_edit_user', Permission::check('users_edit'))
            ->assign('add_user_tpl', $Template->fetch(DASHBOARD_DIR . '/app/modules/users/view/add.tpl'))
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/users/view/index.tpl'));
    }

    /**
     * Get users list data
     */
    public static function get()
    {

        $_order_column_id = (int)Request::post('order.0.column');

        $users = User::getList(Request::post("columns.$_order_column_id.data"), Request::post('order.0.dir'), (int)Request::post('length'), (int)Request::post('start'), Request::post('search.value'));

        $res = [
            'draw' => (int)Request::post('draw'),
            'recordsTotal' => User::total(),
            'recordsFiltered' => User::total(Request::post('search.value')),
            'search_value' => Request::post('search.value'),
            'data' => $users,
        ];

        Json::show($res, true);

    }

    /**
     * View user profile page
     *
     * @param int $user_id
     * @throws \libphonenumber\NumberParseException
     */
    public static function view(int $user_id)
    {

        if (!$user_id) {
            Router::response(false, '', Request::referrer());
        }

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/' . self::$route_id . '/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        $data = [

            'page' => 'users',

            'page_title' => $Template->_get('users_page_edit_title'),

            'header' => $Template->_get('users_page_edit_header'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => true,
                ],
                [
                    'text' => $Template->_get('users_breadcrumb_parent'),
                    'href' => ABS_PATH . 'users',
                    'page' => 'users',
                    'active' => true,
                ],
                [
                    'text' => $Template->_get('users_breadcrumb_edit'),
                    'href' => '',
                    'page' => '',
                    'active' => false,
                ],
            ],
        ];

        $user = self::$model::getUser($user_id);

        Loader::addDirectory(DASHBOARD_DIR . '/libraries/PhoneLib/');
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        $phone = $phoneUtil->parse($user['phone'], $user['country_code']);

        $Template
            ->assign('data', $data)
            ->assign('formatted_phone', $phoneUtil->format($phone, \libphonenumber\PhoneNumberFormat::INTERNATIONAL))
            ->assign('can_edit_user', Permission::check('users_edit'))
            ->assign('user', $user)
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/users/view/view.tpl'));
    }

    /**
     * Edit user profile page
     *
     * @param int $user_id
     */
    public static function edit(int $user_id)
    {

        if (!$user_id || !Permission::check('users_edit')) {
            Router::response(false, '', Request::referrer());
        }

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/' . self::$route_id . '/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        $data = [

            'page' => 'users',

            'page_title' => $Template->_get('users_page_edit_title'),

            'header' => $Template->_get('users_page_edit_header'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => true,
                ],
                [
                    'text' => $Template->_get('users_breadcrumb_parent'),
                    'href' => ABS_PATH . 'users',
                    'page' => 'users',
                    'active' => true,
                ],
                [
                    'text' => $Template->_get('users_breadcrumb_edit'),
                    'href' => '',
                    'page' => '',
                    'active' => false,
                ],
            ],
        ];

        $groups = UserGroup::getList();

        if (UGROUP !== UserGroup::SUPERADMIN) {
            $path = explode('.', Arrays::pathByKeyValue($groups, 'user_group_id', UserGroup::SUPERADMIN));
            Arrays::delete($groups, $path[0]);
        }

        $Template
            ->assign('data', $data)
            ->assign('user', self::$model::getUser($user_id))
            ->assign('countries', Valid::getAllCountries())
            ->assign('groups', $groups)
            ->assign('action', 'save')
            ->assign('cropper_tpl', $Template->fetch(DASHBOARD_DIR . '/app/modules/users/view/cropper.tpl'))
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/users/view/edit.tpl'));
    }

    /**
     * Add user
     */
    public static function add()
    {

        Loader::addDirectory(DASHBOARD_DIR . '/libraries/PhoneLib/');
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        try {

            if (
                Permission::check('users_add') &&
                ($firstname = Request::post('firstname')) &&
                ($lastname = Request::post('lastname')) &&
                ($code = Request::post('code')) &&
                ($_phone = Request::post('phone')) &&
                ($phone = $phoneUtil->parse($_phone, $code)) &&
                ($phoneUtil->isValidNumber($phone)) &&
                ($email = Valid::normalizeEmail(Request::post('email'))) &&
                ($pass = Request::post('password')) &&
                ($group = (int)Request::post('group'))
            ) {
                User::saveUser(null, $firstname, $lastname, mb_strtoupper($code), $phone->getNationalNumber(), $email, $pass, $group, null, ((int)Request::post('send_email') === 1));
            }

        } catch (\libphonenumber\NumberParseException $e) {
        }

        Router::response(true, '', ABS_PATH . 'users');

    }

    /**
     * Save user data
     */
    public static function save()
    {

        Loader::addDirectory(DASHBOARD_DIR . '/libraries/PhoneLib/');
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        try {

            if (
                Permission::perm('users_edit') &&
                ($id = (int)Request::post('user_id')) &&
                ($firstname = trim(Request::post('firstname'))) &&
                ($lastname = trim(Request::post('lastname'))) &&
                ($code = Request::post('code')) &&
                ($_phone = Request::post('phone')) &&
                ($phone = $phoneUtil->parse($_phone, $code)) &&
                ($phoneUtil->isValidNumber($phone)) &&
                ($email = Valid::normalizeEmail(Request::post('email'))) &&
                ($group = (int)Request::post('group'))
            ) {

                $photo = Request::post('new_avatar') ?: null;
                $pass = Request::post('password') ?: null;

                User::saveUser($id, $firstname, $lastname, mb_strtoupper($code), $phone->getNationalNumber(), $email, $pass, $group, $photo, ((int)Request::post('send_email') === 1));
            }

        } catch (\libphonenumber\NumberParseException $e) {
        }

        Router::response(true, '', ABS_PATH . 'users');
    }

    /**
     * Save user avatar
     */
    public static function save_avatar()
    {
        if (
            (Permission::perm('users_edit')) &&
            ($id = (int)Request::post('user_id')) &&
            ($photo = Request::post('new_avatar'))
        ) {
            User::saveAvatar($id, $photo);
        }

        Request::isAjax() ? Response::shutDown() : Request::redirect(Request::referrer());

    }

    /**
     * Block user
     */
    public static function block()
    {
        if (
            (Permission::perm('users_edit')) &&
            ($id = Request::get('user_id'))
        ) {
            User::block($id);
        }
        Request::redirect(Request::referrer() ?? ABS_PATH . 'users');
    }

    /**
     * Unblock user
     */
    public static function unblock()
    {
        if (
            (Permission::perm('users_edit')) &&
            ($id = Request::get('user_id'))
        ) {
            User::unblock($id);
        }
        Request::redirect(Request::referrer() ?? ABS_PATH . 'users');
    }

    /**
     * Delete user
     */
    public static function delete()
    {
        if (
            (Permission::perm('users_delete')) &&
            ($id = Request::get('user_id'))
        ) {
            User::delete($id);
        }
        Request::redirect(Request::referrer() ?? ABS_PATH . 'users');
    }

    /**
     * Restore deleted user
     */
    public static function restore()
    {
        if (
            (Permission::perm('users_delete')) &&
            ($id = Request::get('user_id'))
        ) {
            User::restore($id);
        }
        Request::redirect(Request::referrer() ?? ABS_PATH . 'users');
    }

    /**
     * Check if phone number used by another user
     */
    public static function check_phone()
    {

        $res = false;

        if (($phone = Request::post('phone')) && ($code = Request::post('code'))) {

            Loader::addDirectory(DASHBOARD_DIR . '/libraries/PhoneLib/');
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

            try {

                $_phone = $phoneUtil->parse($phone, $code);

                if ($phoneUtil->isValidNumber($_phone)) {
                    $res = !User::isPhoneUsed($_phone->getNationalNumber(), mb_strtoupper($code), (int)Request::post('user_id'));
                }

            } catch (\libphonenumber\NumberParseException $e) {

            }
        }

        echo Html::output($res ? 'true' : 'false');
        Response::shutDown();
    }

    /**
     * Check if email used by another user
     */
    public static function check_email()
    {

        $res = false;

        if (($_email = Request::post('email')) && ($email = Valid::normalizeEmail($_email))) {
            $res = !User::isEmailUsed($email, (int)Request::post('user_id'));
        }

        echo Html::output($res ? 'true' : 'false');
        Response::shutDown();
    }

}
