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
 * @version    1.1
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 1.0
 */

// ToDo: this needs to be completely refactored

function displayError($error, $errlvl, $color)
{
    echo '<div style="border: 2px solid ' . $color . '; margin: 15px auto; max-width: 1140px; font-size: 12px; font-family: Lucida Console, Courier, monospace; box-shadow: 0 3px 7px rgba(0,0,0,.25)">
        <div style="background: ' . $color . '; color: #fff; margin: 0; padding: 5px 15px;"><h3>' . $errlvl . '</h3></div>
        <div style="background:#fff; color: #000; margin: 0; padding: 15px;">' . $error . '</div>
        </div>';
}

function errorHandler($error_level, $error_message, $error_file, $error_line)
{
	$error = sprintf('
		Lvl: <strong>%s</strong><br>Message: <strong>%s</strong><br>File: <strong>%s</strong><br>Line: <strong>%s</strong>
		', $error_level, nl2br($error_message), $error_file, $error_line);

	switch ($error_level) {
		case E_ERROR:
		case E_CORE_ERROR:
		case E_COMPILE_ERROR:
		case E_PARSE:
			$color = '#bf3430';
			displayError($error, 'Fatal error', $color);
			break;
		case E_USER_ERROR:
		case E_RECOVERABLE_ERROR:
			$color = '#bf3430';
			displayError($error, 'Error', $color);
			break;
		case E_WARNING:
		case E_CORE_WARNING:
		case E_COMPILE_WARNING:
		case E_USER_WARNING:
        case E_USER_DEPRECATED:
			$color = '#bf9230';
			displayError($error, 'Warning', $color);
			break;
		case E_NOTICE:
		case E_USER_NOTICE:
			$color = '#3078bf';
			displayError($error, 'Info', $color);
			break;
		case E_STRICT:
			$color = '#333333';
			displayError($error, 'Debug', $color);
			break;
		default:
			$color = '#bf9230';
			displayError($error, 'Warning', $color);
	}
}

function shutdownHandler()
{
	$last_error = error_get_last();

    $error_types = array(
        1 => 'E_ERROR',
        4 => 'E_PARSE',
        16 => 'E_CORE_ERROR',
        32 => 'E_CORE_WARNING',
        64 => 'E_COMPILE_ERROR',
        128 => 'E_COMPILE_WARNING',
        256 => 'E_USER_ERROR',
        4096 => 'E_RECOVERABLE_ERROR'
    );

	switch ($last_error['type']) {
		case E_ERROR:
        case E_PARSE:
		case E_CORE_ERROR:
        case E_CORE_WARNING:
		case E_COMPILE_ERROR:
        case E_COMPILE_WARNING:
		case E_USER_ERROR:
		case E_RECOVERABLE_ERROR:
			$color = '#bf3430';
			$error = sprintf('
				<strong>Error type:</strong> %s (%s)<br><br><strong>Message:</strong> %s<br><br><strong>File:</strong> %s<br><strong>Line:</strong> %s
				', $last_error['type'], $error_types[(int)$last_error['type']], nl2br($last_error['message']), $last_error['file'], $last_error['line']);
			displayError($error, 'Fatal error', $color);
	}
}

set_error_handler('errorHandler');
register_shutdown_function('shutdownHandler');