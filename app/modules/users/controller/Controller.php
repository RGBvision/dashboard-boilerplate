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

class UsersController extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

        // Check if user has permission
        if (!Permissions::has('users_view')) {
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
            $this->module->uri . '/js/users.js',
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
    public function index()
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

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

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        // Push data to template engine
        $Template
            ->assign('data', $data)
            ->assign('roles', UserRoles::getList())
            ->assign('countries', Valid::getAllCountries())
            ->assign('add_user_tpl', $Template->fetch($this->module->path . '/view/add.tpl'))
            ->assign('content', $Template->fetch($this->module->path . '/view/index.tpl'));
    }

    /**
     * Get users list data
     */
    public function get()
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

        define('NO_CACHE', true);

        Json::output($res, true);

    }

    /**
     * View user profile page
     *
     * @param int $user_id
     * @throws \libphonenumber\NumberParseException
     */
    public function view(int $user_id)
    {

        if (!$user_id) {
            Router::response(false, '', Request::referrer());
        }

        $Template = Template::getInstance();

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

        $data = [

            'page' => 'users',

            'page_title' => $Template->_get('users_page_view_title'),

            'header' => $Template->_get('users_page_view_header'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => true,
                ],
                [
                    'text' => $Template->_get('users_breadcrumb'),
                    'href' => ABS_PATH . 'users',
                    'page' => 'users',
                    'active' => true,
                ],
                [
                    'text' => $Template->_get('users_breadcrumb_view'),
                    'href' => '',
                    'page' => '',
                    'active' => false,
                ],
            ],
        ];

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        $user = $this->model->getUser($user_id);

        Loader::addDirectory(DASHBOARD_DIR . '/libraries/PhoneLib/');
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        $phone = $phoneUtil->parse($user['phone'], $user['country_code']);

        $Template
            ->assign('data', $data)
            ->assign('formatted_phone', $phoneUtil->format($phone, \libphonenumber\PhoneNumberFormat::INTERNATIONAL))
            ->assign('user', $user)
            ->assign('content', $Template->fetch($this->module->path . '/view/view.tpl'));
    }

    /**
     * Edit user profile page
     *
     * @param int $user_id
     */
    public function edit(int $user_id)
    {

        if (!$user_id || !Permissions::has('users_edit')) {
            Router::response(false, '', Request::referrer());
        }

        $Template = Template::getInstance();

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

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
                    'text' => $Template->_get('users_breadcrumb'),
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

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        $roles = UserRoles::getList();

        if (USERROLE !== UserRoles::SUPERADMIN) {
            $path = explode('.', Arrays::pathByKeyValue($roles, 'user_role_id', UserRoles::SUPERADMIN));
            Arrays::delete($roles, $path[0]);
        }

        $Template
            ->assign('data', $data)
            ->assign('user', $this->model->getUser($user_id))
            ->assign('countries', Valid::getAllCountries())
            ->assign('roles', $roles)
            ->assign('action', 'save')
            ->assign('cropper_tpl', $Template->fetch($this->module->path . '/view/cropper.tpl'))
            ->assign('content', $Template->fetch($this->module->path . '/view/edit.tpl'));
    }

    /**
     * Add user
     */
    public function add()
    {

        Loader::addDirectory(DASHBOARD_DIR . '/libraries/PhoneLib/');
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        try {

            if (
                Permissions::has('users_add') &&
                ($firstname = Request::post('firstname')) &&
                ($lastname = Request::post('lastname')) &&
                ($code = Request::post('code')) &&
                ($_phone = Request::post('phone')) &&
                ($phone = $phoneUtil->parse($_phone, $code)) &&
                ($phoneUtil->isValidNumber($phone)) &&
                ($email = Valid::normalizeEmail(Request::post('email'))) &&
                ($pass = Request::post('password')) &&
                ($role = (int)Request::post('role'))
            ) {
                User::saveUser(null, $firstname, $lastname, mb_strtoupper($code), $phone->getNationalNumber(), $email, $pass, $role, null, ((int)Request::post('send_email') === 1));
            }

        } catch (\libphonenumber\NumberParseException $e) {
        }

        Router::response(true, '', ABS_PATH . 'users');

    }

    /**
     * Save user data
     */
    public function save()
    {

        Loader::addDirectory(DASHBOARD_DIR . '/libraries/PhoneLib/');
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        try {

            if (
                Permissions::has('users_edit') &&
                ($id = (int)Request::post('user_id')) &&
                ($firstname = trim(Request::post('firstname'))) &&
                ($lastname = trim(Request::post('lastname'))) &&
                ($code = Request::post('code')) &&
                ($_phone = Request::post('phone')) &&
                ($phone = $phoneUtil->parse($_phone, $code)) &&
                ($phoneUtil->isValidNumber($phone)) &&
                ($email = Valid::normalizeEmail(Request::post('email'))) &&
                ($role = (int)Request::post('role'))
            ) {

                $photo = Request::post('new_avatar') ?: null;
                $pass = Request::post('password') ?: null;

                User::saveUser($id, $firstname, $lastname, mb_strtoupper($code), $phone->getNationalNumber(), $email, $pass, $role, $photo, ((int)Request::post('send_email') === 1));
            }

        } catch (\libphonenumber\NumberParseException $e) {
        }

        Router::response(true, '', ABS_PATH . 'users');
    }

    /**
     * Save user avatar
     */
    public function save_avatar()
    {
        if (
            (Permissions::has('users_edit')) &&
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
    public function block()
    {
        if (
            (Permissions::has('users_edit')) &&
            ($id = Request::get('user_id'))
        ) {
            User::block($id);
        }
        Request::redirect(Request::referrer() ?? ABS_PATH . 'users');
    }

    /**
     * Unblock user
     */
    public function unblock()
    {
        if (
            (Permissions::has('users_edit')) &&
            ($id = Request::get('user_id'))
        ) {
            User::unblock($id);
        }
        Request::redirect(Request::referrer() ?? ABS_PATH . 'users');
    }

    /**
     * Delete user
     */
    public function delete()
    {
        if (
            (Permissions::has('users_delete')) &&
            ($id = Request::get('user_id'))
        ) {
            User::delete($id);
        }
        Request::redirect(Request::referrer() ?? ABS_PATH . 'users');
    }

    /**
     * Restore deleted user
     */
    public function restore()
    {
        if (
            (Permissions::has('users_delete')) &&
            ($id = Request::get('user_id'))
        ) {
            User::restore($id);
        }
        Request::redirect(Request::referrer() ?? ABS_PATH . 'users');
    }

    /**
     * Check if phone number used by another user
     */
    public function check_phone()
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
    public function check_email()
    {

        $res = false;

        if (($_email = Request::post('email')) && ($email = Valid::normalizeEmail($_email))) {
            $res = !User::isEmailUsed($email, (int)Request::post('user_id'));
        }

        echo Html::output($res ? 'true' : 'false');
        Response::shutDown();
    }

}
