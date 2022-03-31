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
 * @version    2.7
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class ApiRouter
{

    static private string $method;
    static private ?array $params;
    static private string $controller;
    static private string $controller_file;
    protected static ?ApiRouter $instance = null;

    public static function init(string $controller, string $file, string $route): ?ApiRouter
    {
        if (!isset(self::$instance)) {
            self::$instance = new ApiRouter($controller, $file, $route);
        }

        return self::$instance;
    }

    public function __construct(string $controller, string $file, string $route)
    {
        self::$controller = $controller;
        self::$controller_file = $file;
        self::$method = strtolower($_SERVER['REQUEST_METHOD']) . '_' . str_replace('/', '_', trim($route, '/'));
        if (strtolower($_SERVER['REQUEST_METHOD']) === 'get') {
            self::$params = $_GET;
        } else {
            if (mb_strtolower(Request::header('Content-Type')) === 'application/json') {
                self::$params = Json::decode(file_get_contents("php://input"));
            } else {
                self::$params = $_POST;
            }
        }
    }

    public static function execute(): array
    {

        if (strpos(self::$method, '__') === 0) {
            return ['message' => i18n::_('router.error.magic_method')];
        }

        $file = self::$controller_file;
        $controller = self::$controller;

        if (is_file($file)) {
            Loader::addClass($controller, $file);
            $controller = new $controller();
        } else {
            return ['message' => i18n::_('router.error.controller')];
        }

        try {

            $reflection = new ReflectionClass($controller);

            if ($reflection->hasMethod(self::$method)) {

                $arguments = [];

                $reflectionMethod = new ReflectionMethod($controller, self::$method);

                foreach ($reflectionMethod->getParameters() as $parameter) {

                    $_parameter = is_array(self::$params) ? Arrays::get(self::$params, $parameter->name) : null;

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

                return call_user_func_array([$controller, self::$method], $arguments);
            }

        } catch (ReflectionException $e) {

            return ['message' => sprintf(i18n::_('router.error.runtime'), $e->getMessage())];

        }

        return ['message' => i18n::_('router.error.method')];
    }

    public static function response(array $data): void
    {
        if (!isset($data['success']) || !$data['success']) {
            Response::setStatus($data['response_code'] ?? 400);
        } else {
            Response::setStatus($data['response_code'] ?? 200);
        }

        Arrays::filterKeys($data, ['success'], true);
        Json::show($data, true);
    }
}
