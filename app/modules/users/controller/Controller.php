<?php

class ControllerUsers extends Controller
{

    public static string $route_id;
    protected static Model $model;

    public function __construct()
    {

        if (!Permission::check('users_view')) {
            Request::redirect(ABS_PATH);
            Response::shutDown();
        }

        self::$route_id = Router::getId();
        self::$model = Router::model();

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

    public static function index()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/users/i18n/' . Session::getvar('current_language') . '.ini', 'main');
        $Template->_load(DASHBOARD_DIR . '/app/modules/users/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        $data = [

            'page' => 'users',

            'page_title' => $Template->_get('users_page_title'),

            'header' => $Template->_get('users_page_header'),

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

        $Template
            ->assign('data', $data)
            ->assign('groups', UserGroup::getList())
            ->assign('countries', Valid::getAllCountries())
            ->assign('can_add_user', self::$model::canAddUser())
            ->assign('can_edit_user', Permission::check('users_edit'))
            ->assign('add_user_tpl', $Template->fetch(DASHBOARD_DIR . '/app/modules/users/view/add.tpl'))
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/users/view/index.tpl'));
    }

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

    public static function view(int $user_id)
    {

        if (!$user_id) {
            Request::redirect(Request::referrer());
            Response::shutDown();
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

    public static function edit(int $user_id)
    {

        if (!$user_id || !Permission::check('users_edit')) {
            Request::redirect(Request::referrer());
            Response::shutDown();
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

    public static function add()
    {

        Loader::addDirectory(DASHBOARD_DIR . '/libraries/PhoneLib/');
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        try {

            if (
                Permission::perm('users_edit') &&
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

        Request::redirect(ABS_PATH . 'users');
    }

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

        Request::redirect(ABS_PATH . 'users');
    }

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
