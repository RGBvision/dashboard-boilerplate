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
 * @version    2.1
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

class Session
{
    public static function init(): void
    {
        self::storage();
        self::start();
    }

    public static function storage(): void
    {
        require_once DASHBOARD_DIR . '/system/drivers/sessions/' . SESSION_SAVE_HANDLER . '.php';
        Sessions::init();
    }

    public static function start(): bool
    {
        if (!session_id()) {
            Sessions::init();
            session_start();
        }

        return true;
    }

    public static function destroy(): void
    {
        if (session_id()) {
            session_unset();

            session_destroy();

            $_SESSION = [];
        }
    }

    public static function getid(): string
    {
        if (!session_id()) {
            self::start();
        }

        return session_id();
    }

    public static function getvar(string $path)
    {
        if (!session_id()) {
            self::start();
        }

        return Arrays::get($_SESSION, $path);
    }

    public static function setvar(string $path, $value): void
    {
        if (!session_id()) {
            self::start();
        }

        Arrays::set($_SESSION, $path, $value);
    }

    public static function checkvar(string $path): bool
    {
        if (!session_id()) {
            self::start();
        }

        return (Arrays::get($_SESSION, $path) !== null);
    }

    public static function delvar(...$arguments): void
    {
        foreach ($arguments as $argument) {
            Arrays::delete($_SESSION, $argument);
        }
    }
}