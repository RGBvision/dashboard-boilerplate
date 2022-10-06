<?php


class SettingsController extends Controller
{

    /*
     |--------------------------------------------------------------------------------------
     | SettingsController конструктор
     |--------------------------------------------------------------------------------------
     | Сразу назначаем Model из Router
     |
     */
    public function __construct()
    {
        parent::__construct();
        Dependencies::add(
            ABS_PATH . 'app/modules/settings/js/settings.js',
            100
        );
    }


    /*
     |--------------------------------------------------------------------------------------
     | Router: settings
     |--------------------------------------------------------------------------------------
     | По умолчанию
     |
     */
    public static function index()
    {
        //-- Get Smarty Instance
        $Template = Template::getInstance();

        //-- Get Lang file
        $Template->_load(DASHBOARD_DIR . '/app/modules/settings/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        //-- Data page
        $data = [
            //-- Navigation
            'page' => 'settings',
            //-- Title
            'page_title' => $Template->_get('settings_page_title'),
            //-- Header
            'header' => $Template->_get('settings_page_header'),
            //-- Breadcrumbs
            'breadcrumbs' => [
                [
                    'text' => $Template->_get('main_page'),
                    'href' => '/',
                    'page' => 'dashboard',
                    'active' => true,
                ],
                [
                    'text' => $Template->_get('settings_breadcrumb'),
                    'href' => '',
                    'page' => '',
                    'active' => false,
                ],
            ],
        ];

        $permission = Permission::perm('settings_edit');

        //-- To Smarty
        $Template
            ->assign('configs', self::$model->getSettings())
            ->assign('permission', $permission)
            ->assign('data', $data)
            ->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/settings/view/index.tpl'));
    }


    /*
     |--------------------------------------------------------------------------------------
     | Router: settings/save
     |--------------------------------------------------------------------------------------
     |
     |
     */
    public static function save()
    {
        $Template = Template::getInstance();

        $Template->_load(DASHBOARD_DIR . '/app/modules/settings/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

        self::$model->saveSettings();
    }
}
