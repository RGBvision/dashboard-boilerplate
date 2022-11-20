<?php


class SettingsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        Dependencies::add(
            ABS_PATH . 'app/modules/settings/js/settings.js',
            100
        );
    }


    public function index()
    {
        //-- Get Smarty Instance
        $Template = Template::getInstance();

        //-- Get Lang file
        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'meta');

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

        $permission = Permissions::has('settings_edit');

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        //-- To Smarty
        $Template
            ->assign('configs', $this->model->getSettings())
            ->assign('permission', $permission)
            ->assign('data', $data)
            ->assign('content', $Template->fetch($this->module->path . '/view/index.tpl'));
    }


    public function save()
    {
        $Template = Template::getInstance();

        $Template->_load($this->module->path . '/i18n/' . Session::getvar('current_language') . '.ini', 'content');

        $this->model->saveSettings();
    }
}
