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
 * @version    3.0
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Core
{
    protected static $instance;

    public static $environment;
    public static $cookie_domain;

    /**
     *  Конструктор класса Core.
     */
    protected function __construct()
    {

        // Check if Timezone in HTTP header
        if ($_http_timezone = $_SERVER['HTTP_TIMEZONE']) {
            define('TIMEZONE', $_http_timezone);
        }

        // Загрузка конфигурации подключения к БД.
        $config = [];
        include_once(DASHBOARD_DIR . '/configs/db.config.php');

        // Загрузка конфигурации.
        self::loadConfig();

        // Обработка ошибок.
        self::phpErrors();

        // Тип окружения.
        self::$environment = CP_ENVIRONMENT;

        // Заголовки ответа.
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'X-Engine: ' . APP_NAME,
            'X-Engine-Build: ' . APP_BUILD,
            'X-Engine-Copyright: (c) RGBvision ' . date('Y'),
            'X-Engine-Site: https://rgbvision.net'
        ];

        // Установка кодировки UTF-8.
        function_exists('mb_language') and mb_language('uni');
        function_exists('mb_regex_encoding') and mb_regex_encoding('UTF-8');
        function_exists('mb_internal_encoding') and mb_internal_encoding('UTF-8');

        // Установка константы ABS_PATH.
        self::absPath();

        // Подключение автозагрузчика.
        require_once DASHBOARD_DIR . '/system/loader/Loader.php';

        // Инициализация автозагрузчика.
        Loader::init();

        // Загрузка EasyDB
        Loader::addDirectory(DASHBOARD_DIR . '/libraries/db/');

        // Загрузка SMARTY
        require_once(DASHBOARD_DIR . '/libraries/Smarty/bootstrap.php');

        // Загрузка классов движка системы.
        Loader::addClasses([

            // БД
            'DB' => DASHBOARD_DIR . '/system/engine/db/DB.php',

            // Язык
            'i18n' => DASHBOARD_DIR . '/system/engine/language/i18n.php',

            // Сессии
            'Session' => DASHBOARD_DIR . '/system/engine/sessions/Session.php',

            // Шаблонизатор
            'Template' => DASHBOARD_DIR . '/system/engine/template/Template.php',
        ]);

        // Загрузка классов ядра.
        Loader::addDirectory(DASHBOARD_DIR . '/system/core/');

        // Загрузка вспомогательных классов.
        Loader::addDirectory(DASHBOARD_DIR . '/system/helpers/');

        // Загрузка вспомогательных функций.
        Loader::addFiles(DASHBOARD_DIR . '/system/functions/');

        // Установка HOST.
        self::setHost();

        // Установка домена хранения cookies
        Cookie::setDomain();

        // Инициализация подключения к БД.
        DB::init($config);

        // Установка TimeZone.
        $tz = (new DateTime('now', new DateTimeZone(TIMEZONE)))->format('P');

        switch (DB::$db_engine) {
            case 'mysql':
                DB::query("SET time_zone = ?", $tz);
                break;
            case 'postgresql':
                DB::query("SET TIME ZONE '$tz'");
                break;

        }

        // Старт сессии.
        Session::init();

        // Инициализация установок.
        Settings::init();

        // Инициализация роутера.
        Router::init();

        // Загрузка алиасов роутера
        $routes = [];
        include_once(DASHBOARD_DIR . '/configs/routes.php');

        foreach ($routes as $alias) {
            Router::addAlias(ABS_PATH . $alias[0], $alias[1], $alias[2]);
        }

        // Установка формата вывода даты и времени.
        self::setDTFormat();

        // Установка HEADERS ответа.
        Response::setHeaders($headers);

        // Установка языка по умолчанию.
        self::defaultLanguage();

        // Установка настроек локали.
        Locales::set(Session::getvar('current_language') ?? DEFAULT_LANGUAGE);

        // Хук после инициализации ядра системы.
        Hooks::action('system_initialize');
    }


    /**
     * Установка хоста.
     */
    public static function setHost(): void
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            // Все символы в $_SERVER['HTTP_HOST'] переводятся в нижний регистр и проверяются на наличие недопустимых в соответствии со спецификацией RFC 952 и RFC 2181
            $_SERVER['HTTP_HOST'] = strtolower($_SERVER['HTTP_HOST']);

            if (!preg_match('/^\[?(?:[a-z0-9-:\]_]+\.?)+$/', $_SERVER['HTTP_HOST'])) {
                // Если $_SERVER['HTTP_HOST'] не соответствует спецификации, то это скорее всего попытка взлома. Значит нужно завершить выполнение со статусом 400.
                Response::setStatus(400);
                Response::shutDown();
            }
        } else {
            $_SERVER['HTTP_HOST'] = '';
        }

        $ssl = self::isSSL();

        $schema = ($ssl)
            ? 'https://'
            : 'http://';

        $host = str_replace(':' . $_SERVER['SERVER_PORT'], '', $_SERVER['HTTP_HOST']);

        $port = ((int)$_SERVER['SERVER_PORT'] === 80 || (int)$_SERVER['SERVER_PORT'] === 443 || $ssl)
            ? ''
            : ':' . $_SERVER['SERVER_PORT'];

        define('HOST', $schema . $host . $port);
    }


    /**
     * Установка константы ABS_PATH, которая содержит абсолютный путь с файлам системы.
     */
    protected static function absPath(): void
    {
        $abs_path = dirname(
            ((strpos($_SERVER['PHP_SELF'], $_SERVER['SCRIPT_NAME']) === false) && (PHP_SAPI === 'cgi'))
                ? $_SERVER['PHP_SELF']
                : $_SERVER['SCRIPT_NAME']);

        if (defined('CP_DIR')) {
            $abs_path = dirname($abs_path);
        }

        define('ABS_PATH', rtrim(str_replace("\\", "/", $abs_path), '/') . '/');
    }


    /**
     * Проверка защищенного (SSL) соединения.
     *
     * @return bool
     */
    public static function isSSL(): bool
    {
        return (isset($_SERVER['HTTPS']) && ((strtolower($_SERVER['HTTPS']) === 'on') || ((int)$_SERVER['HTTPS'] === 1)))
            || (isset($_SERVER['SERVER_PORT']) && ((int)$_SERVER['SERVER_PORT'] === 443));
    }


    /**
     * Загрузка конфигурации.
     */
    protected static function loadConfig(): void
    {
        if (file_exists(DASHBOARD_DIR . '/system/config.php')) {
            include_once(DASHBOARD_DIR . '/system/config.php');
        } else {
            throw new \RuntimeException('The config file does not exist.');
        }
    }

    public static function getBrowserLang(): string
    {

        $browser_lang = null;

        if ($_http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE']) {

            $prefLocales = array_reduce(
                explode(',', $_http_accept_language),
                static function ($res, $el) {
                    [$l, $q] = array_merge(explode(';q=', $el), [1]);
                    $res[$l] = (float)$q;
                    return $res;
                }, []);

            arsort($prefLocales);

            $locales = array_keys($prefLocales);

            $browser_lang = substr($locales[0], 0, 2);

        }

        return $browser_lang ?? DEFAULT_LANGUAGE;
    }


    /**
     * Установка языка по умолчанию.
     */
    public static function defaultLanguage(): void
    {

        // Check browser
        $browser_lang = self::getBrowserLang();

        Session::setvar('accept_langs', []);

        $sql = DB::query("
				SELECT
					*
				FROM
					languages l
				WHERE
					l.active = 1
				ORDER BY
					l.default DESC
			");

        foreach ($sql as $_row) {

            $row = Arrays::toObject($_row);

            if (trim($row->key)) {
                $_SESSION['accept_langs'][trim($row->key)] = trim($row->name);

                if (!@defined('DEFAULT_LANGUAGE') && (int)$row->default === 1) {
                    define('DEFAULT_LANGUAGE', trim($row->key));
                }
            }
        }

        $default_lang = isset($_SESSION['accept_langs'][$browser_lang]) ? $browser_lang : DEFAULT_LANGUAGE;

        Session::setvar('current_language', Session::getvar('current_language') ?? $default_lang);

        unset($browser_lang, $default_lang, $sql);
    }


    /**
     * Установка обработчика ошибок.
     */
    protected static function phpErrors(): void
    {
        if (PHP_DEBUGGING) {
            if (SELF_ERROR) {
                error_reporting(0);
                ini_set('display_errors', 0);
                require_once(DASHBOARD_DIR . '/system/errors.php');
            } else {
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
            }
        } else {
            error_reporting(E_ERROR);
            ini_set('display_errors', 7);
        }
    }


    /**
     * Установка формата вывода даты и времени.
     */
    public static function setDTFormat(): void
    {
        define('DATE_FORMAT', Settings::get('main', 'date_format'));
        define('TIME_FORMAT', Settings::get('main', 'time_format'));
    }


    /**
     * Инициализация инстанса класса.
     *
     * @return Core|null
     */
    public static function init(): ?Core
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }


    protected function __clone()
    {
        //---
    }
}