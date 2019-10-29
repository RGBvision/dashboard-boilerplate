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

class Debug
{
    protected static $time = array();
    protected static $memory = array();

    protected function __construct()
    {
        //
    }

    /**
     * Функция для вывода переменной (для отладки)
     *
     * @param mixed $var любая переменная
     * @param bool $exit
     */
    public static function _echo($var, $exit = false)
    {
        $backtrace = debug_backtrace();

        $backtrace = $backtrace[0];

        if (preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'])) {
            $file = preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'], $match);
            $file = $match[1];
        }

        $fh = fopen((isset($file) ? $file : $backtrace['file']), 'r');

        $line = 0;

        while (++$line <= $backtrace['line']) {
            $code = fgets($fh);
        }

        fclose($fh);

        preg_match('/' . __FUNCTION__ . '\s*\((.*)\)\s*;/u', $code, $name);

        ob_start();

        var_dump($var);

        $var_dump = ob_get_contents();

        $var_dump = preg_replace('/=>(\s+|\s$)/', ' => ', $var_dump);

        $var_dump = htmlspecialchars($var_dump);

        $var_dump = preg_replace('/(=&gt;)/', '<span style="color: #FF8C00;">$1</span>', $var_dump);

        ob_end_clean();

        $fn_name = !empty($name)
            ? $name[1]
            : 'EVAL';

        $var_dump = '<div style="border: 2px solid #3078bf; margin: 15px auto; max-width: 1140px; font-size: 12px; font-family: Lucida Console, Courier, monospace; box-shadow: 0 3px 7px rgba(0,0,0,.25)">
<div style="background:#3078bf; color: #fff; margin: 0; padding: 5px 15px;">
var_dump(<strong>' . trim($fn_name) . '</strong>) - ' . self::_trace() .
'</div>
<pre style="background:#fff; color: #000; margin: 0; padding: 15px; white-space: pre-wrap;">'
. $var_dump .
'</pre>
</div>';

        echo $var_dump;

        if ($exit) {
            exit;
        }
    }


    /**
     * Функция для вывода переменной (для отладки)
     *
     * @param mixed $var любая переменная
     * @param bool $exit
     */
    public static function _print($var, $exit = false)
    {
        $backtrace = debug_backtrace();

        $backtrace = $backtrace[0];

        if (preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'], $match)) {
            $file = $match[1];
        }

        $fh = fopen((isset($file) ? $file : $backtrace['file']), 'r');

        $line = 0;

        while (++$line <= $backtrace['line']) {
            $code = fgets($fh);
        }

        fclose($fh);

        preg_match('/' . __FUNCTION__ . '\s*\((.*)\)\s*;/u', $code, $name);

        ob_start();

        print_r($var);

        $var_dump = htmlspecialchars(ob_get_contents());

        $var_dump = preg_replace('/(=&gt;)/', '<span style="color: #FF8C00;">$1</span>', $var_dump);

        ob_end_clean();

        $fn_name = !empty($name)
            ? $name[1]
            : 'EVAL';

        $var_dump = '
				<div style="border: 1px solid #365899; margin: 5px 0; font-size: 11px; font-family: Consolas, Verdana, Arial; border-radius: 3px;">
					<div style="background:#4e5665; color: #fff; margin: 0; padding: 5px;">
						print_r(<strong>' . trim($fn_name) . '</strong>) - ' . self::_trace() .
            '</div>
					<pre style="background:#f0f0f0; color: #000; margin: 0; padding: 5px; border: 0; font-size: 11px; font-family: Consolas, Verdana, Arial;">'
            . $var_dump .
            '</pre>
				</div>
			';

        echo $var_dump;

        if ($exit) {
            exit;
        }
    }


