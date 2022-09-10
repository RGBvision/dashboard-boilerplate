<?php


class ControllerRoutes extends Controller
{

    public function __construct()
    {

        parent::__construct();

        $files = [
            ABS_PATH . 'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css',
            ABS_PATH . 'assets/vendors/datatables.net/jquery.dataTables.js',
            ABS_PATH . 'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js',
            ABS_PATH . 'app/modules/routes/js/routes.js',
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
        // Инстанс Smarty
        $Template = Template::getInstance();

        // Подгружаем файл переводов
        $Template->_load(DASHBOARD_DIR . '/app/modules/routes/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        // Информация
        $data = [
            // ID навигации
            'page' => 'routes',
            // Title
            'page_title' => $Template->_get('routes_page_title'),
            // Header
            'header' => $Template->_get('routes_page_header'),
            // Breadcrumbs
            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('routes_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $Template->_load(DASHBOARD_DIR . '/app/modules/routes/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        // To Smarty
        $Template
            ->assign('data', $data)
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/routes/view/index.tpl'));
    }

    public static function get()
    {

        $fields = ['name'];

        $routes = App\Routes::get($fields[(int)Request::post('order.0.column')], Request::post('order.0.dir'), (int)Request::post('length'), (int)Request::post('start'), Request::post('search.value'));

        $res = [
            'draw' => (int)Request::post('draw'),
            'recordsTotal' => App\Routes::total(),
            'recordsFiltered' => App\Routes::total(Request::post('search.value')),
            'search_value' => Request::post('search.value'),
            'data' => $routes,
        ];

        Json::show($res, true);

    }

}