<?php


class ControllerGroups extends Controller
{

	public static string $route_id;
	protected static Model $model;


	
	public function __construct()
	{

        if (!Permission::check('groups_view')) {
            Request::redirect(ABS_PATH);
            Response::shutDown();
        }

        self::$route_id = Router::getId();
		self::$model = Router::model();

        Dependencies::add(
            ABS_PATH . 'assets/js/groups.js',
            100
        );
	}


	
	public static function index()
	{

		$Template = Template::getInstance();

		$Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

		$data = array(

			'page' => 'groups',

			'page_title' => $Template->_get('groups_page_title'),

			'header' => $Template->_get('groups_page_header'),

			'breadcrumbs' => array(
				array(
					'text' => $Template->_get('main_page'),
					'href' => '/',
					'page' => 'project',
					'push' => 'true',
					'active' => false
				),
				array(
					'text' => $Template->_get('groups_breadcrumb'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => true
				)
			)
		);

		$groups = UserGroup::getList();

        if (UGROUP !== UserGroup::SUPERADMIN) {
            foreach ($groups as $k => $group) {
                if ((int)$group['user_group_id'] === UserGroup::SUPERADMIN) {
                    Arrays::delete($groups, (string)$k);
                }
            }
        }

		$Template
            ->assign('data', $data)
			->assign('groups', $groups)
			->assign('access', Permission::perm('groups_edit'))
			->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/groups/view/index.tpl'));
	}


	
	public static function edit()
	{
		$user_group_id = (int)Request::get('user_group_id');
		$user_group_name = self::$model::getGroupName($user_group_id);

		$Template = Template::getInstance();

		$Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

		$data = array(

			'page' => 'groups',

			'page_title' => $Template->_get('groups_page_edit_title'),

			'header' => $Template->_get('groups_page_edit_header'),

			'breadcrumbs' => array(
				array(
					'text' => $Template->_get('main_page'),
					'href' => '/',
					'page' => 'project',
					'push' => 'true',
					'active' => true
				),
				array(
					'text' => $Template->_get('groups_breadcrumb'),
					'href' => '/groups',
					'page' => 'groups',
					'push' => 'true',
					'active' => true
				),
				array(
					'text' => $Template->_get('groups_breadcrumb_edit'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => false
				)
			)
		);

		$editable = self::$model->isEditable($user_group_id);
		$disabled = self::$model->getDisabled($user_group_id);
		$exists = self::$model->getGroup($user_group_id);

		$Template
			->assign('data', $data)
			->assign('user_group_id', $user_group_id)
			->assign('user_group_name', $user_group_name)
			->assign('disabled', $disabled)
			->assign('editable', $editable)
			->assign('exists', $exists)
			->assign('permissions', self::$model::getAllPermissions($user_group_id))
			->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/groups/view/edit.tpl'));
	}


	
	public static function add()
	{

		$Template = Template::getInstance();

		$Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

		$data = array(

			'page' => 'groups',

			'page_title' => $Template->_get('groups_page_add_title'),

			'header' => $Template->_get('groups_page_add_header'),

			'breadcrumbs' => array(
				array(
					'text' => $Template->_get('main_page'),
					'href' => '/',
					'page' => 'project',
					'push' => 'true',
					'active' => true
				),
				array(
					'text' => $Template->_get('groups_breadcrumb'),
					'href' => '/groups',
					'page' => 'groups',
					'push' => 'true',
					'active' => true
				),
				array(
					'text' => $Template->_get('groups_breadcrumb_add'),
					'href' => '',
					'page' => '',
					'push' => '',
					'active' => false
				)
			)
		);

		$Template
			->assign('data', $data)
			->assign('access', Permission::perm('groups_edit'))
			->assign('permissions', self::$model->getAllPermissions())
			->assign('content', $Template->fetch(DASHBOARD_DIR . '/app/modules/groups/view/add.tpl'));
	}


	
	public static function save()
	{
		$Template = Template::getInstance();

		$Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

		self::$model::saveGroup();
	}


	
	public static function delete()
	{
		$Template = Template::getInstance();

		$Template->_load(DASHBOARD_DIR . '/app/modules/groups/i18n/' . Session::getvar('current_language') . '.ini', 'pages');

		self::$model::deleteGroup();
	}
}