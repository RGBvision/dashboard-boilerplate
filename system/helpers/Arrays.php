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

class Arrays
{

    protected function __construct()
    {
        //
    }

    //--- Get value by key or path
    public static function get(array &$array, string $path, string $glue = '.', $default = null)
    {
        $path_chunks = explode($glue, $path);
        $ref = &$array;

        foreach ($path_chunks as $chunk) {
            if (is_array($ref) && array_key_exists($chunk, $ref)) {
                $ref = &$ref[$chunk];
            } else {
                return $default;
            }
        }

        return $ref;
    }

    //--- Set value
    public static function set(array &$array, string $path, $value, string $glue = '.'): void
    {
        $path_chunks = explode($glue, $path);
        $ref = &$array;

        foreach ($path_chunks as $chunk) {
            if (isset($ref) && !is_array($ref)) {
                $ref = array();
            }
            $ref = &$ref[$chunk];
        }

        $ref = $value;
    }

    //--- Unset value
    public static function delete(array &$array, string $path, string $glue = '.'): void
    {
        $path_chunks = explode($glue, $path);
        $key = array_shift($path_chunks);

        if (empty($path_chunks)) {
            unset($array[$key]);
        } else {
            self::delete($array[$key], implode($glue, $path_chunks));
        }
    }

    public static function toObject($array)
    {
        if (is_array($array)) {
            $obj = new StdClass();

            foreach ($array as $key => $val) {
                $obj->$key = $val;
            }
        } else {
            $obj = $array;
        }

        return $obj;
    }

    public static function toArray($object): array
    {
        $object = (array)$object;

        if ($object === array()) {
            return $object;
        }

        foreach ($object as $key => &$value) {
            if ((is_object($value) || is_array($value))) {
                $object[$key] = self::toArray($value);
            }
        }

        return $object;
    }

    public static function multiSort(array $array, $key, $sort_way = SORT_ASC): array
    {
        $keys = array_column($array, $key);
        array_multisort($keys, $sort_way, $array);
        return $array;
    }

    public static function safe_serialize(array $array): string
    {
        return base64_encode(serialize($array));
    }


    public static function safe_unserialize(string $string): array
    {
        return unserialize(base64_decode($string), ['allowed_classes' => false]);
    }
}