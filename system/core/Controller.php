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

abstract class Controller
{

    /**
     * @var Module Module class
     */
    public Module $module;

    /**
     * @var Model Model class
     */
    protected Model $model;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->module = Router::getModule();
        $this->model = Router::getModel();
    }

}