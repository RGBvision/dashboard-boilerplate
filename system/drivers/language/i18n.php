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

class i18n
{

    private static ?string $_path = null;
    private static ?string $_language = null;
    private static string $_fallbackLanguage = 'en';
    private static array $_translation = [];
    private static array $_missingTranslations = [];

    public static ?string $active_language = null;

    // Init i18n static class
    public static function init($path = null, $language = 'en'): void
    {
        self::$_language = $language;
        self::$_path = $path;

        self::loadTranslation();
    }

    // Change default language and fallback language
    public static function setLanguage($language, $fallback = null): void
    {
        self::$_language = $language;

        if (!empty($fallback)) {
            self::$_fallbackLanguage = $fallback;
        }

        self::loadTranslation();
    }

    // Get list of missing translations
    // ToDo: Log missing translations
    public static function getMissingTranslations(): array
    {
        return self::$_missingTranslations;
    }

    // Check if translated string is available
    public static function exist($key)
    {
        $return = self::_getKey($key);

        if ($return) {
            $return = true;
        }

        return $return;
    }

    // Get translation for given key
    public static function _(string $key): string
    {
        $return = self::_getKey($key);

        if (!$return) {
            self::$_missingTranslations[self::$_language] = array_unique([...self::$_missingTranslations[self::$_language], $key]);
            $return = $key;
        }

        return $return;
    }

    // Loads translation(s)
    private static function loadTranslation(): void
    {

        $_translation_files = self::$_path . '*.' . self::$_language . '.lang.json';
        $_fallback_files = self::$_path . '*.' . self::$_fallbackLanguage . '.lang.json';

        $dir = glob($_translation_files);

        if (count($dir) === 0) {

            $dir = glob($_fallback_files);

            if (count($dir) === 0) {
                throw new \RuntimeException('Translation file not found');
            } else {
                self::$active_language = self::$_fallbackLanguage;
            }

        } else {
            self::$active_language = self::$_language;
        }

        $translations = [];

        foreach ($dir as $file) {
            $translation = file_get_contents($file);
            $translation = json_decode($translation, true);

            if ($translation === null) {
                throw new \RuntimeException('Invalid json ' . $file);
            }

            $translations[] = $translation;
        }

        self::$_translation = array_merge(self::$_translation, ...$translations);
        unset($translations);

    }

    // Get translation for given key
    private static function _getKey(string $key)
    {
        return Arrays::get(self::$_translation, $key);
    }
}