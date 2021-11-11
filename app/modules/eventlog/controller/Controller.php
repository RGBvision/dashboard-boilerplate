<?php


class ControllerEventlog extends Controller
{

    public static string $route_id;
    protected static Model $model;

    public function __construct()
    {

        if (!Permission::check('eventlog_view')) {
            Request::redirect(ABS_PATH);
            Response::shutDown();
        }

        self::$route_id = Router::getId();
        self::$model = Router::model();

        $files = [
            ABS_PATH . 'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css',
            ABS_PATH . 'assets/vendors/datatables.net/jquery.dataTables.js',
            ABS_PATH . 'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js',
            ABS_PATH . 'assets/js/eventlog.js',
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

        $Template->_load(DASHBOARD_DIR . '/app/modules/eventlog/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $data = [

            'page' => 'eventlog',

            'page_title' => $Template->_get('eventlog_page_title'),

            'header' => $Template->_get('eventlog_page_header'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => ABS_PATH . 'dashboard',
                    'page' => 'dashboard',
                    'active' => false,
                ],
                [
                    'text' => $Template->_get('eventlog_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $Template->_load(DASHBOARD_DIR . '/app/modules/eventlog/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        $Template
            ->assign('data', $data)
            ->assign('_is_ajax', Request::isAjax())
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/eventlog/view/index.tpl'));
    }

    public static function get()
    {

        $eventlog = Log::get(Log::SORTABLE[(int)Request::post('order.0.column')], Request::post('order.0.dir'), (int)Request::post('length'), (int)Request::post('start'), Request::post('search.value'));

        $res = [
            'draw' => (int)Request::post('draw'),
            'recordsTotal' => Log::total(),
            'recordsFiltered' => Log::total(Request::post('search.value')),
            'search_value' => Request::post('search.value'),
            'data' => $eventlog,
        ];

        Json::show($res, true);

    }

}