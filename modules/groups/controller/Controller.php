<?php

/**
 * This file is part of the RGB.dashboard package.
 *
 * (c) Alexey Graham <contact@rgbvision.net>
 *
 * @package    RGB.dashboard
 * @author     Alexey Graham <contact@rgbvision.net>
 * @copyright  2017-2019 RGBvision
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    1.7
 * @link       https://dashboard.rgbvision.net
 * @since      Class available since Release 1.0
 */



Dependencies::add(
	ABS_PATH . 'modules/groups/js/groups.js',
	100
);

class ControllerGroups extends Controller
{
	//-- Model
	public static $route_id;
	protected static $model;


	/*
	 |--------------------------------------------------------------------------------------
	 | ControllerGroups конструктор
	 |--------------------------------------------------------------------------------------
	 | Сразу назначаем Model из Router
	 |
	 */
	public function __construct()
	{
		self::$route_id = Router::getId();
		self::$model = Router::model();
	}


	/*
	 |--------------------------------------------------------------------------------------
	 | Router: groups
	 |--------------------------------------------------------------------------------------
	 | По умолчанию
	 |
	 */
	public static function index()
	{
		//-- Инстанс Smarty
		$Smarty = Tpl::getInstance();

		//-- Подгружаем файл переводов
		$Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

		//-- Информация
		$data = array(
			//-- ID навигации
			'page' => 'groups',
			//-- Title
			'page_title' => $Smarty->_get('groups_page_title'),
			//-- Header
			'header' => $Smarty->_get('groups_page_header'),
			//-- Breadcrumbs
			'breadcrumbs' => array(
				array(
					'text' => $Smarty->_get('main_page'),
					'href' => './',
					'page' => 'dashboard',
					'push' => 'true',
					'active' => false
				),
				array(
					'text' => $Smarty->_get('groups_breadcrumb'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => true
				)
			)
		);

		$Smarty
			->assign('groups', self::$model->getGroups())
			->assign('access', Permission::perm('groups_edit'))
			->assign('data', $data)
			->assign('right_header', $Smarty->fetch('modules/groups/view/right.tpl'))
			->assign('content', $Smarty->fetch('modules/groups/view/index.tpl'));
	}


	/*
	 |--------------------------------------------------------------------------------------
	 | Router: groups/edit
	 |--------------------------------------------------------------------------------------
	 |
	 |
	 */
	public static function edit()
	{
		$user_group_id = (int)Request::get('user_group_id');
		$user_group_name = self::$model->getGroupName($user_group_id);

		//-- Инстанс Smarty
		$Smarty = Tpl::getInstance();

		//-- Подгружаем файл переводов
		$Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

		//-- Информация
		$data = array(
			//-- ID навигации
			'page' => 'groups',
			//-- Title
			'page_title' => $Smarty->_get('groups_page_edit_title'),
			//-- Header
			'header' => $Smarty->_get('groups_page_edit_header'),
			//-- Breadcrumbs
			'breadcrumbs' => array(
				array(
					'text' => $Smarty->_get('main_page'),
					'href' => './',
					'page' => 'dashboard',
					'push' => 'true',
					'active' => true
				),
				array(
					'text' => $Smarty->_get('groups_breadcrumb_parent'),
					'href' => '/route/groups',
					'page' => 'groups',
					'push' => 'true',
					'active' => true
				),
				array(
					'text' => $Smarty->_get('groups_breadcrumb_edit'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => false
				),
				array(
					'text' => $user_group_name,
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => false
				)
			)
		);

		$editable = self::$model->getEditable($user_group_id);
		$disabled = self::$model->getDisabled($user_group_id);
		$exists = self::$model->getGroup($user_group_id);

		$Smarty
			->assign('data', $data)
			->assign('user_group_id', $user_group_id)
			->assign('user_group_name', $user_group_name)
			->assign('disabled', $disabled)
			->assign('editable', $editable)
			->assign('exists', $exists)
			->assign('permissions', self::$model->getAllPermissions($user_group_id))
			->assign('right_header', $Smarty->fetch('modules/groups/view/right_edit.tpl'))
			->assign('content', $Smarty->fetch('modules/groups/view/edit.tpl'));
	}


	/*
	 |--------------------------------------------------------------------------------------
	 | Router: groups/add
	 |--------------------------------------------------------------------------------------
	 |
	 |
	 */
	public static function add()
	{
		//-- Инстанс Smarty
		$Smarty = Tpl::getInstance();

		//-- Подгружаем файл переводов
		$Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

		//-- Информация
		$data = array(
			//-- ID навигации
			'page' => 'groups',
			//-- Title
			'page_title' => $Smarty->_get('groups_page_add_title'),
			//-- Header
			'header' => $Smarty->_get('groups_page_add_header'),
			//-- Breadcrumbs
			'breadcrumbs' => array(
				array(
					'text' => $Smarty->_get('main_page'),
					'href' => './',
					'page' => 'dashboard',
					'push' => 'true',
					'active' => true
				),
				array(
					'text' => $Smarty->_get('groups_breadcrumb_parent'),
					'href' => '/route/groups',
					'page' => 'groups',
					'push' => 'true',
					'active' => true
				),
				array(
					'text' => $Smarty->_get('groups_breadcrumb_add'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => false
				)
			)
		);

		$Smarty
			->assign('data', $data)
			->assign('access', Permission::perm('groups_edit'))
			->assign('permissions', self::$model->getAllPermissions())
			->assign('right_header', $Smarty->fetch('modules/groups/view/right_add.tpl'))
			->assign('content', $Smarty->fetch('modules/groups/view/add.tpl'));
	}


	/*
	 |--------------------------------------------------------------------------------------
	 | Router: groups/save
	 |--------------------------------------------------------------------------------------
	 |
	 |
	 */
	public static function save()
	{
		$Smarty = Tpl::getInstance();

		$Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

		self::$model->saveGroup();
	}


	/*
	 |--------------------------------------------------------------------------------------
	 | Router: groups/delete
	 |--------------------------------------------------------------------------------------
	 |
	 |
	 */
	public static function delete()
	{
		$Smarty = Tpl::getInstance();

		$Smarty->_load(CP_DIR . '/modules/' . self::$route_id . '/lang/' . Session::getvar('current_language') . '.ini', 'pages');

		self::$model->deleteGroup();
	}
}