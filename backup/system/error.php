<?php if ( ! defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Slytherin - Yet another barebones web framework for PHP
 *
 * @author     Rougin Royce Gutib <rougin.royce@gmail.com>
 * @package    Slytherin
 */

/**
 * Disable the default error reporting
 * and replace it with the customized error handler
 */
// error_reporting(0);
set_error_handler('error_handler', E_ALL);
register_shutdown_function('fatal_handler');

/**
 * Error Handler
 *
 * Replaces the current error handler with this one
 * 
 * @param  string $errno
 * @param  string $message
 * @param  string $filepath
 * @param  string $line
 * @param  array $vars
 */
function error_handler($errno, $message, $filepath, $line, $vars = NULL) {
	/**
	 * Find the first occurence of ".php" string
	 */
	$limit    = strrpos($message, '.php') + 4;
	$message  = str_replace(array(BASE, ' and defined'), '', $message);

	if ($limit == 4) {
		$limit = strlen($message);
	}

	/**
	 * Get the message
	 */
	$message  = substr($message, 0, $limit);

	/**
	 * Get the filename from the BASE path
	 */
	$filepath = str_replace(BASE, '', $filepath);

	/**
	 * Load the Error Handling Template
	 */
	require APPLICATION . 'errors/error_php.php';
}

/**
 * Fatal Error Handler
 * 
 * Replaces the current fatal error handler
 */
function fatal_handler() {
	$errfile = 'unknown file';
	$errstr  = 'shutdown';
	$errno   = E_CORE_ERROR;
	$errline = 0;

	$error = error_get_last();

	if ($error !== NULL) {
		$errno   = $error['type'];
		$errfile = $error['file'];
		$errline = $error['line'];

		$limit = strrpos($error['message'], ' (');
		$errstr  = substr($error['message'], 0, $limit);

		return error_handler( $errno, $errstr, $errfile, $errline);
	}
}