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

class LoginController extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        // Add JS dependencies
        Dependencies::add($this->module->uri . '/js/login.js', 100);

    }

    /**
     * Displays Login form
     *
     * @param string|null $error error message to display
     */
    private function displayLoginForm(?string $message = null, ?string $error = null): void
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

        // Page information
        $data = [
            // Page ID
            'page' => 'login',
            // Page Title
            'page_title' => $Template->_get('login_page_title'),
        ];

        if (Request::isAjax()) {
            Router::response(!$error, $error ?? $message, Request::referrer());
        }

        // Push data to template engine
        $Template
            ->assign('data', $data)
            ->assign('message', $message)
            ->assign('error', $error)
            ->assign('content', $Template->fetch( $this->module->path . '/view/index.tpl'));
    }

    /**
     * Login page (default route)
     */
    public function index(): void
    {

        // Redirect to Dashboard if already logged in and has permission to view dashboard 
        if (Permissions::has('dashboard_view')) {
            Router::response(true, '', ABS_PATH . 'dashboard');
        }

        $this->displayLoginForm();
    }

    /**
     * User authentication
     */
    public function auth(): void
    {

        // Template engine instance
        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        $error_message = $Template->_get('login_wrong_pass');

        if (($user_email = Request::post('email')) && ($user_password = Request::post('password'))) {

            $keep_in = Request::post('keep_in') && ((int)Request::post('keep_in') === 1);

            switch (Auth::userLogin($user_email, $user_password, LOGIN_USER_IP, $keep_in, 3)) {
                case Auth::LOGIN_SUCCESS:
                    $redirect_link = Session::getvar('redirect_link') ?: ABS_PATH;
                    Session::delvar('redirect_link');
                    Router::response(true, '', $redirect_link);
                    break;
                case Auth::WRONG_PASS:
                    $error_message = $Template->_get('login_wrong_pass');
                    break;
                case Auth::USER_INACTIVE:
                    $error_message = $Template->_get('login_user_inactive');
                    break;
            }

        }

        $this->displayLoginForm(null, $error_message);

    }

    /**
     * User logout
     */
    public function logout(): void
    {
        Auth::userLogout();
        Router::response(true, '', ABS_PATH);
    }

    /**
     * Send e-mail with password reset link.
     */
    public function reset_request(): void
    {

        $Template = Template::getInstance();
        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        if (
            ($email = Valid::normalizeEmail(Request::post('email'))) &&
            (DB::exists("SELECT COUNT(email) FROM users WHERE email = ?", $email)) &&
            ($user = DB::row("SELECT * FROM users WHERE email = ? LIMIT 1", $email))
        ) {

            $expired = date('Y-m-d H:i:s', strtotime('+4 hours'));
            $hash = md5((int)$user['user_id'] . $email) . md5((int)$user['user_id'] . strtotime($expired));

            // Generate password change link and send it to user's email
            if ($this->model->preparePassChange($email, $hash, $expired)) {

                $body = sprintf($Template->_get('login_reset_mail_body'), IP::getIp(), HOST, ABS_PATH, $hash, HOST, ABS_PATH, $hash, $expired);

                Mailer::send(
                    $email,
                    $body,
                    $Template->_get('login_reset_mail_title'),
                    '',
                    '',
                    'text/html'
                );

                $this->displayLoginForm($Template->_get('login_reset_mail_sent'));

            }

        }

        $this->displayLoginForm(null, $Template->_get('login_wrong_email'));

    }


    /**
     * Password change form
     */
    public function change(string $hash = ''): void
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
            $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

            // Page information
            $data = [
                // Page ID
                'page' => 'login_reset',
                // Page Title
                'page_title' => $Template->_get('login_reset'),
            ];

            // Load i18n variables
            $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

            // Push data to template engine
            $Template
                ->assign('data', $data)
                ->assign('email', $user['email'])
                ->assign('hash', $hash)
                ->assign('content', $Template->fetch( $this->module->path . '/view/reset.tpl'));

        } else {

            Router::response(false, '', ABS_PATH . 'login');

        }

    }


    /**
     * Change password
     */
    public function change_password(): void
    {

        $Template = Template::getInstance();

        // Load i18n variables
        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        if (
            Request::post('email') &&
            ($pass = Request::post('password')) &&
            ($email = Valid::normalizeEmail(Request::post('email'))) &&
            ($hash = Secure::sanitize(Request::post('hash'))) &&
            ($this->model->doPassChange($email, $hash, $pass))
        ) {

            Router::response(true, $Template->_get('login_reset_success'), ABS_PATH . 'login');

        }

        Router::response(false, '', ABS_PATH . 'login');

    }

}