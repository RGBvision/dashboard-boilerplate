<?php

/**
 * This file is part of the dashboard.rgbvision.net package.
 *
 * (c) Alex Graham <contact@rgbvision.net>
 *
 * @package    dashboard.rgbvision.net
 * @author     Alex Graham <contact@rgbvision.net>
 * @copyright  Copyright 2017-2021, Alex Graham
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    0.1
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 2.4
 */

class Search
{

    protected static $registered_classes;
    protected static $instance = null;

    protected function __construct()
    {
        self::$registered_classes = get_declared_classes();
    }

    public static function init(): ?Search
    {
        if (!isset(self::$instance)) {
            self::$instance = new Search();
        }

        return self::$instance;
    }

    public static function getResults(string $query, int $max_results = 10): array
    {
        $result = [];

        foreach (self::$registered_classes as $class) {
            if (method_exists($class, '_search')) {
                $result[] = $class::_search($query);
            }
        }

        return $result;

    }

}