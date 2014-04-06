<?php
define('APPLICATION', dirname(__FILE__) . '/application/');
define('ROOT', dirname(__FILE__));
define('URL', 'http://slytherin.dev/');
define('SYSTEM', dirname(__FILE__) . '/system/');
define('VENDOR', dirname(__FILE__) . '/vendor/');

if (file_exists('vendor/autoload.php'))
{
	require 'vendor/autoload.php';
}

require SYSTEM . 'slytherin.php';
?>