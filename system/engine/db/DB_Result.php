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

class DB_Result
{
	//--- Query result
	public $_result = null;

	//--- Constructor, returns an object with a pointer to the SQL query result
	public function __construct($_result)
	{
		$this->_result = $_result;
	}

	//--- Returns result as an associative or numerical array
	public function getMixed()
	{
		if (is_array($this->_result)) {
			$a = current($this->_result);

			next($this->_result);

			$b = array();

			if (!is_array($a)) {
                return false;
            }

			foreach ($a as $k => $v) {
                $b[] = $v;
            }

			return array_merge($b, $a);
		}

		return @mysqli_fetch_array($this->_result);
	}

	//--- Returns result as a numerical array
	public function getArray(): ?array
    {
		if (is_array($this->_result)) {
			$a = $this->getAssoc();

			$b = array();

			foreach ($a as $v) {
                $b[] = $v;
            }

			return $b;
		}

		return @mysqli_fetch_array($this->_result);
	}

	//--- Returns result as an associative array
	public function getAssoc()
	{
		if (is_array($this->_result)) {
			$a = current($this->_result);

			next($this->_result);

			return $a;
		}

		return @mysqli_fetch_assoc($this->_result);
	}

	//--- Returns result as an object
	public function getObject()
	{
		if (is_array($this->_result)) {
			$a = $this->getAssoc();

			return Arrays::toObject($a);
		}

		return @mysqli_fetch_object($this->_result);
	}

	//--- Returns result as a string
	public function getRow()
	{
		if (is_array($this->_result)) {
			$a = current($this->_result);

			if (is_array($a)) {
                return current($a);
            }

            return false;
        }

		if ($this->numRows()) {
			$a = @mysqli_fetch_row($this->_result);

			return $a[0];
		}

		return false;
	}

	//--- Get number of query result rows
	public function numRows(): int
    {
		if (is_array($this->_result)) {
			return (int)count($this->_result);
		}

		return @mysqli_num_rows($this->_result);
	}

	//--- Get number of query result fields
	public function numFields(): int
    {
		if (is_array($this->_result)) {
			$a = current($this->_result);

			return count($a);
		}

		return (int)@mysqli_num_fields($this->_result);
	}

	//--- Free memory associated with a result
	public function Close(): bool
    {
		if (is_object($this->_result)) {
            @mysqli_free_result($this->_result);
        }

		$this->_result = null;

		return true;
	}

	public function getResult()
	{
		return $this->_result;
	}

	public function __destruct()
	{
		$this->Close();
	}
}