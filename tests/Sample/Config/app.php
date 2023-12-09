<?php

use Rougin\Slytherin\Sample\Router;

$packages = array();

// Testing Packages --------------------------------------------
$packages[] = 'Rougin\Slytherin\Sample\Packages\HttpPackage';
$packages[] = 'Rougin\Slytherin\Sample\Packages\RoutingPackage';
// -------------------------------------------------------------

$config = array('name' => 'Slytherin');

$config['base_url'] = 'http://localhost:8000';

$config['environment'] = 'development';

$config['timezone'] = 'Asia/Manila';

$config['router'] = new Router;

$config['middlewares'] = array();

$config['packages'] = $packages;

return (array) $config;
