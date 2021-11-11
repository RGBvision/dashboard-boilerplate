<?php

	class ModuleErrors extends Module
	{
		public static $version = '1.0';

		public static $date = '11.10.2021';

		public static $_moduleName = 'errors';


		public function __construct()
		{
			parent::__construct();

			$Template = Template::getInstance();

			$Template->_load(DASHBOARD_DIR . '/app/modules/errors/i18n/' . Session::getvar('current_language') . '.ini', 'name');
		}
	}