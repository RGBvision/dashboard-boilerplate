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
     * Check if valid Timezone
     *
     * @param $timezoneId
     * @return bool
     */
    private static function isValidTimezoneId($timezoneId): bool
    {
        try {
            new DateTimeZone($timezoneId);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     *  Constructor
     */
    protected function __construct()
    {

        // Check if Timezone in HTTP header
        if (($_http_timezone = $_SERVER['HTTP_TIMEZONE']) && self::isValidTimezoneId($_http_timezone)) {
            define('TIMEZONE', $_http_timezone);
        }

        // Check if Timezone in cookie
        if (!defined('TIMEZONE') && ($_browser_timezone = $_COOKIE['browser_timezone']) && self::isValidTimezoneId($_browser_timezone)) {
            define('TIMEZONE', $_browser_timezone);
        }

        // DB connection configuration
        $config = [];
        include_once(DASHBOARD_DIR . '/configs/db.config.php');

        // System configuration
        self::loadConfig();

        // Errors handler
        self::phpErrors();

        // Environment type
        self::$environment = SYSTEM_ENVIRONMENT;

        // HTTP headers
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'X-Engine: ' . APP_NAME,
            'X-Engine-Build: ' . APP_BUILD,
            'X-Engine-Copyright: (c) RGBvision ' . date('Y'),
            'X-Engine-Site: https://rgbvision.net',
        ];

        // UTF-8 encoding
        function_exists('mb_language') and mb_language('uni');
        function_exists('mb_regex_encoding') and mb_regex_encoding('UTF-8');
        function_exists('mb_internal_encoding') and mb_internal_encoding('UTF-8');

        // Define ABS_PATH.
        self::absPath();

        // Loader
        require_once DASHBOARD_DIR . '/system/loader/Loader.php';

        // Loader initialization
        Loader::init();

        // EasyDB
        Loader::addDirectory(DASHBOARD_DIR . '/libraries/db/');

        // SMARTY
        require_once(DASHBOARD_DIR . '/libraries/Smarty/bootstrap.php');

        // Drivers
        Loader::addClasses([
            'DB' => DASHBOARD_DIR . '/system/drivers/db/DB.php',
            'i18n' => DASHBOARD_DIR . '/system/drivers/language/i18n.php',
            'Session' => DASHBOARD_DIR . '/system/drivers/sessions/Session.php',
            'Template' => DASHBOARD_DIR . '/system/drivers/template/Template.php',
        ]);

        // Core classes
        Loader::addDirectory(DASHBOARD_DIR . '/system/core/');

        // Common classes
        Loader::addDirectory(DASHBOARD_DIR . '/system/common/');

        // Functions
        Loader::addFiles(DASHBOARD_DIR . '/system/functions/');

        // Define HOST
        self::setHost();

        // Set cookie domain
        Cookie::setDomain();

        // DB initialization
        DB::init($config);

        // DB TimeZone
        $tz = (new DateTime('now', new DateTimeZone(TIMEZONE)))->format('P');

        switch (DB::$db_engine) {
            case 'mysql':
                DB::query("SET time_zone = ?", $tz);
                break;
            case 'postgresql':
                DB::query("SET TIME ZONE '$tz'");
                break;

        }

        // Session start
        Session::init();

        // Load system settings
        Settings::init();

        // Router initialization
        Router::init();

        // Router aliases
        $routes = [];
        include_once(DASHBOARD_DIR . '/configs/routes.php');

        foreach ($routes as $alias) {
            Router::addAlias(ABS_PATH . $alias[0], $alias[1], $alias[2]);
        }

        // Response HEADERS
        Response::setHeaders($headers);

        // Set default language
        self::defaultLanguage();

        // Locale settings
        Locales::set(Session::getvar('current_language') ?? DEFAULT_LANGUAGE);

        // Hook after system initialize
        Hooks::action('system_initialize');
    }


    /**
     * Define HOST
     */
    public static function setHost(): void
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            // According to RFC 952 and RFC 2181 all characters in $_SERVER['HTTP_HOST'] are converted to lowercase and checked for invalid characters
            $_SERVER['HTTP_HOST'] = strtolower($_SERVER['HTTP_HOST']);

            if (!preg_match('/^\[?(?:[a-z0-9-:\]_]+\.?)+$/', $_SERVER['HTTP_HOST'])) {
                // Probably a hack attempt if $_SERVER['HTTP_HOST'] is out of specification. So stop execution with 400 status.
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
     * Define ABS_PATH with system files absolute path.
     */
    protected static function absPath(): void
    {
        $abs_path = dirname(
            ((strpos($_SERVER['PHP_SELF'], $_SERVER['SCRIPT_NAME']) === false) && (PHP_SAPI === 'cgi'))
                ? $_SERVER['PHP_SELF']
                : $_SERVER['SCRIPT_NAME']);

        if (defined('DASHBOARD_DIR')) {
            $abs_path = dirname($abs_path);
        }

        define('ABS_PATH', rtrim(str_replace("\\", "/", $abs_path), '/') . '/');
    }


    /**
     * Check if SSL connection
     *
     * @return bool
     */
    public static function isSSL(): bool
    {
        return (isset($_SERVER['HTTPS']) && ((strtolower($_SERVER['HTTPS']) === 'on') || ((int)$_SERVER['HTTPS'] === 1)))
            || (isset($_SERVER['SERVER_PORT']) && ((int)$_SERVER['SERVER_PORT'] === 443));
    }


    /**
     * Load system configuration
     */
    protected static function loadConfig(): void
    {
        if (file_exists(DASHBOARD_DIR . '/system/config.php')) {
            include_once(DASHBOARD_DIR . '/system/config.php');
        } else {
            throw new \RuntimeException('The config file does not exist.');
        }
    }

    /**
     * Get language by browser locale
     *
     * @return string
     */
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
     * Set default language
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
     * Set error handler
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
     * Instantiate class
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