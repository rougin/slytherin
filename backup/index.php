<?php

/**
 * Slytherin - Yet another barebones web framework for PHP
 *
 * @author     Rougin Royce Gutib <rougin.royce@gmail.com>
 * @package    Slytherin
 */

/**
 * Set the Application Environment
 */
define('ENVIRONMENT', 'development');

/**
 * Path to the application folder
 */
define('APPLICATION', dirname(__FILE__) . '/application/');

/**
 * Path to the base or root directory
 */
define('BASE', dirname(__FILE__) . '/');

/**
 * Base URL
 */
// define('URL', 'http:/' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
define('URL', 'http://slytherin.dev/');

/**
 * Path to the system core files
 */
define('SYSTEM', dirname(__FILE__) . '/system/');

/**
 * Path to the vendors (external libraries)
 */
define('VENDOR', dirname(__FILE__) . '/vendor/');

/**
 * Load the (optional) Composer auto-loader
 */
if (file_exists('vendor/autoload.php')) {
	require 'vendor/autoload.php';
}

/**
 * Load the Slytherin Core
 */
require SYSTEM . 'slytherin.php';
$application = new Slytherin();

/**
 * Launch the application
 */
$application->launch();