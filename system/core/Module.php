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

abstract class Module
{

    /**
     * Module ID
     */
    const ID = null;

    /**
     * Module version
     */
    const VERSION = null;

    /**
     * Module release date
     */
    const DATE = null;

    /**
     * Module icon
     */
    const ICON = '';

    /**
     * Module permissions
     */
    const PERMISSIONS = [];

    readonly string $path;
    readonly string $uri;

    /**
     * Constructor
     */
    protected function __construct()
    {
        $this->path = DASHBOARD_DIR . MODULES_DIR . DS . static::ID . DS;
        $this->uri = ABS_PATH . ltrim(MODULES_DIR, '/') . DS . static::ID . DS;
    }

}