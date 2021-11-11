<?php

	class ModelErrors extends Model
	{
		public static function message($header, $message)
		{
			$return = array(
				'message' => $message,
				'header' => $header,
				'theme' => 'danger',
				'success' => false
			);

			Json::show($return, true);
		}
	}