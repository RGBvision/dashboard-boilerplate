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
 * @version    1.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Hooks
{
    public static $instance;
    public static $hooks;
    public static string $current_hook = '';
    public static $run_hooks;

    /**
     * Инициализация инстанса класса
     *
     * @return Hooks
     */
    public static function init(): Hooks
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }


    /**
     * Добавить функцию
     *
     * @param string $name имя хука
     * @param string $function имя функции
     * @param int $priority приоритет выполнения
     * @return bool
     */
    public static function add(string $name, string $function, int $priority = 10): bool
    {
        // Если хук с такой функцией уже зарегистрирован, то добавлять дубликат не нужно.
        if (isset(self::$hooks[$name][$priority][$function])) {
            return true;
        }

        // Добавляем хук функцию в массив $hooks
        self::$hooks[$name][$priority][$function] = array(
            'function' => $function
        );

        return true;
    }


    /**
     * Выполнить хук функции
     *
     * @param string $name имя хука
     * @param array|mixed|string $arguments параметры вызова функций
     * @return mixed|string
     */
    public static function action(string $name, $arguments = '')
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
                    $return = call_user_func_array($item['function'], [&$arguments]);

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


    /**
     * Удалить функцию из хуков
     *
     * @param string $name имя хука
     * @param string $function имя функции
     * @param int $priority приоритет
     * @return bool
     */
    public static function remove(string $name, string $function, int $priority = 10): bool
    {
        if (!isset(self::$hooks[$name][$priority][$function])) {
            return false;
        }
        unset(self::$hooks[$name][$priority][$function]);
        return true;
    }


    /**
     * Получить текущий хук
     *
     * @return string
     */
    public static function getCurrent(): string
    {
        return self::$current_hook;
    }


    /**
     * Проверить наличие хука с указанным именем и приоритетом
     *
     * @param string $name имя хука
     * @param int $priority приоритет
     * @return bool
     */
    public static function has(string $name, int $priority = 10): bool
    {
        return isset(self::$hooks[$name][$priority]);
    }


    /**
     * Проверить наличие хука с указанным именем
     *
     * @param string $name имя хука
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return isset(self::$hooks[$name]);
    }


    /**
     * Вывести отладочную информацию о хуках
     */
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