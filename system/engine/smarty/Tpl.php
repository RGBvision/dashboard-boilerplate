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

require_once(CP_DIR . '/system/libraries/Smarty/bootstrap.php');
$Smarty = new Smarty();

//--- SMARTY wrapper class
class Tpl extends Smarty
{

    public $sql_cache_dir;
    public $module_cache_dir;
    public $session_dir;
    public $cache_dir_root;
    private static $instance = null;

    public static function getInstance()
    {
        return self::$instance ?? self::$instance = new Tpl();
    }

    public function __construct($template_dir = '')
    {
        parent::__construct();

        //--- default templates directory
        $this->template_dir = $template_dir;

        //--- cache directory
        $this->cache_dir_root = CP_DIR . TEMP_DIR . '/cache';

        //--- compiled templates directory
        $this->compile_dir = CP_DIR . TEMP_DIR . '/cache/smarty';

        //--- templates cache directory
        $this->cache_dir = CP_DIR . TEMP_DIR . '/cache/tpl';

        //--- modules cache directory
        $this->module_cache_dir = CP_DIR . TEMP_DIR . '/cache/modules';

        //--- sessions directory
        $this->session_dir = CP_DIR . SESSION_DIR;

        //--- SQL cache directory
        $this->sql_cache_dir = CP_DIR . TEMP_DIR . '/cache/sql';

        $this->use_sub_dirs = SMARTY_USE_SUB_DIRS;

        //--- check if *.tpl files changed
        $this->compile_check = SMARTY_COMPILE_CHECK;

        //--- show SMARTY debug console
        $this->debugging = SMARTY_DEBUGGING;

        //--- don't show SMARTY errors
        self::muteExpectedErrors();

        $this->assign('CP_DIR', CP_DIR);
        $this->assign('ABS_PATH', ABS_PATH);
        $this->assign('DATE_FORMAT', DATE_FORMAT);
        $this->assign('TIME_FORMAT', TIME_FORMAT);
    }

    //--- change templates folder
    public function _redefine($file): string
    {
        if (!defined('THEME_FOLDER')) {
            return $file;
        }

        $r_tpl = str_replace(CP_DIR, CP_DIR . '/templates/' . THEME_FOLDER, $file);

        return (file_exists($r_tpl) && is_file($r_tpl)) ? $r_tpl : $file;
    }

    //--- redefine Smarty->_load method
    public function _load($file, $section = null)
    {
        parent::configLoad($this->_redefine($file), $section);
    }

    //--- redefine Smarty->_get method
    public function _get($varname = null, $search_parents = true)
    {
        return parent::getConfigVars($varname, $search_parents);
    }

    //--- redefine Smarty->isCached method
    public function isCached($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        return parent::isCached($this->_redefine($template), $cache_id, $compile_id, $parent);
    }

    //--- redefine Smarty->fetch method
    public function fetch($file_template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        return parent::fetch($this->_redefine($file_template), $cache_id, $compile_id, $parent);
    }

    //--- redefine Smarty->display method
    public function display($file_template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        return parent::display($this->_redefine($file_template), $cache_id, $compile_id, $parent);
    }
}