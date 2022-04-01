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
 * @version    2.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

abstract class Module
{

    /**
     * @var string Module version
     */
    public static string $version;

    /**
     * @var string Module release date
     */
    public static string $date;

    /**
     * @var string Module system name
     */
    public static string $moduleName;

    /**
     * Constructor
     */
    protected function __construct()
    {

    }

}