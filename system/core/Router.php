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
 * @version    2.18
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Router
{
    static private string $id;
    static private string $route;
    static private string $method;
    static private array $aliases = [];
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
     * Get route ID
     *
     * @return string
     */
    public static function getId(): string
    {
        return self::$id;
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
     * ToDo:: refactor
     *
     * @param string $route
     * @return bool|Exception|mixed
     */
    public static function execute(string $route)
    {

        foreach (self::$aliases as $regex => $callback) {
            if ((preg_match('/^' . str_replace('/', '\/', $regex) . '$/', $route, $matches) === 1)) {

                if ($callback['module']) {

                    self::$id = $callback['module'];

                    if (!$controller = Loader::loadModule($callback['module'])) {
                        Request::redirect(ABS_PATH . 'errors/controller?controller=' . $callback['module']);
                        return false;
                    }

                    new $controller();

                    $function = $controller . '::' . $callback['function'];

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

            $file = DASHBOARD_DIR . '/app/modules/' . $_module . '/controller/Controller.php';

            if (is_file($file)) {
                self::$id = $_module;
                self::$route = $_module;
            }

            // Default method: index()
            self::$method = array_shift($parts) ?? 'index';

        }

        if (!isset(self::$route)) {
            Request::redirect(ABS_PATH . 'errors/controller?controller=' . trim($route, '/'));
            return false;
        }

        if (strpos(self::$method, '__') === 0) {
            return new \Exception('Error: Calls to magic methods are not allowed!');
        }

        if (!$controller = Loader::loadModule(preg_replace('/[^a-z0-9_]/i', '', self::$route))) {
            Request::redirect(ABS_PATH . 'errors/controller?controller=' . self::$route);
            return false;
        }

        $_controller = new $controller();

        try {

            $reflection = new ReflectionClass($_controller);

            if ($reflection->hasMethod(self::$method) && $reflection->getMethod(self::$method)->getNumberOfRequiredParameters() <= count($parts)) {

                // ToDo: add parameter type check

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

            Request::redirect(ABS_PATH . 'errors/controller?controller=' . self::$route);

        }

        Request::redirect(ABS_PATH . 'errors/method?method=' . self::$route . '/' . self::$method);

        return false;
    }

    /**
     * Get model
     *
     * @return Model
     */
    public static function model(): Model
    {
        $id = str_replace('_', '', self::$id);
        $model = 'Model' . $id;
        return new $model();
    }

    /**
     * Display message and stop execution
     *
     * @param bool $success
     * @param string $message
     * @param string $url redirect URL
     * @param array $arg
     */
    public static function response(bool $success, string $message, string $url = '', array $arg = []): void
    {

        $array = [
            'success' => $success,
            'message' => $message,
        ];

        if (!empty($arg)) {
            $array = array_merge($array, $arg);
        }

        if (Request::isAjax()) {
            if (!$success) {
                Response::setStatus(400);
            }
            Json::show($array, true);
        } else {
            Request::redirect($url);
        }

        Response::shutDown();
    }

}