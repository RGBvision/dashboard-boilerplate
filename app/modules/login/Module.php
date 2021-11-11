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
 * @version    3.1
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ModuleLogin extends Module
{

    /**
     * @var string Module version
     */
    public static $version = '3.1';

    /**
     * @var string Module release date
     */
    public static $date = '07.10.2021';

    /**
     * @var string Module system name
     */
    public static $moduleName = 'login';

    /**
     * Constructor
     */
    public function __construct()
    {

        // Parent
        parent::__construct();

    }

}