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

class Loader
{
    protected static array $classes = [];
    protected static array $directories = [];
    protected static array $namespaces = [];
    protected static array $aliases = [];
    protected static array $modules = [];

    protected function __construct()
    {
        //
    }

    /**
     * Add class to autoloader
     *
     * @param string $className class name
     * @param string $classPath class file path
     */
    public static function addClass(string $className, string $classPath): void
    {
        self::$classes[$className] = $classPath;
    }

    /**
     * Add array of classes to autoloader
     *
     * @param array $classes
     */
    public static function addClasses(array $classes): void
    {
        foreach ($classes as $name => $path) {
            self::$classes[$name] = $path;
        }
    }

    /**
     * Includes and evaluates all PHP files in specified directory
     *
     * @param string $path path to directory with PHP files
     */
    public static function addFiles(string $path): void
    {
        $files = glob($path . '/*.php');

        foreach ($files as $file) {
            require($file);
        }
    }

    /**
     * Load modules
     *
     * @param string $path path to modules directory
     * @return array
     */
    public static function addModules(string $path = ''): array
    {
        $dir = dir($path);

        while ($entry = $dir->read()) {

            if (str_starts_with($entry, '.')) {
                continue;
            }

            if (!empty(self::$modules[$entry])) {
                continue;
            }

            $module_dir = $dir->path . DS . $entry;

            if (!is_dir($module_dir)) {
                continue;
            }

            if (is_file($module_dir . '/Module.php') && include_once($module_dir . '/Module.php')) {

                $module_name = snakeToPascalCase($entry) . 'Module';
                self::$modules[$entry] = new $module_name();

            } else {
                // ToDo: Log errors
                $modules['errors'][] = $entry;
            }

        }

        $dir->Close();

        return self::$modules;
    }

    /**
     * Load module and its classes
     *
     * @param string $name
     * @return string|null
     */
    public static function loadModule(string $name = ''): ?string
    {
        if (self::$modules[$name]) {

            // Model
            $file = self::$modules[$name]->path . '/model/Model.php';
            $model = snakeToPascalCase($name) . 'Model';

            if (is_file($file)) {
                Loader::addClass($model, $file);
            } else {
                Request::redirect('/errors/model?model=' . $model);
            }

            // Controller
            $file = self::$modules[$name]->path . '/controller/Controller.php';
            $controller = snakeToPascalCase($name) . 'Controller';

            if (is_file($file)) {
                Loader::addClass($controller, $file);
                return $controller;
            } else {
                Request::redirect('/errors/controller?controller=' . $controller);
            }

        } else {
            Request::redirect('/errors/module?module=' . $name);
        }

        return null;

    }

    public static function getModule(string $name): ?Module
    {
        return self::$modules[$name] ?? null;
    }

    /**
     * Add directory to autoloader
     *
     * @param string $path путь к папке с классами
     */
    public static function addDirectory(string $path): void
    {
        self::$directories[] = rtrim($path, '/');
    }

    /**
     * Register namespace
     *
     * @param string $namespace имя
     * @param string $path путь
     */
    public static function regNamespace(string $namespace, string $path): void
    {
        self::$namespaces[trim($namespace, '\\') . '\\'] = rtrim($path, '/');
    }

    /**
     * Add class alias
     *
     * @param string $alias псевдоним
     * @param string $className имя класса
     */
    public static function addAlias(string $alias, string $className): void
    {
        self::$aliases[$alias] = $className;
    }

    /**
     * PSR0
     *
     * @param string $className имя класса
     * @param string|null $directory папка
     * @return bool
     */
    protected static function PSR0(string $className, ?string $directory = null): bool
    {
        $classPath = '';

        if (($pos = strripos($className, '\\')) !== false) {
            $namespace = substr($className, 0, $pos);
            $className = substr($className, $pos + 1);
            $classPath = str_replace('\\', '/', $namespace) . '/';
        }

        $classPath .= str_replace('_', '/', $className) . '.php';

        $directories = ($directory === null)
            ? self::$directories
            : [$directory];

        foreach ($directories as $_directory) {
            if (file_exists($_directory . '/' . $classPath)) {
                include($_directory . '/' . $classPath);

                return true;
            }
        }

        return false;
    }

    /**
     * Load class
     *
     * @param string $className имя класса
     * @return bool
     */
    public static function loadClass(string $className): bool
    {

        $className = ltrim($className, '\\');

        if (isset(self::$aliases[$className])) {
            return class_alias(self::$aliases[$className], $className);
        }

        if (isset(self::$classes[$className]) && file_exists(self::$classes[$className])) {
            include self::$classes[$className];

            return true;
        }

        foreach (self::$namespaces as $namespace => $path) {
            if ((str_starts_with($className, $namespace)) && self::PSR0(substr($className, strlen($namespace)), $path)) {
                return true;
            }
        }

        return self::PSR0($className) || self::PSR0(strtolower($className));
    }

    /**
     * Register autoloader
     */
    public static function init(): void
    {
        spl_autoload_register('self::loadClass');
    }

}