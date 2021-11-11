<?php

class ControllerErrors extends Controller
{

    public static string $route_id;
    protected static Model $model;

    public function __construct()
    {
        self::$route_id = Router::getId();
        self::$model = Router::model();
    }


    public static function index()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $_header = $Template->_get('header_404');
        $_message = $Template->_get('message_404');

        $data = [

            'page' => 'errors',

            'page_title' => $Template->_get('404_page_title'),

            'header' => $Template->_get('404_page_title'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH,
                    'page' => 'home',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('404_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $Template
            ->assign('data', $data)
            ->assign('_header', $_header)
            ->assign('_message', $_message)
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/errors/view/index.tpl'));
    }


    public static function module()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $request = Request::get('model');

        $_header = $Template->_get('header_model');
        $_message = sprintf($Template->_get('message_module'), $request);

        if (Request::isAjax())
            self::$model->message($_header, $_message);

        $data = [

            'page' => 'errors',

            'page_title' => $Template->_get('error_page_title'),

            'header' => $Template->_get('error_page_title'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => './',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('error_page_title'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $Template
            ->assign('data', $data)
            ->assign('_header', $_header)
            ->assign('_message', $_message)
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/errors/view/index.tpl'));
    }


    public static function model()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $request = Request::get('model');

        $_header = $Template->_get('header_model');
        $_message = sprintf($Template->_get('message_model'), $request);

        if (Request::isAjax())
            self::$model->message($_header, $_message);

        $data = [

            'page' => 'errors',

            'page_title' => $Template->_get('error_page_title'),

            'header' => $Template->_get('error_page_title'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => './',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('error_page_title'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $Template
            ->assign('data', $data)
            ->assign('_header', $_header)
            ->assign('_message', $_message)
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/errors/view/index.tpl'));
    }


    public static function controller()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $request = Request::get('controller');

        $_header = $Template->_get('header_controller');
        $_message = sprintf($Template->_get('message_controller'), $request);

        $data = [

            'page' => 'errors',

            'page_title' => $Template->_get('error_page_title'),

            'header' => $Template->_get('error_page_title'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => './',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('error_page_title'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $Template
            ->assign('data', $data)
            ->assign('_header', $_header)
            ->assign('_message', $_message)
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/errors/view/index.tpl'));
    }


    public static function method()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $request = Request::get('method');
        $parts = [];

        if (isset($request))
            $parts = explode('/', preg_replace('/[^a-zA-Z0-9_\/]/', '', trim((string)$request, '/')));

        $_header = $Template->_get('header_method');
        $_message = sprintf($Template->_get('message_method'), $parts[0], $parts[1]);

        $data = [

            'page' => 'errors',

            'page_title' => $Template->_get('error_page_title'),

            'header' => $Template->_get('error_page_title'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => './',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('error_page_title'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $Template
            ->assign('data', $data)
            ->assign('_header', $_header)
            ->assign('_message', $_message)
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/errors/view/index.tpl'));
    }


    public static function denied()
    {

        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $_header = $Template->_get('header_denied');
        $_message = $Template->_get('message_denied');

        $data = [

            'page' => 'errors',

            'page_title' => $Template->_get('error_page_title'),

            'header' => $Template->_get('error_page_title'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => './',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('error_page_title'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $Template
            ->assign('data', $data)
            ->assign('_header', $_header)
            ->assign('_message', $_message)
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/errors/view/index.tpl'));
    }
}