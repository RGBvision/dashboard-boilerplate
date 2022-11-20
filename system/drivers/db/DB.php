<?php

use ParagonIE\EasyDB\EasyDB, \ParagonIE\EasyDB\EasyStatement, ParagonIE\EasyDB\EasyPlaceholder;

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
class DB
{

    /**
     * Class (self) instance
     *
     * @var DB|null
     */
    private static ?DB $instance = null;

    /**
     * DB engine
     *
     * @var string|mixed
     */
    static public string $db_engine = 'mysql'; // default

    /**
     * DB host
     *
     * @var string|mixed|null
     */
    static protected ?string $db_host;

    /**
     * DB user
     *
     * @var string|mixed|null
     */
    static protected ?string $db_user;

    /**
     * DB password
     *
     * @var string|mixed|null
     */
    static protected ?string $db_pass;

    /**
     * DB port
     *
     * @var string|mixed|null
     */
    static protected ?string $db_port;

    /**
     * DB socket
     *
     * @var string|null
     */
    static protected ?string $db_socket;

    /**
     * DB name
     *
     * @var string|mixed|null
     */
    static protected ?string $db_name;

    /**
     * PDO connection
     *
     * @var PDO
     */
    static public PDO $connection;

    /**
     * driver (EasyDB)
     *
     * @var EasyDB|null
     */
    static public ?EasyDB $driver = null;

    /**
     * Cache lifetime
     */
    private static int $cache_TTL = 0;

    /**
     * Constructor
     *
     * @param array $config
     */
    private function __construct(array $config)
    {
        self::$db_engine = $config['dbengine'];
        self::$db_host = $config['dbhost'];
        self::$db_user = $config['dbuser'];
        self::$db_pass = $config['dbpass'];
        self::$db_name = $config['dbname'];

        if (!isset($config['dbengine'])) {
            self::$db_engine = 'mysql';
        } else {
            self::$db_engine = ($config['dbengine'] ?? 'mysql');
        }

        if (!isset($config['dbport'])) {
            self::$db_port = null;
        } else {
            self::$db_port = ($config['dbport'] ?? null);
        }

        if (!isset($config['dbsock'])) {
            self::$db_socket = null;
        } else {
            self::$db_port = ($config['dbsock'] ?? null);
        }

        $connection_string = sprintf("%s:host=%s;port=%d;dbname=%s;user=%s;password=%s;charset=utf8;",
            (self::$db_engine === 'postgresql') ? 'pgsql' : 'mysql',
            self::$db_host,
            self::$db_port,
            self::$db_name,
            self::$db_user,
            self::$db_pass);

        try {
            self::$connection = @new PDO($connection_string);
            self::$driver = new ParagonIE\EasyDB\EasyDB(self::$connection, self::$db_engine);
        } catch (PDOException $pe) {
            self::shutDown(__METHOD__ . ': ' . $pe->getMessage());
        }

        self::$cache_TTL = (defined('SQL_CACHE') && SQL_CACHE) ? (defined('SQL_CACHE_LIFETIME') ? SQL_CACHE_LIFETIME : 0): 0;

    }


