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

class Template extends Smarty
{

    public $sql_cache_dir;
    public $module_cache_dir;
    public $session_dir;
    public $cache_dir_root;
    private static $instance = null;

    public static function getInstance(): Template
    {
        return self::$instance ?? self::$instance = new Template();
    }

    public function __construct($template_dir = '')
    {
        parent::__construct();

        // default templates directory
        $this->template_dir = $template_dir;

        // cache directory
        $this->cache_dir_root = DASHBOARD_DIR . TEMP_DIR . '/cache';

        // compiled templates directory
        $this->compile_dir = DASHBOARD_DIR . TEMP_DIR . '/cache/smarty';

        // templates cache directory
        $this->cache_dir = DASHBOARD_DIR . TEMP_DIR . '/cache/tpl';

        // modules cache directory
        $this->module_cache_dir = DASHBOARD_DIR . TEMP_DIR . '/cache/modules';

        // sessions directory
        $this->session_dir = DASHBOARD_DIR . SESSION_DIR;

        // SQL cache directory
        $this->sql_cache_dir = DASHBOARD_DIR . TEMP_DIR . '/cache/sql';

        $this->use_sub_dirs = SMARTY_USE_SUB_DIRS;

        // check if *.tpl files changed
        $this->compile_check = SMARTY_COMPILE_CHECK;

        // show SMARTY debug console
        $this->debugging = SMARTY_DEBUGGING;

        // don't show SMARTY errors
        self::muteExpectedErrors();

        $this->assign('CP_DIR', DASHBOARD_DIR);
        $this->assign('ABS_PATH', ABS_PATH);
        $this->assign('DATE_FORMAT', DATE_FORMAT);
        $this->assign('TIME_FORMAT', TIME_FORMAT);
    }

    // change templates folder
    public function _redefine($file): string
    {
        if (!defined('THEME_FOLDER')) {
            return $file;
        }

        $r_tpl = str_replace(DASHBOARD_DIR, DASHBOARD_DIR . '/templates/' . THEME_FOLDER, $file);

        return (file_exists($r_tpl) && is_file($r_tpl)) ? $r_tpl : $file;
    }

    // redefine Smarty->_load method
    public function _load($file, $section = null)
    {
        parent::configLoad($this->_redefine($file), $section);
    }

    // redefine Smarty->_get method
    public function _get($variable_name = null, $search_parents = true)
    {
        return parent::getConfigVars($variable_name, $search_parents);
    }

    // redefine Smarty->isCached method
    public function isCached($template = null, $cache_id = null, $compile_id = null, $parent = null): bool
    {
        return parent::isCached($this->_redefine($template), $cache_id, $compile_id, $parent);
    }

    // redefine Smarty->fetch method

    /**
     * @param null $template
     * @param null $cache_id
     * @param null $compile_id
     * @param null $parent
     * @return false|string|null
     */
    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        try {
            return parent::fetch($this->_redefine($template), $cache_id, $compile_id, $parent);
        } catch (Exception $e) {
            return null;
        }
    }

    // redefine Smarty->display method
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null): void
    {
        parent::display($this->_redefine($template), $cache_id, $compile_id, $parent);
    }
}