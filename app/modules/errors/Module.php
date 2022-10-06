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

class ErrorsModule extends Module
{

    /**
     * @var string Module version
     */
    public static string $version = '4.0';

    /**
     * @var string Module release date
     */
    public static string $date = '07.10.2022';

    /**
     * @var string Module system name
     */
    public static string $moduleName = 'errors';

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

    }

}