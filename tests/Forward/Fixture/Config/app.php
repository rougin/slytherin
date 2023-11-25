<?php

use Rougin\Slytherin\Forward\Fixture\Router;

return
[
    'name' => 'Slytherin',

    'base_url' => 'http://localhost:8000',

    'environment' => 'development',

    'timezone' => 'Asia/Manila',

    'http' =>
    [
        'cookies' => array(),

        'files' => array(),

        'get' => array(),

        'post' => array(),

        'server' => array(),
    ],

    'router' => new Router,

    'middlewares' => [],

    'packages' =>
    [
        // Testing Packages ---------------------------------------
        'Rougin\Slytherin\Forward\Fixture\Packages\HttpPackage',
        'Rougin\Slytherin\Forward\Fixture\Packages\RoutingPackage',
        // --------------------------------------------------------

        // Slytherin Integrations ------------------------------
        'Rougin\Slytherin\Integration\ConfigurationIntegration',
        'Rougin\Slytherin\Middleware\MiddlewareIntegration',
        // -----------------------------------------------------
    ],
];