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


/**
 * Converts snake_case_string to PascalCaseString
 *
 * @param string $input
 * @return string
 */
function snakeToPascalCase(string $input): string
{
    return str_replace('_', '', ucwords($input, '_'));
}

/**
 * Converts snake_case_string to camelCaseString
 *
 * @param string $input
 * @return string
 */
function snakeToCamelCase(string $input): string
{
    return lcfirst(snakeToPascalCase($input));
}

/**
 * Converts camelCaseString to snake_case_string
 *
 * @param string $input
 * @return string
 */
function camelToSnakeCase(string $input): string
{
    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
    $res = $matches[0];
    foreach ($res as &$match) {
        $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
    }
    return implode('_', $res);
}