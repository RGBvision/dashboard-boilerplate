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

class Secure
{
	protected function __construct()
	{
		//--
	}

	public static function cleanOut($text): string
    {
		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

		return stripslashes($text);
	}

	public static function sanitize(string $string, bool $trim = false, bool $int = false, bool $str = false): string
	{
		$string = filter_var($string, FILTER_SANITIZE_STRING);
		$string = trim($string);
		$string = stripslashes($string);
		$string = strip_tags($string);
		$string = str_replace(
			array(
				'‘',
				'’',
				'“',
				'”'
			),
			array(
				"'",
				"'",
				'"',
				'"'
			),
			$string
		);

		if ($trim) {
            $string = substr($string, 0, $trim);
        }
		if ($int) {
            $string = preg_replace("/[^0-9\s]/", '', $string);
        }
		if ($str) {
            $string = preg_replace("/[^a-zA-Z\s]/", '', $string);
        }

		return $string;
	}

	public static function cleanSanitize($string, $trim = false, $end_char = '&#8230;')
	{
		$string = self::cleanOut($string);
		$string = filter_var($string, FILTER_SANITIZE_STRING);
		$string = trim($string);
		$string = stripslashes($string);
		$string = strip_tags($string);
		$string = str_replace(array(
			'‘',
			'’',
			'“',
			'”'),
			array(
				"'",
				"'",
				'"',
				'"'
			), $string);

		if ($trim) {
			if (strlen($string) < $trim) {
                return $string;
            }

			$string = preg_replace("/\s+/", ' ', str_replace(array(
				"\r\n",
				"\r",
				"\n"), ' ', $string));

			if (strlen($string) <= $trim) {
                return $string;
            }

			$out = '';

			foreach (explode(' ', trim($string)) as $val) {
				$out .= $val . ' ';

				if (strlen($out) >= $trim) {
					$out = trim($out);

					return (strlen($out) === strlen($string))
						? $out
						: $out . $end_char;
				}
			}
		}

		return $string;
	}
}