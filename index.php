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