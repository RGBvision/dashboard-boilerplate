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
 * @version    4.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Arrays
{

    /**
     * Get value by key or path
     *
     * @param array $array array to search in
     * @param string $path key or path
     * @param string $glue path divider
     * @param mixed|null $default default value if nothing found
     * @return mixed
     */
    public static function get(array &$array, string $path, string $glue = '.', mixed $default = null): mixed
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

    /**
     * Searches the array for a given value and returns the first corresponding key if successful
     *
     * @param array $array
     * @param mixed $value
     * @param mixed|null $default
     * @return mixed
     */
    public static function search(array $array, mixed $value, mixed $default = null): mixed
    {
        $key = array_search($value, $array, true);
        return ($key === false) ? $default : $key;
    }


    /**
     * Get value by key or path
     *
     * @param array $array array
     * @param string $path key or path
     * @param mixed $value value
     * @param string $glue path divider
     */
    public static function set(array &$array, string $path, mixed $value, string $glue = '.'): void
    {
        $path_chunks = explode($glue, $path);
        $ref = &$array;

        foreach ($path_chunks as $chunk) {
            if (isset($ref) && !is_array($ref)) {
                $ref = [];
            }
            $ref = &$ref[$chunk];
        }

        $ref = $value;
    }


    /**
     * Delete value by key or path
     *
     * @param array $array array to search in
     * @param string $path key or path
     * @param string $glue path divider
     */
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

    /**
     * @param array $array
     * @param callable $callback
     * @param mixed $userdata
     * @param bool $delete
     * @return array
     */
    private static function array_walk_recursive_delete(array &$array, callable $callback, mixed $userdata = null, bool $delete = false): array
    {
        foreach ($array as $key => &$value) {

            if (is_array($value) || is_object($value)) {
                $value = self::array_walk_recursive_delete($value, $callback, $userdata, $delete);
            }

            if ((!is_int($key)) && ($callback($value, $key, $userdata) === $delete)) {
                unset($array[$key]);
            }

        }

        return $array;
    }

    /**
     * Unset value by key name
     *
     * @param array $array
     * @param array $key_names
     * @param bool $delete
     * @return void
     */
    public static function filterKeys(array &$array, array $key_names, bool $delete = false): void
    {

        if (!empty($key_names)) {
            self::array_walk_recursive_delete($array, static function (&$value, $key, $name) {
                return in_array($key, $name, true);
            }, $key_names, $delete);
        }
    }

    /**
     * Recursively iterates over each value in the array passing them to the callback function
     *
     * @param array $input
     * @param $callback
     * @return array
     */
    public static function filterRecursive(array $input, $callback = null): array
    {
        foreach ($input as &$value)
        {
            if (is_array($value))
            {
                $value = self::filterRecursive($value, $callback);
            }
        }

        return array_filter($input, $callback);
    }

    /**
     * Trim all array values
     *
     * @param array $array
     * @param string $characters
     * @return void
     */
    public static function trimAll(array &$array, string $characters = " \t\n\r\0\x0B"): void
    {
        array_walk_recursive($array, function (&$v) use ($characters) {
            $v = trim($v, $characters);
        });
    }

    /**
     * Rename keys in array
     *
     * @param array $array
     * @param array $pattern
     * @return void
     */
    public static function renameKeys(array &$array, array $pattern): void
    {
        if (!empty($array) && !empty($pattern)) {

            $return = [];

            foreach ($array as $key => $value) {

                if (is_array($value)) {
                    self::renameKeys($value, $pattern);
                }

                if (array_key_exists($key, $pattern)) {
                    $key = $pattern[$key];
                }

                $return[$key] = $value;
            }

            $array = $return;
            unset($return);

        }
    }

    /**
     * Unset value by key name (string only)
     *
     * @param array $array
     * @param string $key_name
     * @return void
     */
    public static function deleteKey(array &$array, string $key_name): void
    {

        function _isKey(&$value, $key, $name): bool
        {
            return ($key === $name);
        }

        function array_walk_recursive_delete(array &$array, callable $callback, $key_name = null): array
        {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    $value = array_walk_recursive_delete($value, $callback, $key_name);
                }
                if ($callback($value, $key, $key_name)) {
                    unset($array[$key]);
                }
            }

            return $array;
        }

        array_walk_recursive_delete($array, '_isKey', $key_name);
    }

    /**
     * Unset value by key name and value (string only)
     *
     * @param array $array
     * @param string $key_name
     * @param string $val
     * @return void
     */
    public static function deleteKeyValue(array &$array, string $key_name, string $val): void
    {

        function _isKeyVal($value, $key, $name, $val): bool
        {
            return ($key === $name) && ($value === $val);
        }

        function array_walk_recursive_delete(array &$array, callable $callback, $key_name = null, $val = null): array
        {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    $value = array_walk_recursive_delete($value, $callback, $key_name, $val);
                }
                if ($callback($value, $key, $key_name, $val)) {
                    unset($array[$key]);
                }
            }

            return $array;
        }

        array_walk_recursive_delete($array, '_isKeyVal', $key_name);
    }

    /**
     * Return element's path by key-value pair
     *
     * @param array $array
     * @param int|string $key
     * @param mixed $val
     * @param string $glue
     * @param bool $add_self_key
     * @param array $stack
     * @return bool|int|string
     */
    public static function pathByKeyValue(array $array, int|string $key, mixed $val, string $glue = '.', bool $add_self_key = true, array &$stack = []): bool|int|string
    {

        foreach ($array as $k => $v) {

            if (($v === $val) && ($k === $key)) {
                if ($add_self_key) {
                    array_unshift($stack, $k);
                }
                return $k;
            }

            if (is_array($v) && self::pathByKeyValue($v, $key, $val, $glue, $add_self_key, $stack)) {
                array_unshift($stack, $k);
                return implode($glue, $stack);
            }
        }

        return false;
    }

    /**
     * Convert array to object
     *
     * @param array|object $array массив для преобразования
     * @return object
     */
    public static function toObject(array|object $array): object
    {
        if (is_array($array)) {
            $obj = new stdClass();

            foreach ($array as $key => $val) {
                $obj->$key = $val;
            }
        } else if (is_object($array)) {
            $obj = $array;
        } else {
            $obj = null;
        }

        return $obj;
    }

    /**
     * Convert object to array
     *
     * @param object|array $object
     * @return array
     * @throws ReflectionException
     */
    public static function toArray(object|array $object): array
    {

        if (is_object($object)) {

            $reflectionClass = new ReflectionClass(get_class($object));
            $array = [];
            foreach ($reflectionClass->getProperties() as $property) {
                if ($property->isPublic()) {
                    $array[$property->getName()] = $property->getValue($object);
                }
            }
            return $array;
        }

        $object = (array)$object;

        if ($object === []) {
            return $object;
        }

        foreach ($object as $key => &$value) {
            if ((is_object($value) || is_array($value))) {
                $object[$key] = self::toArray($value);
            }
        }

        return $object;

    }

    /**
     * Convert array to XML file
     *
     * @param $array
     * @param bool $values_to_attributes
     * @return string
     */
    public static function toXML($array, bool $values_to_attributes = false): string
    {
        function array_to_xml($data, &$xml_data, $values_to_attributes = false): void
        {
            foreach ($data as $key => $value) {
                if (is_numeric($key)) {
                    if (is_array($value) && isset($value['@'])) {
                        $key = $value['@']; // naming by @ key
                    } else {
                        $key = 'item' . $key; //dealing with <0/>..<n/> issues
                    }
                }
                if (is_array($value)) {
                    $sub_node = $xml_data->addChild($key);
                    array_to_xml($value, $sub_node, $values_to_attributes);
                } else if ($key !== '@') {
                    if ($values_to_attributes) {
                        $xml_data->addAttribute("$key", htmlspecialchars("$value"));
                    } else {
                        $xml_data->addChild("$key", htmlspecialchars("$value"));
                    }
                }
            }
        }

        $xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><data></data>');

        array_to_xml($array, $xml_data, $values_to_attributes);

        return (string)$xml_data->asXML();

    }

    /**
     * Convert array to CSV file
     *
     * @param $array
     * @return string
     */
    public static function toCSV($array): string
    {

        function csvstr(array $fields): string
        {
            $f = fopen('php://memory', 'r+');
            if (fputcsv($f, $fields) === false) {
                return false;
            }
            rewind($f);
            return stream_get_contents($f);
        }

        $xml = self::toXML($array, true);

        $dom = new DOMDocument();
        $dom->loadXML($xml);

        $tails = [];

        foreach ($dom->getElementsByTagName('*') as $node) {
            if (!$node->hasChildNodes()) {
                $tails[] = $node->getNodePath();
            }
        }

        $result = [];

        $xpath = new DOMXPath($dom);

        $line = 1;
        foreach ($tails as $query) {
            $node = $xpath->query($query)->item(0);
            while ($node) {
                if ($node->hasAttributes()) {
                    foreach ($node->attributes as $attribute) {
                        if ($line === 1) {
                            $result[0][] = $attribute->nodeName;
                            //$result[0][] = '_' . $node->parentNode->nodeName . '_' . $attribute->nodeName;
                        }
                        $result[$line][] = $attribute->nodeValue;
                    }
                }
                $node = $node->parentNode;
            }
            $line++;
        }

        unset($xpath, $dom, $line);

        $csv = '';
        foreach ($result as $line) {
            $csv .= csvstr($line);
        }

        return $csv;
    }

    /**
     * Sort multi-dimensional array by key
     *
     * @param array $array
     * @param $key
     * @param int $sort_way
     * @return array
     */
    public static function multiSort(array $array, $key, int $sort_way = SORT_ASC): array
    {
        $keys = array_column($array, $key);
        array_multisort($keys, $sort_way, $array);
        return $array;
    }

}