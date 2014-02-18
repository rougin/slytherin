<?php
define('APPLICATION', dirname(__FILE__) . '/application/');
define('ROOT', dirname(__FILE__));
define('SYSTEM', dirname(__FILE__) . '/system/');

if (file_exists('vendor/autoload.php'))
{
	require 'vendor/autoload.php';
}

require SYSTEM . 'slytherin.php';
?>