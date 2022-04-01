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
 * @version    2.1
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ControllerLogin extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        // Add JS dependencies
        Dependencies::add(ABS_PATH . 'assets/js/login.js', 100);

    }

    /**
     * Displays Login form
     *
     * @param string|null $error error message to display
     */
    private static function displayLoginForm(?string $message = null, ?string $error = null): void
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/login/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        // Page information
        $data = [
            // Page ID
            'page' => 'login',
            // Page Title
            'page_title' => $Template->_get('login_page_title'),
        ];

        if (isAjax()) {
            Router::response(!$error, $error ?? $message, Request::referrer());
        }

        // Push data to template engine
        $Template
            ->assign('data', $data)
            ->assign('message', $message)
            ->assign('error', $error)
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/login/view/index.tpl'));
    }

    /**
     * Login page (default route)
     */
    public static function index(): void
    {

        if (Permission::check('dashboard_view')) {
            Router::response(true, '', ABS_PATH . 'dashboard');
        }

        self::displayLoginForm();
    }

    /**
     * User authentication
     */
    public static function auth(): void
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/login/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $error_message = $Template->_get('wrong_pass');

        if (($user_email = Request::post('email')) && ($user_password = Request::post('password'))) {

            $keep_in = Request::post('keep_in') && ((int)Request::post('keep_in') === 1);

            switch (Auth::userLogin($user_email, $user_password, LOGIN_USER_IP, $keep_in, 3)) {
                case Auth::LOGIN_SUCCESS:
                    $redirect_link = Session::getvar('redirect_link') ?: ABS_PATH;
                    Session::delvar('redirect_link');
                    Router::response(false, '', $redirect_link);
                    break;
                case Auth::WRONG_PASS:
                    $error_message = $Template->_get('wrong_pass');
                    break;
                case Auth::USER_INACTIVE:
                    $error_message = $Template->_get('user_inactive');
                    break;
            }

        }

        self::displayLoginForm(null, $error_message);

    }

    /**
     * User logout
     */
    public static function logout(): void
    {
        Auth::userLogout();
        Router::response(true, '', ABS_PATH);
    }

    /**
     * Send e-mail with password reset link.
     */
    public static function reset_request(): void
    {

        $Template = Template::getInstance();
        $Template->_load(DASHBOARD_DIR . '/app/modules/login/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        if (
            ($email = normalizeEmail(Request::post('email'))) &&
            (DB::exists("SELECT COUNT(email) FROM users WHERE email = ?", $email)) &&
            ($user = DB::row("SELECT * FROM users WHERE email = ? LIMIT 1", $email))
        ) {

            self::$model->preparePassChange((int)$user['user_id'], $email);

            self::displayLoginForm($Template->_get('login_reset_mail_sent'));

        }

        self::displayLoginForm(null, $Template->_get('wrong_email'));

    }


    /**
     * Password change form
     */
    public static function change(string $hash = ''): void
    {

        sleep(3);

        if (
            ($hash)
            && ($user = DB::row("SELECT * FROM users WHERE hash = ?", Secure::sanitize($hash)))
            && (strtotime($user['hash_expire']) > time())
            && ($hash === (md5($user['user_id'] . $user['email']) . md5($user['user_id'] . strtotime($user['hash_expire']))))
        ) {

            // Template engine instance
            $Template = Template::getInstance();

            // Load i18n variables
            $Template->_load(DASHBOARD_DIR . '/app/modules/login/i18n/' . Session::getvar('current_language') . '.ini', 'main');

            // Page information
            $data = [
                // Page ID
                'page' => 'login_reset',
                // Page Title
                'page_title' => $Template->_get('login_reset'),
            ];

            // Push data to template engine
            $Template
                ->assign('data', $data)
                ->assign('email', $user['email'])
                ->assign('hash', $hash)
                ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/login/view/reset.tpl'));

        } else {

            Router::response(false, '', ABS_PATH . 'login');

        }

    }


    /**
     * Change password
     */
    public static function change_password(): void
    {

        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load(DASHBOARD_DIR . '/app/modules/login/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        if (
            Request::post('email') &&
            ($pass = Request::post('password')) &&
            ($email = normalizeEmail(Request::post('email'))) &&
            ($hash = Secure::sanitize(Request::post('hash')))
        ) {

            self::$model->doPassChange($email, $hash, $pass);

            Router::response(true, $Template->_get('login_reset_success'), ABS_PATH . 'login');

        }

        Router::response(false, '', ABS_PATH . 'login');

    }

}