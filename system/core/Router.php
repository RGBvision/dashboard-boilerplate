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

class Router
{

    private static string $module;
    private static string $method;
    private static array $aliases = [];
    protected static ?Router $instance = null;

    /**
     * Initialization
     *
     * @return Router|null
     */
    public static function init(): ?Router
    {
        if (!isset(self::$instance)) {
            self::$instance = new Router();
        }

        return self::$instance;
    }

    /**
     * Constructor
     */
    protected function __construct()
    {

    }

    /**
     * Get module
     *
     * @return string
     */
    public static function getModule(): string
    {
        return self::$module;
    }

    /**
     * Get model
     *
     * @return Model
     */
    public static function getModel(): Model
    {
        $model = snakeToPascalCase(self::$module) . 'Model';
        return new $model();
    }

    public static function addAlias(string $regex, ?string $module, string $function): void
    {
        self::$aliases[$regex] = [
            'module' => $module,
            'function' => $function,
        ];
    }

    /**
     * Execute router
     *
     * @param string $route
     * @return mixed
     */
    public static function execute(string $route): mixed
    {

        // Check if route matches aliases
        foreach (self::$aliases as $regex => $callback) {
            if ((preg_match('/^' . str_replace('/', '\/', $regex) . '$/', $route, $matches) === 1)) {

                if ($callback['module']) {

                    self::$module = $callback['module'];

                    if (!$controller = Loader::loadModule($callback['module'])) {
                        Request::redirect(ABS_PATH . 'errors/controller?controller=' . $callback['module']);
                        return false;
                    }

                    $function = [new $controller(), $callback['function']];

                } else {

                    $function = $callback['function'];

                }

                if (is_callable($function)) {
                    unset($matches[0]);
                    return call_user_func_array($function, $matches);
                }

                Request::redirect(ABS_PATH . 'errors/method?method=' . $function);
                return false;
            }
        }

        $parts = explode('/', trim($route, '/'));

        if (count($parts)) {

            $_module = preg_replace('/[^a-z0-9_]/i', '', array_shift($parts));

            $file = DASHBOARD_DIR . MODULES_DIR . DS . $_module . '/controller/Controller.php';

            if (is_file($file)) {
                self::$module = $_module;
            }

            // Default method: index()
            self::$method = array_shift($parts) ?? 'index';

        }

        if (!isset(self::$module)) {
            Request::redirect(ABS_PATH . 'errors/controller?controller=' . trim($route, '/'));
            return false;
        }

        if (str_starts_with(self::$method, '__')) {
            return new \Exception('Error: Calls to magic methods are not allowed!');
        }

        if (!$controller = Loader::loadModule(preg_replace('/[^a-z0-9_]/i', '', self::$module))) {
            Request::redirect(ABS_PATH . 'errors/controller?controller=' . self::$module);
            return false;
        }

        $_controller = new $controller();

        try {

            $reflection = new ReflectionClass($_controller);

            if ($reflection->hasMethod(self::$method) && $reflection->getMethod(self::$method)->getNumberOfRequiredParameters() <= count($parts)) {

                $arguments = [];

                $reflectionMethod = new ReflectionMethod($_controller, self::$method);

                foreach ($reflectionMethod->getParameters() as $parameter) {

                    $_parameter = array_shift($parts);

                    if ($_parameter !== null) {

                        if ($parameter->hasType()) {

                            $parameterType = $parameter->getType();
                            assert($parameterType instanceof ReflectionNamedType);

                            if ($parameterType->isBuiltin()) {

                                if (in_array($parameterType->getName(), ['int', 'integer'])) {
                                    $strictInt = filter_var($_parameter, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                                    if ($strictInt === null) {
                                        return ['message' => sprintf(i18n::_('router.error.parameter_type'), $parameter->name, $parameterType->getName())];
                                    }
                                    $_parameter = $strictInt;
                                }

                                if (in_array($parameterType->getName(), ['float', 'double'])) {
                                    $strictFloat = filter_var($_parameter, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
                                    if ($strictFloat === null) {
                                        return ['message' => sprintf(i18n::_('router.error.parameter_type'), $parameter->name, $parameterType->getName())];
                                    }
                                    $_parameter = $strictFloat;
                                }

                                if (in_array($parameterType->getName(), ['bool', 'boolean'])) {
                                    $strictBool = filter_var($_parameter, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                                    if ($strictBool === null) {
                                        return ['message' => sprintf(i18n::_('router.error.parameter_type'), $parameter->name, $parameterType->getName())];
                                    }
                                    $_parameter = $strictBool;
                                }

                                if (!is_array($_parameter) && $parameterType->getName() === 'array') {
                                    return ['message' => sprintf(i18n::_('router.error.parameter_type'), $parameter->name, $parameterType->getName())];
                                }

                                settype($_parameter, $parameterType->getName());
                            }
                        }

                        $arguments[$parameter->name] = $_parameter;


                    } else if (!$parameter->isOptional()) {

                        return ['message' => sprintf(i18n::_('router.error.required_parameter'), $parameter->name)];

                    }

                    unset($_parameter);

                }

                return call_user_func_array([$_controller, self::$method], $arguments);
            }

        } catch (ReflectionException $e) {

            Request::redirect(ABS_PATH . 'errors/controller?controller=' . self::$module);

        }

        Request::redirect(ABS_PATH . 'errors/method?method=' . self::$module . '/' . self::$method);

        return false;
    }

    /**
     * Display message and stop execution
     *
     * @param bool $success
     * @param string $message
     * @param string $url redirect URL
     * @param array $args
     * @param int|null $status response HTTP status
     */
    public static function response(bool $success, string $message, string $url = '', array $args = [], ?int $status = null): void
    {

        $_args = [
            'success' => $success,
            'message' => $message,
        ];

        if (!empty($args)) {
            $_args = array_merge($_args, $args);
        }

        if (!$success) {
            Response::setStatus($status ?? 400);
        }

        if (Request::isAjax()) {
            Json::output($_args, true);
        }

        if ($url) {
            Request::redirect($url);
        }

        Response::shutDown();
    }

}