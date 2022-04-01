<?php

/**
 * This file is part of the dashboard.rgbvision.net package.
 *
 * (c) Alex Graham <contact@rgbvision.net>
 *
 * @package    dashboard.rgbvision.net
 * @author     Alex Graham <contact@rgbvision.net>
 * @copyright  Copyright 2017-2022, Alex Graham
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    1.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
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