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


	
	class ModelModules extends Model
	{
		public static function get()
		{
			$classes = array();

			foreach (get_declared_classes() as $k => $class)
			{
				$parrent = class_parents($class);

				if (in_array('Module', array_keys($parrent)))
				{
					$classes[$class]['name']     = $class;
					$classes[$class]['title']    = $class::$_moduleName . '_menu_name';
					$classes[$class]['short']    = $class::$_moduleName;
					$classes[$class]['ver']      = $class::$version;
					$classes[$class]['date']     = $class::$date;
				}
			}

			return $classes;
		}
	}