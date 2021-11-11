<?php


class ControllerDashboard extends Controller
{

    public static string $route_id;
    protected static Model $model;

    public function __construct()
    {

        if (!Permission::check('dashboard_view')) {
            Router::response(false, '', ABS_PATH . 'login');
        }

        self::$route_id = Router::getId();
        self::$model = Router::model();

        $files = [
            ABS_PATH . 'assets/vendors/progressbar.js/progressbar.min.js',
            ABS_PATH . 'assets/js/dashboard.js',
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

        $Template->_load(DASHBOARD_DIR . '/app/modules/dashboard/i18n/' . Session::getvar('current_language') . '.ini', 'main');

        $data = [

            'page' => 'dashboard',

            'page_title' => $Template->_get('dashboard_page_title'),

            'header' => $Template->_get('dashboard_page_header'),

            'breadcrumbs' => [
                [
                    'text' => $Template->_get('dashboard_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'active' => true,
                ],
            ],
        ];

        $Template->_load(DASHBOARD_DIR . '/app/modules/dashboard/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        $Template
            ->assign('data', $data)
            ->assign('_is_ajax', Request::isAjax())
            ->assign('storage_size', self::$model::getStorageSize())
            ->assign('storage_usage', self::$model::getStorageUsage())
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/dashboard/view/index.tpl'));
    }

    public static function backup_db()
    {
        $status = DB::backup();
        Response::setStatus($status ? 200 : 503);
        Json::show(['success' => $status], true);
    }

    public static function clear_cache()
    {
        Dir::delete_contents(DASHBOARD_DIR . TEMP_DIR . '/cache/smarty');
        Json::show(['success' => true], true);
    }


    public static function copy_template(string $src, string $dst, string $newname): void
    {
        $dir = opendir($src);

        if (!mkdir($dst) && !is_dir($dst)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dst));
        }

        while (false !== ($file = readdir($dir))) {
            if (($file !== '.') && ($file !== '..')) {
                if (is_dir($src . '/' . $file)) {
                    self::copy_template($src . '/' . $file, $dst . '/' . $file, $newname);
                } else {

                    $new_file = str_replace(
                        'example',
                        preg_replace('/\s+/', '', strtolower($newname)),
                        $file
                    );

                    $_new_name = explode(' ', $newname);

                    foreach ($_new_name as &$part) {
                        $part = ucfirst($part);
                    }

                    $content = File::getContents($src . '/' . $file);

                    $new_content = str_replace(
                        [
                            'example',
                            'Example',
                            'EXAMPLE',
                            ':date:',
                        ],
                        [
                            strtolower(implode('', $_new_name)),
                            implode('', $_new_name),
                            implode(' ', $_new_name),
                            date('d.m.Y'),
                        ],
                        $content
                    );

                    File::putContents($dst . '/' . $new_file, $new_content);

                }
            }
        }

        closedir($dir);
    }

    public static function generate()
    {

        if (
            ($name = Request::post('module')) &&
            (!Dir::exists(DASHBOARD_DIR . "/modules/$name"))
        ) {
            self::copy_template(DASHBOARD_DIR . "/tmp/module_template", DASHBOARD_DIR . '/app/modules/' . preg_replace('/\s+/', '', strtolower($name)), $name);
        }

        Request::redirect(Request::referrer());
    }

}