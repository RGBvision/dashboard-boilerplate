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
 * @version    2.4
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

abstract class Controller
{

    /**
     * @var string Route ID
     */
    public static string $route_id;

    /**
     * @var Model Model class
     */
    protected static Model $model;

    /**
     * Constructor
     */
    public function __construct()
    {

        self::$route_id = Router::getId();
        self::$model = Router::model();

    }

}