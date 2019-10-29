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




	class ModelSettings extends Model
	{
		public static function saveSettings()
		{
			Router::demo();

			$type = 'danger';

			$Smarty = Tpl::getInstance();

			$permission = Permission::perm('settings_edit');

			if ($permission)
			{
				$message = $Smarty->_get('settings_message_edit_success');
				$type = 'success';
			}
			else
				{
					$message = $Smarty->_get('settings_message_perm_danger');
				}

			Router::response($type, $message, 'index.php?router=settings');
		}
	}