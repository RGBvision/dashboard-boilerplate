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

class Core
{
    protected static $instance = null;

    public static $environment;
    public static $cookie_domain;

    protected function __construct()
    {
        //--- Load DB config
        $config = array();
        include_once(CP_DIR . '/configs/db.config.php');

        //--- Load Config
        self::loadConfig();

        //--- Errors
        self::phpErrors();

        //--- Environment
        self::$environment = CP_ENVIRONMENT;

        //--- Headers
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'X-Engine: ' . APP_NAME,
            'X-Engine-Build: ' . APP_BUILD,
            'X-Engine-Copyright: 2017-' . date('Y') . ' (c) RGBvision',
            'X-Engine-Site: https://rgbvision.net'
        );

        //--- Force UTF-8
        function_exists('mb_language') AND mb_language('uni');
        function_exists('mb_regex_encoding') AND mb_regex_encoding('UTF-8');
        function_exists('mb_internal_encoding') AND mb_internal_encoding('UTF-8');

        //--- Set ABS_PATH
        self::absPath();

        //--- Auto-loader
        require_once CP_DIR . '/system/loader/loader.php';

        //--- Start Auto-loader
        Load::init();

        //--- Load classes with aliases for start engine
        Load::addClasses(array(
            //--- DB classes
            'DB' => CP_DIR . '/system/engine/db/DB.php',
            'DB_Result' => CP_DIR . '/system/engine/db/DB_Result.php',

            //--- Language
            'i18n' => CP_DIR . '/system/engine/language/i18n.php',

            //--- Sessions
            'Session' => CP_DIR . '/system/engine/sessions/Session.php',

            //--- Smarty
            'Tpl' => CP_DIR . '/system/engine/smarty/Tpl.php',
        ));

        //--- Load classes - Core
        Load::addDirectory(CP_DIR . '/system/core/');

        //--- Load classes - Helpers
        Load::addDirectory(CP_DIR . '/system/helpers/');

        //--- Load Functions
        Load::addFiles(CP_DIR . '/system/functions/');

        //--- Set HOST
        self::setHost();

        //--- Set cookie domain
        Cookie::setDomain();

        //--- MySQLi
        DB::init($config);
        //--- Set TimeZone
        $tz = (new DateTime('now', new DateTimeZone(TIMEZONE)))->format('P');
        DB::Query("SET time_zone = '$tz'");

        //--- Session start
        Session::init();

        //--- Settings
        Settings::init();

        //--- Define
        self::setDTFormat();

        //--- Set http headers
        Request::setHeaders($headers);

        //--- Set default language
        self::defaultLanguage();

        //--- Set locale
        Locales::set();

        //--- Hook trigger after initialize system
        Hooks::action('system_initialize');
    }

    //--- Define HOST - domain full path
    public static function setHost(): void
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            //--- Set all characters in $_SERVER ['HTTP_HOST'] to lowercase and check for prohibited characters in accordance with RFC 952 and RFC 2181
            $_SERVER['HTTP_HOST'] = strtolower($_SERVER['HTTP_HOST']);

            if (!preg_match('/^\[?(?:[a-z0-9-:\]_]+\.?)+$/', $_SERVER['HTTP_HOST'])) {
                //--- If $_SERVER['HTTP_HOST'] does not match specification - perhaps a hack attempt, so shutdown with 400 status.
                Response::setStatus(400);
                Request::shutDown();
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

    //--- Define ABS_PATH
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

    //--- SSL Check
    public static function isSSL(): bool
    {
        return (isset($_SERVER['HTTPS']) && ((strtolower($_SERVER['HTTPS']) === 'on') || ((int)$_SERVER['HTTPS'] === 1)))
            || (isset($_SERVER['SERVER_PORT']) && ((int)$_SERVER['SERVER_PORT'] === 443));
    }

    //--- Load Config
    protected static function loadConfig(): void
    {
        if (file_exists(CP_DIR . '/system/config.php')) {
            include_once(CP_DIR . '/system/config.php');
        } else {
            throw new \RuntimeException('The config file does not exist.');
        }
    }

    //--- Set default language
    public static function defaultLanguage(): void
    {
        // Check browser
        $browser_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $browser_lang = explode('-', $browser_lang);
        $browser_lang = $browser_lang[0];

        Session::setvar('accept_langs', array());

        $sql = DB::Query("
				SELECT
					*
				FROM
					" . PREFIX . "languages
				WHERE
					`active` = '1'
				ORDER BY
					`default` ASC
			", SYSTEM_CACHE_LIFETIME);

        while ($row = $sql->getObject()) {
            if (trim($row->key) > '') {
                $_SESSION['accept_langs'][trim($row->key)] = trim($row->alias);

                if (!@defined('DEFAULT_LANGUAGE') && (int)$row->default === 1) {
                    define('DEFAULT_LANGUAGE', trim($row->key));
                }
            }
        }

        $default_lang = (isset($_SESSION['accept_langs'][$browser_lang])
            ? $browser_lang
            : DEFAULT_LANGUAGE);

        $_SESSION['current_language'] = (Session::checkvar('current_language')
            ? $_SESSION['current_language']
            : $default_lang
        );

        unset($browser_lang, $default_lang, $sql);
    }

    //--- PHP debugging
    protected static function phpErrors(): void
    {
        if (PHP_DEBUGGING) {
            if (SELF_ERROR) {
                error_reporting(0);
                ini_set('display_errors', 0);
                require_once(CP_DIR . '/system/errors.php');
            } else {
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
            }
        } else {
            error_reporting(E_ERROR);
            ini_set('display_errors', 7);
        }
    }

    //--- Class instance
    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function setDTFormat(): void
    {
        define('DATE_FORMAT', Settings::get('main', 'date_format'));
        define('TIME_FORMAT', Settings::get('main', 'time_format'));
    }

    protected function __clone()
    {
        //---
    }
}