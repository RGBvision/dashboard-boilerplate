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
 * @version    3.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 3.0
 */

class Injections
{
    public static array $templates = [];

    function __construct()
    {
        //
    }

    /**
     * Add HTML to inject
     *
     * @param string $html HTML code
     * @param int $priority priority
     */
    public static function addHtml(string $html, int $priority = 10): void
    {
        self::$templates[] = [
            'html' => $html,
            'priority' => $priority,
        ];
    }

    /**
     * Add template file to inject
     *
     * @param string $file template file
     * @param int $priority priority
     */
    public static function addFile(string $file, int $priority = 10): void
    {
        self::$templates[] = [
            'file' => $file,
            'priority' => $priority,
        ];
    }

    /**
     * Get an array of HTML injections
     *
     * @return array sorted array of HTML injections
     */
    public static function get(): array
    {

        $Template = Template::getInstance();

        $injections = Arrays::multiSort(self::$templates, 'priority');

        foreach ($injections as &$injection) {
            if ($injection['file']) {
                $injection['html'] = $Template->fetch($injection['file']);
                unset($injection['file']);
            }
        }

        return $injections;
    }

}