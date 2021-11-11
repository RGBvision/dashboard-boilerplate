<?php


class ControllerProfile extends Controller
{

    public static string $route_id;
    protected static Model $model;


    
    public function __construct()
    {
        self::$route_id = Router::getId();
        self::$model = Router::model();

        $files = [
            ABS_PATH . 'assets/vendors/select2/select2.min.css',
            ABS_PATH . 'assets/vendors/select2/select2.min.js',
            ABS_PATH . 'assets/vendors/sweetalert2/sweetalert2.min.css',
            ABS_PATH . 'assets/vendors/sweetalert2/sweetalert2.min.js',
            ABS_PATH . 'assets/vendors/libphonenumber/libphonenumber.js',
            ABS_PATH . 'assets/vendors/jquery-validation/jquery.validate.min.js',
            ABS_PATH . 'assets/vendors/cropperjs/cropper.min.css',
            ABS_PATH . 'assets/vendors/cropperjs/cropper.min.js',
            ABS_PATH . 'assets/vendors/tinymce/tinymce.min.js',
            ABS_PATH . 'assets/js/profile.js',
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

        if (!Permission::check('profile_view')) {
            Request::redirect(ABS_PATH);
            Response::shutDown();
        }

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/profile/i18n/' . Session::getvar('current_language') . '.ini', 'main');
        $Template->_load(DASHBOARD_DIR . '/app/modules/profile/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        $data = [

            'page' => 'profile',

            'page_title' => $Template->_get('profile_page_title'),

            'header' => $Template->_get('profile_page_header'),

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

        $user = self::$model::getUser(UID);

        $Template
            ->assign('data', $data)
            ->assign('_is_ajax', Request::isAjax())
            ->assign('countries', Valid::getAllCountries())
            ->assign('user', $user)
            ->assign('cropper_tpl', $Template->fetch(DASHBOARD_DIR . '/app/modules/profile/view/cropper.tpl'))
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/profile/view/index.tpl'));
    }

    public static function save_avatar()
    {
        if (
            UID && ($photo = Request::post('new_avatar'))
        ) {
            User::saveAvatar(UID, $photo);
        }

        Request::isAjax() ? Response::shutDown() : Request::redirect(Request::referrer());

    }

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