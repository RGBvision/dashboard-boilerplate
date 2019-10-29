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

class Router
{
	static private $id;
	static private $route;
	static private $method = 'index';
	protected static $instance = null;

	public static function init($route): ?Router
    {
		if (!isset(self::$instance)) {
            self::$instance = new Router($route);
        }

		return self::$instance;
	}

	public static function reinit($route): Router
    {
		self::$instance = null;

		return new self($route);
	}

	public function __construct(string $route)
	{
		$parts = explode('/', preg_replace('#[^a-zA-Z0-9_/]#', '', $route));

		while ($parts) {
			$file = CP_DIR . '/modules/' . implode('/', $parts) . '/controller/Controller.php';

			if (is_file($file)) {
				self::$id = implode('', $parts);
				self::$route = implode('/', $parts);
				break;
			}
            self::$method = array_pop($parts);
        }
	}

	public static function getId(): string
    {
		return self::$id;
	}

	public static function execute(array $args = array())
	{
		if (strpos(self::$method, '__') === 0) {
            return new \Exception('Error: Calls to magic methods are not allowed!');
        }

		//--- Model

		$file = CP_DIR . '/modules/' . self::$route . '/model/Model.php';
		$model = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', self::$route);

		if (is_file($file)) {
			Load::addClass($model, $file);
		} else {
			Request::redirect('/errors/model?model=' . self::$method);
		}

		//--- Controller

		$file = CP_DIR . '/modules/' . self::$route . '/controller/Controller.php';
		$controller = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', self::$route);

		if (is_file($file)) {
			Load::addClass($controller, $file);
			$controller = new $controller();
		} else {
			Request::redirect('/errors/controller?controller=' . self::$route);
		}

		$reflection = new ReflectionClass($controller);

		if ($reflection->hasMethod(self::$method) && $reflection->getMethod(self::$method)->getNumberOfRequiredParameters() <= count($args)) {
			return call_user_func_array(array($controller, self::$method), $args);
		}

        Request::redirect('/errors/method?method=' . self::$route . '/' . self::$method);

        return false;
	}

	public static function model()
	{
		$id = self::$id;
		$model = 'Model' . $id;
		return new $model();
	}

	public static function response($type, $message, $url = '', $arg = array()): void
    {
		$Smarty = Tpl::getInstance();

		$array = array(
			'success' => $type === 'success',
			'header' => $Smarty->_get('message_header_' . $type),
			'message' => $message,
			'theme' => $type
		);

		if (!empty($arg)) {
            $array = array_merge($array, $arg);
        }

		if (Request::isAjax()) {
            Json::show($array, true);
        } else {
            Request::setHeader('Location: ' . $url);
        }
		Request::shutDown();
	}

	//--- demo
	public static function demo(): void
    {
		if (Core::$environment === 'demo') {
			$permission = Permission::perm('all_permissions');

			if (!$permission) {
				$Smarty = Tpl::getInstance();

				$json = array(
					'header' => $Smarty->_get('demonstration_header'),
					'message' => $Smarty->_get('demonstration_message'),
					'theme' => 'warning',
					'success' => false
				);

				if (Request::isAjax()) {
                    Json::show($json, true);
                } else {
                    Request::setHeader('Location: ./' . self::getId());
                }
				Request::shutDown();
			}
		}

	}
}