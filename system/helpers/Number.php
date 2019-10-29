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

class Number
{
	protected function __construct()
	{
		//
	}

	//--- Format size
    // Snippet from PHP Share: http://www.phpshare.org
	public static function formatSize($size): string
    {
		if ($size >= 1073741824) {
			$size = round($size / 1073741824 * 100) / 100 . ' Gb';
		} elseif ($size >= 1048576) {
			$size = round($size / 1048576 * 100) / 100 . ' Mb';
		} elseif ($size >= 1024) {
			$size = round($size / 1024 * 100) / 100 . ' Kb';
		} else {
			$size .= ' b';
		}

		return $size;
	}

	//--- Formatted number output
	public static function numFormat($number, $decimal = 0, $after = '.', $thousand = '\''): string
    {
		if ($number) {
            return number_format($number, $decimal, $after, $thousand);
        }

		return '';
	}

	//--- time interval
	public static function microtimeDiff($a, $b): float
    {
		list($a_dec, $a_sec) = explode(' ', $a);
		list($b_dec, $b_sec) = explode(' ', $b);

		return $b_sec - $a_sec + $b_dec - $a_dec;
	}


	//--- Converts a numeric value to words
	public static function numberToWords($number): string
    {
		$words = array(
			'zero',
			'one',
			'two',
			'three',
			'four',
			'five',
			'six',
			'seven',
			'eight',
			'nine',
			'ten',
			'eleven',
			'twelve',
			'thirteen',
			'fourteen',
			'fifteen',
			'sixteen',
			'seventeen',
			'eighteen',
			'nineteen',
			'twenty',
			30 => 'thirty',
			40 => 'forty',
			50 => 'fifty',
			60 => 'sixty',
			70 => 'seventy',
			80 => 'eighty',
			90 => 'ninety',
			100 => 'hundred',
			1000 => 'thousand'
		);

		$number_in_words = '';

		if (is_numeric($number)) {
			$number = (int)round($number);

			if ($number < 0) {
				$number = -$number;
				$number_in_words = 'minus ';
			}

			if ($number > 1000) {

				$number_in_words .= self::numberToWords(floor($number / 1000)) . ' ' . $words[1000];

				$hundreds = $number % 1000;

				$tens = $hundreds % 100;

				if ($hundreds > 100) {
					$number_in_words .= ', ' . self::numberToWords($hundreds);
				} elseif ($tens) {
					$number_in_words .= ' and ' . self::numberToWords($tens);
				}
			} elseif ($number > 100) {
				$number_in_words .= self::numberToWords(floor($number / 100)) . ' ' . $words[100];

				$tens = $number % 100;

				if ($tens) {
					$number_in_words .= ' and ' . self::numberToWords($tens);
				}
			} elseif ($number > 20) {
				$number_in_words .= ' ' . $words[10 * floor($number / 10)];

				$units = $number % 10;

				if ($units) {
					$number_in_words .= self::numberToWords($units);
				}
			} else {
				$number_in_words .= ' ' . $words[$number];
			}

			return $number_in_words;
		}
		return false;
	}
}