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

class Settings
{
    protected static $settings = null;
    protected static $instance = null;

    protected function __construct()
    {
        $sql = "
				SELECT
					*
				FROM
					" . PREFIX . "settings
			";

        $query = DB::Query($sql, SYSTEM_CACHE_LIFETIME);

        $settings = array();

        while ($row = $query->getAssoc()) {
            $settings[$row['type']][$row['key']] = $row['array']
                ? unserialize($row['value'], ['allowed_classes' => false])
                : $row['value'];
        }

        self::$settings = $settings;
    }

    public static function init(): ?Settings
    {
        if (!isset(self::$instance)) {
            self::$instance = new Settings();
        }

        return self::$instance;
    }

    public static function get($type = '', $key = '')
    {
        if ($key === '' || $type === '') {
            return self::$settings;
        }

        return self::$settings[$type][$key] ?? null;
    }

    public static function loadUserSettings(): void
    {
        $sql = "
				SELECT
					settings
				FROM
					" . PREFIX . "users
				WHERE
					user_id = '" . UID . "'
				LIMIT 1
			";

        $user = DB::Query($sql)->getObject();

        if ($user) {
            $_SESSION['user_settings'] = Arrays::safe_unserialize($user->settings);
        }

    }

    public static function saveUserSettings(): void
    {
        if (!empty($_SESSION['user_settings'])) {
            DB::Query("
				UPDATE
					" . PREFIX . "users
				SET
					settings = '" . Arrays::safe_serialize($_SESSION['user_settings']) . "'
				WHERE
					user_id  = '" . UID . "'
			");
        }
    }

}