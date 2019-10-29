<?php

/**
 * This file is part of the RGB.dashboard package.
 *
 * (c) Alexey Graham <contact@rgbvision.net>
 *
 * @package    RGB.dashboard
 * @author     Alexey Graham <contact@rgbvision.net>
 * @copyright  2017-2019 RGBvision
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    1.7
 * @link       https://dashboard.rgbvision.net
 * @since      Class available since Release 1.0
 */

class Hooks
{
    public static $instance;
    public static $hooks;
    public static $current_hook;
    public static $run_hooks;

    public static function init(): Hooks
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    //--- Add Hook
    //--- ToDo: strictly typed
    public static function add($name, $function, int $priority = 10): bool
    {
        // If we have already registered this action return true
        if (isset(self::$hooks[$name][$priority][$function])) {
            return true;
        }
        // Allows to iterate through multiple action hooks
        if (is_array($name)) {
            foreach ($name as $item) {
                // Store the action hook in the $hooks array
                self::$hooks[$item][$priority][$function] = array(
                    'function' => $function
                );
            }
        } else {
            // Store the action hook in the $hooks array
            self::$hooks[$name][$priority][$function] = array(
                'function' => $function
            );
        }

        return true;
    }

    //--- Run Hook
    public static function action($name, $arguments = '')
    {

        if (!isset(self::$hooks[$name])) {
            return $arguments;
        }

        // Set the current running hook to this
        self::$current_hook = $name;

        // Key sort our action hooks
        ksort(self::$hooks[$name]);
        foreach (self::$hooks[$name] as $priority => $items) {
            if (is_array($items)) {
                foreach ($items as $item) {
                    $return = call_user_func_array($item['function'], array(
                        &$arguments
                    ));

                    if ($return) {
                        $arguments = $return;
                    }

                    self::$run_hooks[$item][$priority];
                }
            }
        }

        self::$current_hook = '';

        return $arguments;
    }

    //--- Remove Hook
    public static function remove(string $name, $function, int $priority = 10): bool
    {
        if (!isset(self::$hooks[$name][$priority][$function])) {
            return false;
        }
        unset(self::$hooks[$name][$priority][$function]);
        return true;
    }


    //--- Get the currently running action hook
    public static function getCurrent()
    {
        return self::$current_hook;
    }


    //--- Check if hook was started
    public static function has($hook, $priority = 10): bool
    {
        if (isset(self::$hooks[$hook][$priority])) {
            return true;
        }

        return false;
    }

    //--- Hook Exists
    public static function exists($name): bool
    {
        if (isset(self::$hooks[$name])) {
            return true;
        }

        return false;
    }

    //--- Print information about all Hooks and actions
    public static function debug(): void
    {
        if (isset(self::$hooks)) {
            echo '<pre>';
            echo '<h2>Registered action hooks</h2>';
            print_r(self::$hooks);
            echo '</pre>';
            echo '<br />';
        }
        if (isset(self::$run_hooks)) {
            echo '<pre>';
            echo '<h2>Started action hooks</h2>';
            print_r(self::$run_hooks);
            echo '</pre>';
            echo '<br />';
        }
    }
}