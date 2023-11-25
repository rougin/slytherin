<?php

use Rougin\Slytherin\Forward\Fixture\Router;

$config = array('name' => 'Slytherin');
$config['base_url'] = 'http://localhost:8000';
$config['environment'] = 'development';
$config['timezone'] = 'Asia/Manila';

$http = array();
$http['cookies'] = array();
$http['files'] = array();
$http['get'] = array();
$http['post'] = array();
$http['server'] = array();
$config['http'] = $http;

$packages = array();

// Testing Packages -----------------------------------------------------
$packages[] = 'Rougin\Slytherin\Forward\Fixture\Packages\HttpPackage';
$packages[] = 'Rougin\Slytherin\Forward\Fixture\Packages\RoutingPackage';
// ----------------------------------------------------------------------

// Slytherin Integrations --------------------------------------------
$packages[] = 'Rougin\Slytherin\Integration\ConfigurationIntegration';
$packages[] = 'Rougin\Slytherin\Middleware\MiddlewareIntegration';
// -------------------------------------------------------------------

$config['router'] = new Router;

$config['middlewares'] = array();

$config['packages'] = $packages;

return $config;