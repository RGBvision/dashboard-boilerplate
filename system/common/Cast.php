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
 * @version    1.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 2.7
 */

class Cast
{

    public function __construct(array $properties = [])
    {
        if (!empty($properties)) {
            $this->cast($properties);
        }
    }

    private function cast(array $properties): void
    {
        foreach ($properties as $key => $value) {
            if (property_exists($this, (string)$key)) {
                $this->{(string)$key} = html_entity_decode($value);
            }
        }
    }
}