    /**
     * Функция для вывода переменной (для экспорта)
     *
     * @param mixed $var любая переменная
     * @param bool $exit
     */
    public static function _exp($var, $exit = false)
    {
        $backtrace = debug_backtrace();

        $backtrace = $backtrace[0];

        if (preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'])) {
            $file = preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'], $match);
            $file = $match[1];
        }

        $fh = fopen((isset($file) ? $file : $backtrace['file']), 'r');

        $line = 0;

        while (++$line <= $backtrace['line']) {
            $code = fgets($fh);
        }

        fclose($fh);

        preg_match('/' . __FUNCTION__ . '\s*\((.*)\)\s*;/u', $code, $name);

        ob_start();

        var_export($var);

        $fn_name = !empty($name)
            ? $name[1]
            : 'EVAL';

        $var_export = htmlspecialchars(ob_get_contents());

        $var_export = preg_replace('/(=&gt;)/', '<span style="color: #FF8C00;">$1</span>', $var_export);

        ob_end_clean();

        $var_dump = '
				<div style="border: 1px solid #bbb; margin: 5px 0; font-size: 11px; font-family: Consolas, Verdana, Arial; border-radius: 3px;">
					<div style="background:#ccc; color: #000; margin: 0; padding: 5px;">var_export(<strong>'
            . trim($fn_name) . '</strong>) - ' . self::_trace() .
            '</div>
					<pre style="background:#f0f0f0; color: #000; margin: 0; padding: 5px; border: 0; font-size: 11px; font-family: Consolas, Verdana, Arial;">'
            . $var_export .
            '</pre>
				</div>
			';

        echo $var_dump;

        if ($exit)
            exit;
    }


    /**
     * Функция для вывода переменной (для отладки)
     *
     * @param mixed $var любая переменная
     * @param bool $exit true - остановливает дальнейшее выполнение скрипта, false - продолжает выполнять скрипт
     */
    public static function _html($var, $exit = false)
    {
        $backtrace = debug_backtrace();

        $backtrace = $backtrace[0];

        if (preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'])) {
            $file = preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'], $match);
            $file = $match[1];
        }

        $fh = fopen((isset($file) ? $file : $backtrace['file']), 'r');

        $line = 0;

        while (++$line <= $backtrace['line']) {
            $code = fgets($fh);
        }

        fclose($fh);

        preg_match('/' . __FUNCTION__ . '\s*\((.*)\)\s*;/u', $code, $name);

        ob_start();

        var_export($var);

        $fn_name = !empty($name)
            ? $name[1]
            : 'EVAL';

        $var_dump = ob_get_clean();

        $var_dump = '
				<div style="border: 1px solid #bbb; margin: 5px 0; font-size: 11px; font-family: Consolas, Verdana, Arial; border-radius: 3px;">
					<div style="background:#ccc; color: #000; margin: 0; padding: 5px;">var_export(<strong>'
            . trim($fn_name) . '</strong>) - ' . self::_trace() .
            '</div>
					<pre style="background:#f0f0f0; color: #000; margin: 0; padding: 5px; border: 0; font-size: 11px; font-family: Consolas, Verdana, Arial;">'
            . htmlentities($var_dump, ENT_QUOTES) .
            '</pre>
				</div>
			';

        echo $var_dump;

        if ($exit)
            exit;
    }


    /**
     * Функция для записи переменной в файл (для отладки)
     *
     * @param mixed $var любая переменная
     * @param bool $append
     * @param bool $exit true - остановливает дальнейшее выполнение скрипта, false - продолжает выполнять скрипт
     */
    public static function _dump($var, $append = true, $exit = false)
    {
        $backtrace = debug_backtrace();

        $backtrace = $backtrace[0];

        if (preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'])) {
            $file = preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'], $match);
            $file = $match[1];
        }

        $fh = fopen((isset($file) ? $file : $backtrace['file']), 'r');

        $line = 0;

        while (++$line <= $backtrace['line']) {
            $code = fgets($fh);
        }

        fclose($fh);

        preg_match('/' . __FUNCTION__ . '\s*\((.*)\)\s*;/u', $code, $name);

        ob_start();

        var_dump($var);

        $var_dump = ob_get_contents();

        $var_dump = preg_replace('/=>(\s+|\s$)/', ' => ', $var_dump);

        $var_dump = htmlspecialchars($var_dump);

        $var_dump = preg_replace('/(=&gt; )+([a-zA-Z]+\(\d+\))/', '$1<span style="color: #FF8C00;">$2</span>', $var_dump);

        ob_end_clean();

        $fn_name = !empty($name)
            ? $name[1]
            : 'EVAL';

        $var_dump = '
				<div style="border: 1px solid #2a5885; margin: 5px 0; font-size: 11px; font-family: Consolas, Verdana, Arial; border-radius: 3px;">
					<div style="background:#43648c; color: #fff; margin: 0; padding: 5px;">
						<strong>' . date("j F Y, H:i:s") . '</strong> - var_dump(<strong>' . trim($fn_name) . '</strong>) - ' . self::_trace() .
            '</div>
					<pre style="background:#f5f5f5; color: #000; margin: 0; padding: 5px; border: 0; font-size: 11px; font-family: Consolas, Verdana, Arial;">'
            . $var_dump .
            '</pre>
				</div>
			';

        if ($append) {
            file_put_contents(CP_DIR . '/debug.html', $var_dump, FILE_APPEND);
        } else {
            file_put_contents(CP_DIR . '/debug.html', $var_dump);
        }

        if ($exit) {
            exit;
        }
    }


    /**
     * Функция для трейсинга дебаггера
     *
     * @param
     * @return string
     */
    public static function _trace()
    {
        $bt = debug_backtrace();

        $trace = $bt[1];

        $line = $trace['line'];

        $file = $trace['file'];

        $function = $trace['function'];

        $class = (isset($bt[2]['class'])
            ? $bt[2]['class']
            : 'None');

        if (isset($bt[2]['class'])) {
            $type = $bt[2]['type'];
        } else {
            $type = 'Unknow';
        }

        $function = isset($bt[2]['function'])
            ? $bt[2]['function']
            : 'None';

        return sprintf('Class: <strong>%s</strong> | Type: <strong>%s</strong> | Function: <strong>%s</strong> | File: <strong>%s</strong> line <strong>%s</strong>', $class, $type, $function, $file, $line);
    }


    public static function _errorSql(string $header, string $body, string $caller, bool $exit = false): void
    {

        self::_echo(preg_replace('/(\s)+/s', ' ', $header));
        self::_echo($body);
        self::_echo(DB::$_query_list);
        self::_echo($caller);

        if ($exit) {
            exit;
        }
    }


    /**
     * Функция отвечает за начало таймера
     *
     * @param string $name любая переменная (ключ массива)
     */
    public static function startTime($name = '')
    {
        self::$time[$name] = microtime(true);
    }


    /**
     * Функция отвечает за окончание таймера
     *
     * @param string $name любая переменная (ключ массива)
     * @return string
     */
    public static function endTime($name = '')
    {
        if (isset(Debug::$time[$name])) {
            return sprintf("%01.4f", microtime(true) - Debug::$time[$name]);
        }
    }


    /**
     * Функция отвечает за начало подсчета используеой памяти
     *
     * @param string $name любая переменная (ключ массива)
     */
    public static function startMemory($name = '')
    {
        Debug::$memory[$name] = memory_get_usage();
    }


    /**
     * Функция отвечает за окончание подсчета используемой памяти
     *
     * @param string $name любая переменная (ключ массива)
     * @return string
     */
    public static function endMemory($name = '')
    {
        if (isset(Debug::$memory[$name])) {
            return Number::numFormat((memory_get_usage() - Debug::$memory[$name]) / 1024, 0, ',', '.') . ' Kb';
        }
    }


    /**
     * Вывод статистики
     *
     * @param bool $t
     * @param bool $m
     * @param bool $q
     * @return string
     */
    public static function getStats($t = false, $m = false, $q = false)
    {
        $stat = '<div class="alert alert-info bg-dark alert-dismissible mb-0 rounded-0 fixed-bottom w-100 small fade show" role="alert">';
        $stat .= '<div class="row justify-content-between text-center">';

        if ($t) {
            $search = array(
                '[msg1]',
                '[data]',
                '[msg2]'
            );

            $replace = array(
                i18n::_('statistics.time_generate'),
                Number::numFormat(Number::microtimeDiff(START_CP, microtime()), 6, ',', ' '),
                i18n::_('statistics.seconds')
            );

            $stat .= str_replace($search, $replace, '<div class="col"><i class="sli small sli-time-timer-full-2"></i> [msg1] [data] [msg2]</div>');
        }

        if ($m) {
            $search = array(
                '[msg1]',
                '[data1]',
                '[msg2]',
                '[data2]'
            );

            $replace = array(
                i18n::_('statistics.memory_usage'),
                Number::formatSize(memory_get_usage() - START_MEMORY),
                i18n::_('statistics.memory_peak'),
                Number::formatSize(memory_get_peak_usage())
            );

            $stat .= str_replace($search, $replace, '<div class="col"><i class="sli small sli-computers-computer-chip"></i> [msg1] [data1] | [msg2] [data2]</div>');
        }

        if ($q && SQL_PROFILING) {
            $search = array(
                '[msg1]',
                '[msg2]',
                '[msg3]',
                '[data1]',
                '[data2]'
            );

            $q_list = DB::getStatistics('list');
            $q_pretty = '';

            foreach ($q_list as $_q) {
                $q_pretty .= '<small>' . $_q['time'] . '</small>' . $_q['query'];
            }

            $replace = array(
                i18n::_('statistics.count_queries'),
                i18n::_('statistics.count_time'),
                i18n::_('statistics.seconds'),
                DB::getStatistics('count') / 2,
                DB::getStatistics('time')
            );

            $stat .= str_replace($search, $replace, '<div class="col"><i class="sli small sli-server-server-3"></i> [msg1] [data1] [msg2] [data2] [msg3]
<button class="btn btn-primary btn-xs ml-3" type="button" data-toggle="modal" data-target="#sqlDataModal"><i class="sli small sli-server-server-view-1"></i></button></div>');
        }

        $stat .= '</div>';
        $stat .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        $stat .= '</div>';
        if ($q && SQL_PROFILING) {
            $stat .= '<div id="sqlDataModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="sqlDataModalLabel" aria-modal="true">
<div class="modal-dialog modal-xl" role="document"> <div class="modal-content">
<div class="modal-header">
<h4 class="modal-title" id="sqlDataModalLabel">SQL queries list</h4>
</div>
<div class="modal-body">' . $q_pretty . '</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
</div>
</div>
</div>
</div>';
        }

        return $stat;
    }
}