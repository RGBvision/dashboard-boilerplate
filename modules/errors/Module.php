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



	class ModuleErrors extends Module
	{
		public static $version = '1.0';

		public static $date = '18.12.2017';

		public static $_moduleName = 'errors';


		public function __construct()
		{
			parent::__construct();

			//-- Get Smarty Instance
			$Smarty = Tpl::getInstance();

			//-- Get Lang file
			$Smarty->_load(CP_DIR . '/modules/errors/lang/' . $_SESSION['current_language'] . '.ini', 'name');
		}
	}