<?php
/* load the core files */
require_once BASEPATH . "/application/config.php";
require_once BASEPATH . "/system/load.php";
require_once BASEPATH . "/system/controller.php";
require_once BASEPATH . "/system/error.php";
// require_once BASEPATH . "/system/html.php";
/* loads the library */
load::library();
/* loads the controllers */
load::controllers();
/* loads the models */
load::models();
/* loads the core classes */
load::core(array('router', 'view'));
/* load the the module base on the URI segments */
router::load();