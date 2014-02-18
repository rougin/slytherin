<<<<<<< HEAD
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
=======
<?php
/* determine the root path */
define('BASEPATH', dirname(__FILE__));
define('APPPATH', BASEPATH . "/application/");
/* get the actual link */
// echo "ACTUAL LINK = http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . "<br/>";
/* initialize the core functionalities */
require_once BASEPATH . "/system/core.php";
/* view -> show */
/* view::page */
>>>>>>> 521c8223a219db5c97689ed9578dfa8794b6c930