    /**
     * Get class instance
     *
     * @param array $config DB connection parameters
     * @return DB|null
     */
    public static function getInstance(array $config): ?DB
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }


    /**
     * Initialize
     *
     * @param array $config DB connection parameters
     */
    public static function init(array $config): void
    {
        self::getInstance($config);
    }


    /**
     * Shut down and display message if there is an error connecting to the database
     *
     * @param string $error
     */
    public static function shutDown(string $error = ''): never
    {
        ob_start();
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
        header('Retry-After: 120');

        die ($error);
    }

    /**
     * Perform a Query (alias of EasyDB->run)
     *
     * @param string $statement
     * @param mixed ...$params
     * @return array
     */
    public static function query(string $statement, ...$params): array
    {

        if ((self::$cache_TTL > 0) && (strtoupper(substr(trim($statement), 0, 6)) === 'SELECT')) {

            $cache_file = md5($statement) . md5(print_r([...$params], true));
            $cache_dir = DASHBOARD_DIR . TEMP_DIR . '/cache/sql/' . substr($cache_file, 0, 2) . '/' . substr($cache_file, 2, 2) . '/' . substr($cache_file, 4, 2) . '/';

            Dir::create($cache_dir);

            if (!(file_exists($cache_dir . $cache_file) && (time() - @filemtime($cache_dir . $cache_file) < self::$cache_TTL))) {
                $result = self::$driver->run($statement, ...$params);
                file_put_contents($cache_dir . $cache_file, serialize($result));
            } else {
                $result = unserialize(file_get_contents($cache_dir . $cache_file));
            }

            return $result;

        }

        return self::$driver->run($statement, ...$params);
    }

    /**
     * Wrap EasyDB methods
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::$driver, $name], $arguments);
    }

    /**
     * Get size of database
     *
     * @return int
     */
    public static function getSize(): int
    {

        $db_size = 0;

        if (self::$db_engine === 'mysql') {
            $q = self::query('SHOW TABLE STATUS');
            foreach ($q as $row) {
                $db_size += $row['Data_length'] + $row['Index_length'];
            }
        }

        if (self::$db_engine === 'postgresql') {
            $db_size = (int)self::$driver->cell('SELECT pg_database_size(?)', self::$db_name);
        }

        return $db_size;
    }

    /**
     * Creat database backup file
     *
     * @return bool
     */
    public static function backup(): bool // ToDo: refactor
    {

        $file = DASHBOARD_DIR . TEMP_DIR . '/backup/' . gmdate('Y-m-d_H-i-s') . '_backup.sql';

        if (self::$db_engine === 'mysql') {

            $command = sprintf(
                'mysqldump %s -h %s -u %s -p%s --routines --single-transaction -r %s',
                self::$db_name,
                self::$db_host,
                self::$db_user,
                self::$db_pass,
                $file
            );

            exec($command);

        }

        if (self::$db_engine === 'postgresql') {
            //--
        }

        $res = (File::exists($file) && (File::size($file) > 0));

        Log::log($res ? Log::INFO : Log::ERROR, 'System\DB', $res ? "Database backup created in $file" : 'Database backup error');

        return $res;
    }

    /**
     * Restore database from backup file
     *
     * @param string $file
     * @return bool
     */
    public static function restore(string $file): bool // ToDo: refactor
    {

        if ((File::exists($file) && (File::size($file) > 0))) {

            if (self::$db_engine === 'mysql') {

                $command = sprintf(
                    'mysql %s -h %s -u %s -p%s < %s',
                    self::$db_name,
                    self::$db_host,
                    self::$db_user,
                    self::$db_pass,
                    $file
                );

                exec($command);

            }

            if (self::$db_engine === 'postgresql') {
                //--
            }

            return true;

        }

        return false;
    }

    /**
     * Build WHERE part to perform basic search
     *
     * @param array $fields
     * @param string $search
     * @return string
     */
    public static function buildSearch(array $fields, string $search): string
    {
        $like = '';

        if ($search) {

            $chunks = explode(' ', preg_replace('/\s+/', ' ', trim($search)));

            $like_chunks = [];

            foreach ($chunks as $chunk) {

                $field_chunks = [];

                foreach ($fields as $field) {
                    $field_chunks[] = "UPPER($field) LIKE '%" . mb_strtoupper($chunk) . "%'";
                }

                $like_chunks[] = implode(' OR ', $field_chunks);
            }

            $like = implode(' AND ', $like_chunks);
        }

        return $like;
    }

    /**
     * Creates DB statement
     *
     * @return EasyStatement
     */
    public static function statement(): EasyStatement
    {
        return EasyStatement::open();
    }

    /**
     * Creates query placeholder
     *
     * @param string $mask
     * @param ...$values
     * @return EasyPlaceholder
     */
    public static function placeholder(string $mask, ...$values): EasyPlaceholder
    {
        return new EasyPlaceholder($mask, $values);
    }

}
