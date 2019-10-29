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

class Sessions extends \SessionHandler
{
	public function create_sid()
	{
		return parent::create_sid();
	}

	public function open($path, $name)
	{
		return parent::open($path, $name);
	}

	public function close()
	{
		return parent::close();
	}

	public function read($session_id)
	{
		return parent::read($session_id);
	}

	public function write($session_id, $data)
	{
		return parent::write($session_id, $data);
	}

	public function destroy($session_id)
	{
		return parent::destroy($session_id);
	}

	public function gc($maxlifetime)
	{
		return parent::gc($maxlifetime);
	}
}